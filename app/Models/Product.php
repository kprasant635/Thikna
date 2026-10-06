<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'icon',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(
            Subscription::class,
            'subscription_product'
        )->withTimestamps();
    }

    public function course(): HasOne
    {
        return $this->hasOne(Course::class, 'product_id');
    }
}
