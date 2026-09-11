<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'video_url',
        'duration_seconds',
        'required_watch_percentage',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'duration_seconds' => 'integer',
            'required_watch_percentage' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function videoProgress(): HasMany
    {
        return $this->hasMany(UserVideoProgress::class, 'video_id');
    }
}
