<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'otp_hash',
        'expires_at',
        'verified_at',
        'used_at',
        'attempts',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'verified_at' => 'datetime',
            'used_at' => 'datetime',
            'attempts' => 'integer',
        ];
    }

    /**
     * Scope query for latest unverified and unexpired OTP for a phone number.
     */
    public function scopeActive(Builder $query, string $phone): Builder
    {
        return $query->where('phone', $phone)
            ->whereNull('verified_at')
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest('id');
    }

    /**
     * Scope query for recently verified OTP for a phone number.
     */
    public function scopeRecentVerified(Builder $query, string $phone, int $withinMinutes = 15): Builder
    {
        return $query->where('phone', $phone)
            ->whereNotNull('verified_at')
            ->whereNull('used_at')
            ->where('verified_at', '>=', now()->subMinutes($withinMinutes))
            ->latest('verified_at');
    }

    /**
     * Check if this OTP record is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if this OTP record has exceeded attempt limits.
     */
    public function hasExceededAttempts(int $maxAttempts = 5): bool
    {
        return $this->attempts >= $maxAttempts;
    }
}
