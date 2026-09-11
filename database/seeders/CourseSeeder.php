<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseVideo;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        // Requested YouTube educational video source
        $sampleVideos = [
            'https://www.youtube.com/watch?v=mCeYN9dx1io&list=RDmCeYN9dx1io&start_radio=1',
        ];

        foreach ($products as $product) {
            $course = Course::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'title' => $product->name . ' Masterclass',
                    'slug' => Str::slug($product->name . '-masterclass'),
                    'description' => 'Comprehensive short-video educational program on ' . $product->name . '. Learn key skills and best practices.',
                    'thumbnail' => null,
                    'is_active' => true,
                ]
            );

            // Create 3 to 4 short videos per course
            $videoTopics = [
                ['title' => 'Introduction to ' . $product->name, 'desc' => 'Overview and key fundamentals.', 'duration' => 90],
                ['title' => 'Core Techniques & Best Practices', 'desc' => 'Step-by-step guidance and practical methods.', 'duration' => 120],
                ['title' => 'Advanced Concepts & Case Studies', 'desc' => 'Real-world applications and tips.', 'duration' => 150],
                ['title' => 'Summary & Key Takeaways', 'desc' => 'Review of core lessons and practical action steps.', 'duration' => 90],
            ];

            foreach ($videoTopics as $index => $topic) {
                CourseVideo::updateOrCreate(
                    [
                        'course_id' => $course->id,
                        'sort_order' => $index + 1,
                    ],
                    [
                        'title' => $topic['title'],
                        'description' => $topic['desc'],
                        'video_url' => $sampleVideos[$index % count($sampleVideos)],
                        'duration_seconds' => $topic['duration'],
                        'required_watch_percentage' => 80,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
