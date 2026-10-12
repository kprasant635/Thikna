<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\LandingSlider;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_user_is_denied_access_to_admin_dashboard(): void
    {
        $regularUser = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this->actingAs($regularUser)->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_login_page_renders_successfully(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
        $response->assertSee('Admin Portal');
        $response->assertSee('Username or Email');
        $response->assertSee('Password');
    }

    public function test_admin_can_login_with_username_and_password(): void
    {
        $admin = User::factory()->create([
            'username' => 'superadmin',
            'email' => 'superadmin@skopx.com',
            'password' => Hash::make('secretpass123'),
            'is_admin' => true,
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'username' => 'superadmin',
            'password' => 'secretpass123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_can_login_with_email_and_password(): void
    {
        $admin = User::factory()->create([
            'username' => 'adminmanager',
            'email' => 'manager@skopx.com',
            'password' => Hash::make('secretpass123'),
            'is_admin' => true,
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'username' => 'manager@skopx.com',
            'password' => 'secretpass123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_login_fails_with_invalid_password(): void
    {
        User::factory()->create([
            'username' => 'adminuser',
            'password' => Hash::make('correctpassword'),
            'is_admin' => true,
        ]);

        $response = $this->from(route('admin.login'))->post(route('admin.login.submit'), [
            'username' => 'adminuser',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_admin_dashboard_displays_all_required_metrics(): void
    {
        $admin = User::where('username', 'admin')->first()
            ?? User::factory()->create(['is_admin' => true, 'status' => User::STATUS_ACTIVE]);

        // Create 2 active users, 1 inactive user
        $activeUser1 = User::factory()->create([
            'status' => User::STATUS_ACTIVE,
        ]);
        $activeUser2 = User::factory()->create([
            'status' => User::STATUS_ACTIVE,
            'referred_by_id' => $activeUser1->id, // referral
        ]);
        $pendingUser = User::factory()->create([
            'status' => User::STATUS_PENDING,
            'referred_by_id' => $activeUser1->id, // referral
        ]);

        // Create Courses with Products
        $product1 = Product::create([
            'name' => 'Web Design Product',
            'category' => 'Technology',
            'icon' => '💻',
            'is_active' => true,
        ]);
        $product2 = Product::create([
            'name' => 'Digital Marketing Product',
            'category' => 'Business',
            'icon' => '📈',
            'is_active' => true,
        ]);

        Course::create([
            'product_id' => $product1->id,
            'title' => 'Web Design Mastery',
            'slug' => 'web-design-mastery',
            'description' => 'Course description',
        ]);
        Course::create([
            'product_id' => $product2->id,
            'title' => 'Digital Marketing',
            'slug' => 'digital-marketing',
            'description' => 'Course description',
        ]);

        // Create Subscriptions
        Subscription::create([
            'user_id' => $activeUser1->id,
            'amount' => 1999,
            'status' => Subscription::STATUS_ACTIVE,
            'activated_at' => now(),
        ]);
        Subscription::create([
            'user_id' => $activeUser2->id,
            'amount' => 2499,
            'status' => Subscription::STATUS_ACTIVE,
            'activated_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('totalReferralsCount', 2);
        $response->assertViewHas('totalActiveUsersCount', 3); // 2 created + 1 admin
        $response->assertViewHas('totalInactiveUsersCount', 1);
        $response->assertViewHas('totalCourseCount', 2);
        $response->assertViewHas('totalSubscribeCount', 2);

        $response->assertSee('Total Referrals');
        $response->assertSee('Active Users');
        $response->assertSee('Inactive Users');
        $response->assertSee('Course Count');
        $response->assertSee('Total Subscriptions');
    }

    public function test_admin_can_logout(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_admin_can_view_total_referrals_list_with_details(): void
    {
        $admin = User::where('username', 'admin')->first()
            ?? User::factory()->create(['is_admin' => true]);

        $referrer = User::factory()->create([
            'name' => 'Suresh Referrer',
            'phone' => '9876543210',
            'referral_code' => 'SURESH99',
        ]);

        $refMember = User::factory()->create([
            'name' => 'Ramesh Referred',
            'phone' => '9123456789',
            'referred_by_id' => $referrer->id,
            'status' => User::STATUS_ACTIVE,
            'created_at' => now()->subDay(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.referrals'));

        $response->assertStatus(200);
        $response->assertSee('Total Referral Directory');
        $response->assertSee('Ramesh Referred');
        $response->assertSee('Suresh Referrer');
        $response->assertSee('SURESH99');
        $response->assertSee('Subscribed (Active)');
    }

    public function test_admin_can_view_landing_sliders_in_settings(): void
    {
        $admin = User::where('username', 'admin')->first()
            ?? User::factory()->create(['is_admin' => true]);

        LandingSlider::create([
            'title' => 'Sample Test Hero Slide',
            'eyebrow' => 'TESTING HERO',
            'description' => 'This is a test hero description.',
            'image' => 'images/hero-female-learner.jpg',
            'button_text' => 'Get Started',
            'button_url' => '/register',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.sliders.index'));

        $response->assertStatus(200);
        $response->assertSee('Landing Page Hero Sliders');
        $response->assertSee('Sample Test Hero Slide');
        $response->assertSee('TESTING HERO');
        $response->assertSee('Settings');
    }

    public function test_admin_can_upload_and_create_landing_slider(): void
    {
        Storage::fake('public');

        $admin = User::where('username', 'admin')->first()
            ?? User::factory()->create(['is_admin' => true]);

        $fakeImage = UploadedFile::fake()->image('banner_hero.jpg', 1200, 800);

        $response = $this->actingAs($admin)->post(route('admin.sliders.store'), [
            'title' => 'Revolutionize Your Skills Today',
            'eyebrow' => 'NEW BATCH',
            'description' => 'Comprehensive skill building programs.',
            'button_text' => 'Join SKOP-X',
            'button_url' => '/register',
            'order' => 5,
            'is_active' => '1',
            'image' => $fakeImage,
        ]);

        $response->assertRedirect(route('admin.sliders.index'));
        $response->assertSessionHas('success');

        $slider = LandingSlider::where('title', 'Revolutionize Your Skills Today')->first();
        $this->assertNotNull($slider);
        $this->assertEquals('NEW BATCH', $slider->eyebrow);
        $this->assertEquals(5, $slider->order);
        $this->assertTrue($slider->is_active);

        Storage::disk('public')->assertExists($slider->image);
    }

    public function test_admin_can_edit_and_update_landing_slider(): void
    {
        Storage::fake('public');

        $admin = User::where('username', 'admin')->first()
            ?? User::factory()->create(['is_admin' => true]);

        $slider = LandingSlider::create([
            'title' => 'Original Slide Title',
            'eyebrow' => 'ORIGINAL TAG',
            'description' => 'Original description.',
            'image' => 'images/hero-female-learner.jpg',
            'button_text' => 'Enroll',
            'button_url' => '/register',
            'order' => 1,
            'is_active' => true,
        ]);

        $newImage = UploadedFile::fake()->image('updated_banner.png', 1000, 600);

        $response = $this->actingAs($admin)->put(route('admin.sliders.update', $slider), [
            'title' => 'Updated Slide Title',
            'eyebrow' => 'UPDATED TAG',
            'description' => 'Updated slide description.',
            'button_text' => 'Start Now',
            'button_url' => '/course-catalog',
            'order' => 2,
            'is_active' => '1',
            'image' => $newImage,
        ]);

        $response->assertRedirect(route('admin.sliders.index'));
        $slider->refresh();

        $this->assertEquals('Updated Slide Title', $slider->title);
        $this->assertEquals('UPDATED TAG', $slider->eyebrow);
        $this->assertEquals('Start Now', $slider->button_text);
        $this->assertEquals(2, $slider->order);
        Storage::disk('public')->assertExists($slider->image);
    }

    public function test_admin_can_toggle_slider_status(): void
    {
        $admin = User::where('username', 'admin')->first()
            ?? User::factory()->create(['is_admin' => true]);

        $slider = LandingSlider::create([
            'title' => 'Toggle Test Slide',
            'image' => 'images/hero-female-learner.jpg',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.sliders.toggle', $slider));

        $response->assertRedirect(route('admin.sliders.index'));
        $slider->refresh();
        $this->assertFalse($slider->is_active);

        $this->actingAs($admin)->patch(route('admin.sliders.toggle', $slider));
        $slider->refresh();
        $this->assertTrue($slider->is_active);
    }

    public function test_admin_can_delete_landing_slider(): void
    {
        Storage::fake('public');

        $admin = User::where('username', 'admin')->first()
            ?? User::factory()->create(['is_admin' => true]);

        $fakeFile = UploadedFile::fake()->image('to_delete.jpg');
        $storedPath = $fakeFile->store('sliders', 'public');

        $slider = LandingSlider::create([
            'title' => 'Slide to be deleted',
            'image' => $storedPath,
            'is_active' => true,
        ]);

        Storage::disk('public')->assertExists($storedPath);

        $response = $this->actingAs($admin)->delete(route('admin.sliders.destroy', $slider));

        $response->assertRedirect(route('admin.sliders.index'));
        $this->assertDatabaseMissing('landing_sliders', ['id' => $slider->id]);
        Storage::disk('public')->assertMissing($storedPath);
    }

    public function test_landing_page_renders_active_sliders(): void
    {
        LandingSlider::create([
            'title' => 'Welcome to SKOP-X Academy',
            'eyebrow' => 'PREMIUM COURSES',
            'description' => 'Learn industry level skills right here.',
            'image' => 'images/hero-female-learner.jpg',
            'button_text' => 'Get Started Today',
            'button_url' => '/register',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Welcome to SKOP-X Academy');
        $response->assertSee('PREMIUM COURSES');
        $response->assertSee('Get Started Today');
    }
}
