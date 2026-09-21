<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseVideo;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserVideoProgress;
use Database\Seeders\CourseSeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LearningAndCertificateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ProductSeeder::class);
        $this->seed(CourseSeeder::class);
    }

    public function test_active_user_with_subscription_can_access_selected_courses(): void
    {
        $user = User::create([
            'name' => 'Active Learner',
            'phone' => '9876543210',
            'status' => User::STATUS_ACTIVE,
            'address' => 'Bhubaneswar',
        ]);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'amount' => 200,
            'status' => Subscription::STATUS_ACTIVE,
            'activated_at' => now(),
        ]);
        $subscription->products()->sync([1, 2, 3]);

        $response = $this->actingAs($user)->get(route('courses.index'));

        $response->assertStatus(200);
        $response->assertSee('Educational Masterclasses');
        $response->assertSee('Product (Education / Training) Masterclass');
        $response->assertSee('Yoga (Exercise) Masterclass');
        $response->assertSee('Zumba (Exercise) Masterclass');
    }

    public function test_user_cannot_access_unselected_course(): void
    {
        $user = User::create([
            'name' => 'Restricted Learner',
            'phone' => '9876543210',
            'status' => User::STATUS_ACTIVE,
            'address' => 'Bhubaneswar',
        ]);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'amount' => 200,
            'status' => Subscription::STATUS_ACTIVE,
            'activated_at' => now(),
        ]);
        // User selected products 1, 2, 3
        $subscription->products()->sync([1, 2, 3]);

        // Try accessing course for product 10
        $unselectedCourse = Course::where('product_id', 10)->firstOrFail();

        $response = $this->actingAs($user)->get(route('courses.show', $unselectedCourse));

        $response->assertStatus(403);
    }

    public function test_video_progress_requires_80_percent_watch_time_to_complete(): void
    {
        $user = User::create([
            'name' => 'Progress User',
            'phone' => '9876543210',
            'status' => User::STATUS_ACTIVE,
            'address' => 'Bhubaneswar',
        ]);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'amount' => 200,
            'status' => Subscription::STATUS_ACTIVE,
            'activated_at' => now(),
        ]);
        $subscription->products()->sync([1, 2, 3]);

        $course = Course::where('product_id', 1)->firstOrFail();
        $video = $course->activeVideos->firstOrFail();

        // 1. Partial watch (50%) -> should NOT complete video
        $response = $this->actingAs($user)->postJson(route('courses.video.progress', [$course, $video]), [
            'watch_time_seconds' => 45,
            'percentage_watched' => 50,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_video_completed' => false,
            'is_course_completed' => false,
        ]);

        $this->assertDatabaseHas('user_video_progress', [
            'user_id' => $user->id,
            'video_id' => $video->id,
            'is_completed' => false,
        ]);

        // 2. Full watch (85%) -> SHOULD complete video
        $response2 = $this->actingAs($user)->postJson(route('courses.video.progress', [$course, $video]), [
            'watch_time_seconds' => 77,
            'percentage_watched' => 85,
        ]);

        $response2->assertStatus(200);
        $response2->assertJson([
            'success' => true,
            'is_video_completed' => true,
        ]);

        $this->assertDatabaseHas('user_video_progress', [
            'user_id' => $user->id,
            'video_id' => $video->id,
            'is_completed' => true,
        ]);
    }

    public function test_completing_all_videos_completes_course(): void
    {
        $user = User::create([
            'name' => 'Course Finisher',
            'phone' => '9876543210',
            'status' => User::STATUS_ACTIVE,
            'address' => 'Bhubaneswar',
        ]);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'amount' => 200,
            'status' => Subscription::STATUS_ACTIVE,
            'activated_at' => now(),
        ]);
        $subscription->products()->sync([1, 2, 3]);

        $course = Course::where('product_id', 1)->firstOrFail();
        $videos = $course->activeVideos;

        foreach ($videos as $video) {
            $this->actingAs($user)->postJson(route('courses.video.progress', [$course, $video]), [
                'watch_time_seconds' => $video->duration_seconds,
                'percentage_watched' => 100,
            ]);
        }

        $this->assertDatabaseHas('user_course_progress', [
            'user_id' => $user->id,
            'course_id' => $course->id,
            'is_completed' => true,
        ]);
    }

    public function test_certificate_locked_until_all_3_courses_completed(): void
    {
        $user = User::create([
            'name' => 'Partial Finisher',
            'phone' => '9876543210',
            'status' => User::STATUS_ACTIVE,
            'address' => 'Bhubaneswar',
        ]);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'amount' => 200,
            'status' => Subscription::STATUS_ACTIVE,
            'activated_at' => now(),
        ]);
        $subscription->products()->sync([1, 2, 3]);

        // Complete only course 1 & course 2 (2 out of 3)
        $courses = Course::whereIn('product_id', [1, 2])->get();
        foreach ($courses as $course) {
            foreach ($course->activeVideos as $video) {
                $this->actingAs($user)->postJson(route('courses.video.progress', [$course, $video]), [
                    'watch_time_seconds' => 100,
                    'percentage_watched' => 100,
                ]);
            }
        }

        // Try viewing certificate
        $response = $this->actingAs($user)->get(route('certificate.show'));
        $response->assertRedirect(route('courses.index'));
        $response->assertSessionHas('warning');

        $this->assertDatabaseMissing('certificates', [
            'user_id' => $user->id,
        ]);

        // Benefits should also be locked
        $benefitsResponse = $this->actingAs($user)->get(route('benefits.index'));
        $benefitsResponse->assertRedirect(route('courses.index'));
    }

    public function test_completing_all_3_courses_generates_certificate_and_unlocks_benefits(): void
    {
        $user = User::create([
            'name' => 'Master Student',
            'phone' => '9876543210',
            'status' => User::STATUS_ACTIVE,
            'address' => 'Bhubaneswar',
        ]);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'amount' => 200,
            'status' => Subscription::STATUS_ACTIVE,
            'activated_at' => now(),
        ]);
        $subscription->products()->sync([1, 2, 3]);

        // Complete all 3 courses
        $courses = Course::whereIn('product_id', [1, 2, 3])->get();
        foreach ($courses as $course) {
            foreach ($course->activeVideos as $video) {
                $this->actingAs($user)->postJson(route('courses.video.progress', [$course, $video]), [
                    'watch_time_seconds' => 100,
                    'percentage_watched' => 100,
                ]);
            }
        }

        // Check user is certified
        $user->refresh();
        $this->assertTrue($user->isCertified());
        $this->assertEquals(User::LEARNING_CERTIFIED, $user->learning_status);

        // Certificate should exist in database with unique number
        $this->assertDatabaseHas('certificates', [
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
        ]);

        $certificate = Certificate::where('user_id', $user->id)->first();
        $this->assertStringStartsWith('THK-CERT-', $certificate->certificate_number);

        // Certificate view should now return 200
        $certResponse = $this->actingAs($user)->get(route('certificate.show'));
        $certResponse->assertStatus(200);
        $certResponse->assertSee('Certificate of Completion');
        $certResponse->assertSee($user->name);

        // Benefits dashboard should now return 200
        $benefitsResponse = $this->actingAs($user)->get(route('benefits.index'));
        $benefitsResponse->assertStatus(200);
        $benefitsResponse->assertSee('Exclusive Member Benefits');
        $benefitsResponse->assertSee('Verified SkopX Member Badge');
    }

    public function test_certificate_generation_is_idempotent(): void
    {
        $user = User::create([
            'name' => 'Idempotent Student',
            'phone' => '9876543210',
            'status' => User::STATUS_ACTIVE,
            'address' => 'Bhubaneswar',
        ]);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'amount' => 200,
            'status' => Subscription::STATUS_ACTIVE,
            'activated_at' => now(),
        ]);
        $subscription->products()->sync([1, 2, 3]);

        $courses = Course::whereIn('product_id', [1, 2, 3])->get();
        foreach ($courses as $course) {
            foreach ($course->activeVideos as $video) {
                $this->actingAs($user)->postJson(route('courses.video.progress', [$course, $video]), [
                    'watch_time_seconds' => 100,
                    'percentage_watched' => 100,
                ]);
            }
        }

        // Assert only 1 certificate record created
        $this->assertEquals(1, Certificate::where('user_id', $user->id)->count());

        // Repeat viewing or triggering progress
        $lastCourse = $courses->last();
        $lastVideo = $lastCourse->activeVideos->last();

        $this->actingAs($user)->postJson(route('courses.video.progress', [$lastCourse, $lastVideo]), [
            'watch_time_seconds' => 100,
            'percentage_watched' => 100,
        ]);

        $this->assertEquals(1, Certificate::where('user_id', $user->id)->count());
    }
}
