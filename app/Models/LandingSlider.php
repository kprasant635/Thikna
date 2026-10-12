<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingSlider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'eyebrow',
        'description',
        'image',
        'button_text',
        'button_url',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    /**
     * Get the full URL for the slider image.
     */
    public function imageUrl(): string
    {
        if (empty($this->image)) {
            return asset('images/hero-female-learner.jpg');
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        if (str_starts_with($this->image, 'images/')) {
            return asset($this->image);
        }

        return asset('storage/'.$this->image);
    }
}
