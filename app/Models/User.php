<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable([
    'name',
    'phone',
    'phone_verified_at',
    'email',
    'password',
    'address',
    'profile_photo',
    'status',
    'learning_status',
    'referral_code',
    'referred_by_id',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE = 'active';

    public const LEARNING_PENDING = 'pending';
    public const LEARNING_IN_PROGRESS = 'in_progress';
    public const LEARNING_COMPLETED = 'completed';
    public const LEARNING_CERTIFIED = 'certified';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isCertified(): bool
    {
        return $this->learning_status === self::LEARNING_CERTIFIED || $this->certificates()->exists();
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function latestCertificate()
    {
        return $this->hasOne(Certificate::class)->latestOfMany();
    }

    public function videoProgress(): HasMany
    {
        return $this->hasMany(UserVideoProgress::class);
    }

    public function courseProgress(): HasMany
    {
        return $this->hasMany(UserCourseProgress::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->where('status', Subscription::STATUS_ACTIVE)->latestOfMany();
    }

    public function latestSubscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    /**
     * The user who referred this user.
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by_id');
    }

    /**
     * Users referred by this user.
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(User::class, 'referred_by_id');
    }

    /**
     * Get the full URL to the user's profile photo.
     */
    public function profilePhotoUrl(): ?string
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }

        return null;
    }

    /**
     * Generate initials from the user's name for avatar display.
     */
    public function initials(): string
    {
        $words = array_filter(explode(' ', trim($this->name ?? '')));
        if (empty($words)) {
            return 'U';
        }

        if (count($words) === 1) {
            return strtoupper(substr($words[0], 0, 2));
        }

        return strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
    }

    /**
     * Generate a unique referral code.
     */
    public static function generateUniqueReferralCode(): string
    {
        do {
            $code = 'THK' . strtoupper(Str::random(6));
        } while (static::where('referral_code', $code)->exists());

        return $code;
    }
}
