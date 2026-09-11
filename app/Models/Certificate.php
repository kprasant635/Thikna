<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_id',
        'certificate_number',
        'issued_at',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
            'payload' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Generate unique certificate number: THK-CERT-YYYY-XXXXXX
     */
    public static function generateUniqueNumber(): string
    {
        $year = date('Y');
        do {
            $number = 'THK-CERT-' . $year . '-' . strtoupper(Str::random(8));
        } while (static::where('certificate_number', $number)->exists());

        return $number;
    }
}
