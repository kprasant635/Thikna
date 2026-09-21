<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send an SMS message to a phone number.
     */
    public function send(string $phone, string $message): bool
    {
        $driver = config('sms.default', 'log');

        return match ($driver) {
            'log' => $this->sendViaLog($phone, $message),
            'null' => true,
            default => $this->sendViaCustom($driver, $phone, $message),
        };
    }

    /**
     * Send OTP SMS helper.
     */
    public function sendOtp(string $phone, string $otp): bool
    {
        $message = "Your SkopX verification code is: {$otp}. Valid for 5 minutes. Do not share this code with anyone.";

        return $this->send($phone, $message);
    }

    /**
     * Log SMS driver for local development & testing.
     */
    protected function sendViaLog(string $phone, string $message): bool
    {
        Log::channel(config('sms.drivers.log.channel'))->info("[SMS Driver] Message dispatched", [
            'to' => $phone,
            'message' => $message,
            'timestamp' => now()->toIso8601String(),
        ]);

        return true;
    }

    /**
     * Extensible custom driver handler.
     */
    protected function sendViaCustom(string $driver, string $phone, string $message): bool
    {
        Log::warning("[SMS Driver] Custom driver '{$driver}' is not configured. Falling back to log.", [
            'to' => $phone,
        ]);

        return $this->sendViaLog($phone, $message);
    }
}
