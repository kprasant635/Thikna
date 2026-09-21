<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileAndReferralsTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_profile_page(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'phone' => '9876543210',
            'referral_code' => 'THK12345',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard.profile'));

        $response->assertStatus(200);
        $response->assertSee('My Profile &amp; Digital ID Card', false);
        $response->assertSee('John Doe');
        $response->assertSee('THK12345');
    }

    public function test_user_can_update_profile_and_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'Old Name',
            'address' => 'Old Address',
        ]);

        $photo = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user)->put(route('dashboard.profile.update'), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'address' => 'New Full Address',
            'profile_photo' => $photo,
        ]);

        $response->assertRedirect(route('dashboard.profile'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('updated@example.com', $user->email);
        $this->assertEquals('New Full Address', $user->address);
        $this->assertNotNull($user->profile_photo);
        Storage::disk('public')->assertExists($user->profile_photo);
    }

    public function test_authenticated_user_can_view_referrals_list(): void
    {
        $referrer = User::factory()->create([
            'name' => 'Referrer User',
            'referral_code' => 'THK99999',
        ]);

        $referredUser = User::factory()->create([
            'name' => 'Referred Member',
            'phone' => '9988776655',
            'referred_by_id' => $referrer->id,
        ]);

        $response = $this->actingAs($referrer)->get(route('dashboard.referrals'));

        $response->assertStatus(200);
        $response->assertSee('My Referral List');
        $response->assertSee('Referred Member');
        $response->assertSee('THK99999');
    }

    public function test_user_can_download_virtual_id_card(): void
    {
        $user = User::factory()->create([
            'name' => 'ID Pass User',
            'referral_code' => 'THK77777',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard.idcard.download'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
