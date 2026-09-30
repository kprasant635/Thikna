<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_slides_through_all_active_courses(): void
    {
        $product = Product::create(['name' => 'Digital Skills']);

        Course::create([
            'product_id' => $product->id,
            'title' => 'Digital Skills Masterclass',
            'slug' => 'digital-skills-masterclass',
            'description' => 'Build practical digital skills.',
            'is_active' => true,
        ]);
        Course::create([
            'product_id' => $product->id,
            'title' => 'Archived Masterclass',
            'slug' => 'archived-masterclass',
            'is_active' => false,
        ]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Digital Skills Masterclass')
            ->assertSee('Build practical digital skills.')
            ->assertSee('hero-female-learner.jpg')
            ->assertDontSee('Archived Masterclass');
    }

    public function test_home_page_shows_the_five_most_recent_registered_users(): void
    {
        foreach (range(1, 6) as $number) {
            User::factory()->create([
                'name' => 'Registered User '.$number,
                'address' => 'Address '.$number,
                'referral_code' => 'THK00000'.$number,
                'created_at' => now()->subDays(6 - $number),
            ]);
        }

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Registered User 6')
            ->assertSee('THK000006')
            ->assertSee('Address 6')
            ->assertDontSee('Registered User 1');
    }

    public function test_home_page_renders_featured_video_thumbnails(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('images/promo-male-learner.jpg')
            ->assertSee('images/hero-female-learner.jpg')
            ->assertSee('images/hero-female-learner1.jpg')
            ->assertSee('images/growth-plant.jpg');
    }
}
