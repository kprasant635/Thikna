<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseVideo;
use App\Services\LearningService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function __construct(
        protected LearningService $learningService
    ) {}

    /**
     * Learning Dashboard listing user's selected courses, video counts, progress bars, certificate status, and benefits.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $learningData = $this->learningService->getUserCourses($user);

        return view('pages.courses.index', array_merge([
            'user' => $user,
        ], $learningData));
    }

    /**
     * Short-video learning player interface for a specific course.
     */
    public function show(Request $request, Course $course): View
    {
        $user = $request->user();

        if (! $this->learningService->isCourseAccessAllowed($user, $course)) {
            abort(403, 'You are only authorized to access courses included in your subscription.');
        }

        $learningData = $this->learningService->getUserCourses($user);
        
        $courseData = $learningData['courses']->firstWhere('id', $course->id) ?? $course;
        $videos = $course->activeVideos;

        // Fetch user progress for each video
        $videoProgresses = $user->videoProgress()
            ->where('course_id', $course->id)
            ->get()
            ->keyBy('video_id');

        foreach ($videos as $video) {
            $video->user_progress = $videoProgresses->get($video->id);
        }

        return view('pages.courses.show', [
            'user' => $user,
            'course' => $courseData,
            'videos' => $videos,
            'learningData' => $learningData,
        ]);
    }

    /**
     * Update video watch progress via AJAX and evaluate completion thresholds.
     */
    public function updateVideoProgress(Request $request, Course $course, CourseVideo $video): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'watch_time_seconds' => ['required', 'integer', 'min:0'],
            'percentage_watched' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        try {
            $result = $this->learningService->updateVideoProgress(
                $user,
                $course,
                $video,
                (int) $validated['watch_time_seconds'],
                (float) $validated['percentage_watched']
            );

            return response()->json(array_merge([
                'success' => true,
                'message' => 'Progress updated.',
            ], $result));
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 403);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save progress.',
            ], 500);
        }
    }
}
