<?php

namespace Tests\Feature;

use App\Models\Otp;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_page_can_be_rendered(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertSee('Create Your Account');
        $response->assertSee('Referral Code');
    }

    public function test_send_otp_success_for_valid_10_digit_phone(): void
    {
        $response = $this->postJson(route('register.otp.send'), [
            'phone' => '9876543210',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('otps', [
            'phone' => '9876543210',
            'verified_at' => null,
            'used_at' => null,
        ]);
    }

    public function test_send_otp_fails_for_invalid_phone_format(): void
    {
        // 9 digits
        $response = $this->postJson(route('register.otp.send'), [
            'phone' => '987654321',
        ]);
        $response->assertStatus(422);

        // Alpha characters
        $response = $this->postJson(route('register.otp.send'), [
            'phone' => '98765abcde',
        ]);
        $response->assertStatus(422);
    }

    public function test_send_otp_fails_for_already_registered_phone(): void
    {
        User::create([
            'name' => 'Existing User',
            'phone' => '9876543210',
            'email' => 'existing@example.com',
            'address' => 'Bhubaneswar',
        ]);

        $response = $this->postJson(route('register.otp.send'), [
            'phone' => '9876543210',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'This mobile number is already registered. Please log in or use another number.',
        ]);
    }

    public function test_send_otp_rate_limiting_cooldown(): void
    {
        // First send
        $this->postJson(route('register.otp.send'), [
            'phone' => '9876543210',
        ])->assertStatus(200);

        // Immediate second send should fail due to 60s cooldown
        $response = $this->postJson(route('register.otp.send'), [
            'phone' => '9876543210',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function test_verify_otp_fails_with_invalid_otp(): void
    {
        Otp::create([
            'phone' => '9876543210',
            'otp_hash' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
        ]);

        $response = $this->postJson(route('register.otp.verify'), [
            'phone' => '9876543210',
            'otp' => '999999',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);

        $this->assertDatabaseHas('otps', [
            'phone' => '9876543210',
            'attempts' => 1,
            'verified_at' => null,
        ]);
    }

    public function test_verify_otp_fails_when_expired(): void
    {
        Otp::create([
            'phone' => '9876543210',
            'otp_hash' => Hash::make('123456'),
            'expires_at' => now()->subMinute(),
            'attempts' => 0,
        ]);

        $response = $this->postJson(route('register.otp.verify'), [
            'phone' => '9876543210',
            'otp' => '123456',
        ]);

        $response->assertStatus(422);
    }

    public function test_verify_otp_fails_exceeding_max_attempts(): void
    {
        Otp::create([
            'phone' => '9876543210',
            'otp_hash' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(5),
            'attempts' => 5,
        ]);

        $response = $this->postJson(route('register.otp.verify'), [
            'phone' => '9876543210',
            'otp' => '123456',
        ]);

        $response->assertStatus(422);
    }

    public function test_verify_otp_success_with_correct_otp(): void
    {
        Otp::create([
            'phone' => '9876543210',
            'otp_hash' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
        ]);

        $response = $this->postJson(route('register.otp.verify'), [
            'phone' => '9876543210',
            'otp' => '123456',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseMissing('otps', [
            'phone' => '9876543210',
            'verified_at' => null,
        ]);
    }

    public function test_registration_fails_without_otp_verification(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'John Doe',
            'phone' => '9876543210',
            'address' => '123 Main Street',
        ]);

        $response->assertSessionHasErrors(['phone']);
        $this->assertDatabaseMissing('users', [
            'phone' => '9876543210',
        ]);
    }

    public function test_registration_fails_with_invalid_referral_code(): void
    {
        // First verify phone
        Otp::create([
            'phone' => '9876543210',
            'otp_hash' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
        ]);

        $this->postJson(route('register.otp.verify'), [
            'phone' => '9876543210',
            'otp' => '123456',
        ]);

        // Submit registration with non-existent referral code
        $response = $this->post(route('register.submit'), [
            'name' => 'Priya Das',
            'phone' => '9876543210',
            'referral_code' => 'INVALIDREF99',
            'address' => 'Flat 402, Green Valley Apartments, Bhubaneswar',
        ]);

        $response->assertSessionHasErrors(['referral_code']);
        $this->assertDatabaseMissing('users', [
            'phone' => '9876543210',
        ]);
    }

    public function test_registration_succeeds_with_valid_referral_code(): void
    {
        // Create referring user
        $referrer = User::create([
            'name' => 'Referrer User',
            'phone' => '9111111111',
            'email' => 'ref@example.com',
            'address' => 'Bhubaneswar',
            'referral_code' => 'THKREF100',
        ]);

        // Verify phone for new user
        Otp::create([
            'phone' => '9876543210',
            'otp_hash' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
        ]);

        $this->postJson(route('register.otp.verify'), [
            'phone' => '9876543210',
            'otp' => '123456',
        ]);

        // Submit registration
        $response = $this->post(route('register.submit'), [
            'name' => 'Priya Das',
            'phone' => '9876543210',
            'email' => 'priya@example.com',
            'referral_code' => 'THKREF100',
            'address' => 'Flat 402, Green Valley Apartments, Bhubaneswar',
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Priya Das',
            'phone' => '9876543210',
            'email' => 'priya@example.com',
            'status' => User::STATUS_PENDING,
            'referred_by_id' => $referrer->id,
        ]);

        $newUser = User::where('phone', '9876543210')->first();
        $this->assertNotNull($newUser->referral_code);
        $this->assertTrue(Auth::check());
        $this->assertEquals($newUser->id, Auth::id());
    }

    public function test_registration_succeeds_without_optional_fields(): void
    {
        // Verify phone
        Otp::create([
            'phone' => '9876543210',
            'otp_hash' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
        ]);

        $this->postJson(route('register.otp.verify'), [
            'phone' => '9876543210',
            'otp' => '123456',
        ]);

        // Submit without email and without referral code
        $response = $this->post(route('register.submit'), [
            'name' => 'Amit Roy',
            'phone' => '9876543210',
            'address' => 'Zoo Road, Bhubaneswar',
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'name' => 'Amit Roy',
            'phone' => '9876543210',
            'email' => null,
            'status' => User::STATUS_PENDING,
            'referred_by_id' => null,
        ]);

        // Check OTP is marked as used
        $this->assertDatabaseHas('otps', [
            'phone' => '9876543210',
        ]);
        $this->assertNotNull(Otp::where('phone', '9876543210')->first()->used_at);
    }

    public function test_otp_cannot_be_reused_after_registration(): void
    {
        // 1. Verify phone
        Otp::create([
            'phone' => '9876543210',
            'otp_hash' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
        ]);

        $this->postJson(route('register.otp.verify'), [
            'phone' => '9876543210',
            'otp' => '123456',
        ]);

        // 2. Complete registration
        $this->post(route('register.submit'), [
            'name' => 'User One',
            'phone' => '9876543210',
            'address' => 'Bhubaneswar',
        ]);

        // 3. Attempting to use the same verified OTP again should be blocked
        $otpService = app(OtpService::class);
        $this->assertFalse($otpService->isPhoneVerified('9876543210'));
    }

    public function test_user_initials_and_referral_code_helpers(): void
    {
        $user = new User(['name' => 'Priya Das']);
        $this->assertEquals('PD', $user->initials());

        $userSingle = new User(['name' => 'Admin']);
        $this->assertEquals('AD', $userSingle->initials());

        $code = User::generateUniqueReferralCode();
        $this->assertStringStartsWith('THK', $code);
        $this->assertEquals(9, strlen($code));
    }
}
