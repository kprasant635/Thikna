<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVideoProgress extends Model
{
    use HasFactory;

    protected $table = 'user_video_progress';

    protected $fillable = [
        'user_id',
        'course_id',
        'video_id',
        'watch_time_seconds',
        'percentage_watched',
        'is_completed',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'watch_time_seconds' => 'integer',
            'percentage_watched' => 'float',
            'is_completed' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(CourseVideo::class, 'video_id');
    }
}
