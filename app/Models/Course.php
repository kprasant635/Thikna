<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'title',
        'slug',
        'description',
        'thumbnail',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(CourseVideo::class)->orderBy('sort_order', 'asc');
    }

    public function activeVideos(): HasMany
    {
        return $this->hasMany(CourseVideo::class)->where('is_active', true)->orderBy('sort_order', 'asc');
    }

    public function courseProgress(): HasMany
    {
        return $this->hasMany(UserCourseProgress::class);
    }
}
