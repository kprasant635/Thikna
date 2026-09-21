<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

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
}
