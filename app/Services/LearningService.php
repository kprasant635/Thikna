<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseVideo;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserCourseProgress;
use App\Models\UserVideoProgress;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LearningService
{
    /**
     * Get courses associated with the user's active subscription along with progress tracking.
     */
    public function getUserCourses(User $user): array
    {
        $activeSubscription = $user->subscriptions()
            ->where('status', Subscription::STATUS_ACTIVE)
            ->latest()
            ->first();

        if (! $activeSubscription) {
            return [
                'subscription' => null,
                'courses' => collect(),
                'total_courses' => 0,
                'completed_courses' => 0,
                'overall_percentage' => 0,
                'is_all_completed' => false,
                'certificate' => null,
            ];
        }

        $productIds = $activeSubscription->products->pluck('id')->toArray();

        $courses = Course::whereIn('product_id', $productIds)
            ->where('is_active', true)
            ->with(['product', 'activeVideos'])
            ->get();

        // Fetch user progress for all videos & courses
        $videoProgresses = UserVideoProgress::where('user_id', $user->id)
            ->get()
            ->keyBy('video_id');

        $courseProgresses = UserCourseProgress::where('user_id', $user->id)
            ->get()
            ->keyBy('course_id');

        $completedCoursesCount = 0;

        foreach ($courses as $course) {
            $videos = $course->activeVideos;
            $totalVideos = $videos->count();
            $completedVideos = 0;

            foreach ($videos as $video) {
                $progress = $videoProgresses->get($video->id);
                $video->user_progress = $progress;
                if ($progress && $progress->is_completed) {
                    $completedVideos++;
                }
            }

            $course->total_videos = $totalVideos;
            $course->completed_videos = $completedVideos;
            $course->progress_percentage = $totalVideos > 0 ? (int) round(($completedVideos / $totalVideos) * 100) : 0;
            
            $courseProgress = $courseProgresses->get($course->id);
            $course->is_completed = ($courseProgress && $courseProgress->is_completed) || ($totalVideos > 0 && $completedVideos === $totalVideos);

            if ($course->is_completed) {
                $completedCoursesCount++;
            }
        }

        $totalCoursesCount = $courses->count();
        $isAllCompleted = $totalCoursesCount > 0 && $completedCoursesCount === $totalCoursesCount;

        $certificate = Certificate::where('user_id', $user->id)
            ->where('subscription_id', $activeSubscription->id)
            ->first();

        // Auto-generate certificate if all courses completed but certificate missing
        if ($isAllCompleted && ! $certificate) {
            $certificate = $this->generateCertificate($user, $activeSubscription, $courses);
        }

        return [
            'subscription' => $activeSubscription,
            'courses' => $courses,
            'total_courses' => $totalCoursesCount,
            'completed_courses' => $completedCoursesCount,
            'overall_percentage' => $totalCoursesCount > 0 ? (int) round(($completedCoursesCount / $totalCoursesCount) * 100) : 0,
            'is_all_completed' => $isAllCompleted,
            'certificate' => $certificate,
        ];
    }

    /**
     * Check if a course belongs to a user's active subscription.
     */
    public function isCourseAccessAllowed(User $user, Course $course): bool
    {
        $activeSubscription = $user->subscriptions()
            ->where('status', Subscription::STATUS_ACTIVE)
            ->latest()
            ->first();

        if (! $activeSubscription) {
            return false;
        }

        return $activeSubscription->products()->where('products.id', $course->product_id)->exists();
    }

    /**
     * Update video watch progress and auto-evaluate course / certificate completion.
     */
    public function updateVideoProgress(
        User $user,
        Course $course,
        CourseVideo $video,
        int $watchTimeSeconds,
        float $percentageWatched
    ): array {
        if (! $this->isCourseAccessAllowed($user, $course)) {
            throw new \InvalidArgumentException('You are not authorized to access this course.');
        }

        if ($video->course_id !== $course->id) {
            throw new \InvalidArgumentException('Video does not belong to the specified course.');
        }

        $percentageWatched = min(100.00, max(0.00, $percentageWatched));

        return DB::transaction(function () use ($user, $course, $video, $watchTimeSeconds, $percentageWatched) {
            $existing = UserVideoProgress::where('user_id', $user->id)
                ->where('video_id', $video->id)
                ->first();

            $newWatchTime = max($watchTimeSeconds, $existing?->watch_time_seconds ?? 0);
            $newPercentage = max($percentageWatched, (float)($existing?->percentage_watched ?? 0));
            $isCompleted = ($existing?->is_completed) || ($newPercentage >= $video->required_watch_percentage);

            $videoProgress = UserVideoProgress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'video_id' => $video->id,
                ],
                [
                    'course_id' => $course->id,
                    'watch_time_seconds' => $newWatchTime,
                    'percentage_watched' => $newPercentage,
                    'is_completed' => $isCompleted,
                    'completed_at' => $isCompleted ? ($existing?->completed_at ?? now()) : null,
                ]
            );

            // Check if course is now fully completed
            $totalActiveVideos = $course->activeVideos()->count();
            $completedVideosCount = UserVideoProgress::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->where('is_completed', true)
                ->count();

            $isCourseCompleted = ($totalActiveVideos > 0) && ($completedVideosCount >= $totalActiveVideos);

            if ($isCourseCompleted) {
                UserCourseProgress::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                    ],
                    [
                        'is_completed' => true,
                        'completed_at' => now(),
                    ]
                );
            }

            // Check overall courses status for certification
            $learningData = $this->getUserCourses($user);
            $certificate = $learningData['certificate'];

            if ($learningData['is_all_completed'] && ! $certificate && $learningData['subscription']) {
                $certificate = $this->generateCertificate($user, $learningData['subscription'], $learningData['courses']);
            }

            return [
                'video_progress' => $videoProgress,
                'is_video_completed' => $videoProgress->is_completed,
                'is_course_completed' => $isCourseCompleted,
                'is_all_courses_completed' => $learningData['is_all_completed'],
                'certificate' => $certificate,
            ];
        });
    }

    /**
     * Generate unique Certificate of Completion for user.
     */
    public function generateCertificate(User $user, Subscription $subscription, Collection $courses): Certificate
    {
        return DB::transaction(function () use ($user, $subscription, $courses) {
            $existing = Certificate::where('user_id', $user->id)
                ->where('subscription_id', $subscription->id)
                ->first();

            if ($existing) {
                return $existing;
            }

            $courseTitles = $courses->pluck('title')->toArray();

            $certificate = Certificate::create([
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'certificate_number' => Certificate::generateUniqueNumber(),
                'issued_at' => now(),
                'payload' => [
                    'user_name' => $user->name,
                    'courses' => $courseTitles,
                    'categories' => $courses->pluck('product.category')->filter()->unique()->values()->toArray(),
                    'subscription_reference' => $subscription->id,
                ],
            ]);

            $user->update([
                'learning_status' => User::LEARNING_CERTIFIED,
            ]);

            return $certificate;
        });
    }
}
