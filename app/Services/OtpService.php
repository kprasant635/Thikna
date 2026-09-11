<?php

namespace App\Services;

use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class OtpService
{
    public function __construct(
        protected SmsService $smsService
    ) {}

    /**
     * Normalize phone number to 10 digits.
     */
    public function normalizePhone(string $phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }

    /**
     * Generate and dispatch an OTP to the given phone number.
     *
     * @throws ValidationException
     */
    public function sendOtp(string $phone, ?string $ip = null, ?string $userAgent = null): array
    {
        $cleanPhone = $this->normalizePhone($phone);
        $cooldownSeconds = (int) config('sms.otp.resend_cooldown_seconds', 60);
        $maxPerHour = (int) config('sms.otp.max_per_hour', 5);
        $expiryMinutes = (int) config('sms.otp.expiry_minutes', 5);

        // Check hourly limit for this phone
        $hourlyCount = Otp::where('phone', $cleanPhone)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        if ($hourlyCount >= $maxPerHour) {
            throw ValidationException::withMessages([
                'phone' => ['Too many OTP requests for this phone number. Please try again later.'],
            ]);
        }

        // Check cooldown from latest OTP
        $latestOtp = Otp::where('phone', $cleanPhone)->latest('id')->first();
        if ($latestOtp && $latestOtp->created_at->diffInSeconds(now()) < $cooldownSeconds) {
            $remaining = $cooldownSeconds - $latestOtp->created_at->diffInSeconds(now());
            throw ValidationException::withMessages([
                'phone' => ["Please wait {$remaining} seconds before requesting a new OTP."],
            ]);
        }

        // Generate 6-digit random code
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Invalidate any existing unused OTPs for this phone
        Otp::where('phone', $cleanPhone)
            ->whereNull('verified_at')
            ->whereNull('used_at')
            ->update(['expires_at' => now()]);

        // Create new OTP record
        $otpRecord = Otp::create([
            'phone' => $cleanPhone,
            'otp_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes($expiryMinutes),
            'attempts' => 0,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ]);

        // Dispatch SMS
        $this->smsService->sendOtp($cleanPhone, $code);

        $response = [
            'success' => true,
            'message' => "OTP sent successfully to +91 {$cleanPhone}.",
            'resend_after' => $cooldownSeconds,
        ];

        // Safe helper for local environment debugging if enabled
        if (config('app.debug') && config('app.env') !== 'production') {
            $response['debug_otp'] = $code;
        }

        return $response;
    }

    /**
     * Verify the OTP provided by the user.
     *
     * @throws ValidationException
     */
    public function verifyOtp(string $phone, string $otp): array
    {
        $cleanPhone = $this->normalizePhone($phone);
        $maxAttempts = (int) config('sms.otp.max_attempts', 5);

        $otpRecord = Otp::where('phone', $cleanPhone)
            ->whereNull('verified_at')
            ->whereNull('used_at')
            ->latest('id')
            ->first();

        if (! $otpRecord) {
            throw ValidationException::withMessages([
                'otp' => ['No active OTP found for this phone number. Please request a new OTP.'],
            ]);
        }

        if ($otpRecord->isExpired()) {
            throw ValidationException::withMessages([
                'otp' => ['This OTP has expired. Please request a new one.'],
            ]);
        }

        if ($otpRecord->hasExceededAttempts($maxAttempts)) {
            throw ValidationException::withMessages([
                'otp' => ['Maximum verification attempts exceeded. Please request a new OTP.'],
            ]);
        }

        // Increment attempt count
        $otpRecord->increment('attempts');

        if (! Hash::check($otp, $otpRecord->otp_hash)) {
            $remaining = $maxAttempts - $otpRecord->attempts;
            $msg = $remaining > 0
                ? "Invalid OTP. You have {$remaining} attempt(s) remaining."
                : 'Invalid OTP. Maximum attempts exceeded. Please request a new OTP.';

            throw ValidationException::withMessages([
                'otp' => [$msg],
            ]);
        }

        // Mark OTP as verified
        $otpRecord->update([
            'verified_at' => now(),
        ]);

        // Set server session verification state
        session()->put('otp_verified_phone', $cleanPhone);
        session()->put('otp_verified_at', now()->timestamp);

        return [
            'success' => true,
            'message' => 'Mobile number verified successfully.',
            'phone' => $cleanPhone,
        ];
    }

    /**
     * Ensure the phone number has been verified before completing registration.
     */
    public function isPhoneVerified(string $phone): bool
    {
        $cleanPhone = $this->normalizePhone($phone);

        // Check session state
        $sessionPhone = session('otp_verified_phone');
        $sessionTime = session('otp_verified_at');

        $sessionValid = ($sessionPhone === $cleanPhone) && ($sessionTime && (time() - $sessionTime) <= 900);

        // Check database record
        $dbValid = Otp::recentVerified($cleanPhone, 15)->exists();

        return $sessionValid && $dbValid;
    }

    /**
     * Consume / invalidate verified OTP upon successful registration.
     */
    public function markAsConsumed(string $phone): void
    {
        $cleanPhone = $this->normalizePhone($phone);

        Otp::where('phone', $cleanPhone)
            ->whereNotNull('verified_at')
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        session()->forget(['otp_verified_phone', 'otp_verified_at']);
    }
}
