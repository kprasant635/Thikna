<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'id' => 1,
                'name' => 'Product (Education / Training)',
                'category' => 'Education & Skills',
                'icon' => '🎓',
            ],
            [
                'id' => 2,
                'name' => 'Yoga (Exercise)',
                'category' => 'Health & Fitness',
                'icon' => '🧘',
            ],
            [
                'id' => 3,
                'name' => 'Zumba (Exercise)',
                'category' => 'Health & Fitness',
                'icon' => '💃',
            ],
            [
                'id' => 4,
                'name' => 'Fashion (Training)',
                'category' => 'Lifestyle & Design',
                'icon' => '👗',
            ],
            [
                'id' => 5,
                'name' => 'Cultivation',
                'category' => 'Agriculture & Gardening',
                'icon' => '🌾',
            ],
            [
                'id' => 6,
                'name' => 'Podcast',
                'category' => 'Media & Audio',
                'icon' => '🎙️',
            ],
            [
                'id' => 7,
                'name' => 'Cook (Home Delivery)',
                'category' => 'Food & Dining',
                'icon' => '🍲',
            ],
            [
                'id' => 8,
                'name' => 'Cartoon (Kids)',
                'category' => 'Kids & Animation',
                'icon' => '🎨',
            ],
            [
                'id' => 9,
                'name' => 'Health',
                'category' => 'Wellness & Medical',
                'icon' => '🏥',
            ],
            [
                'id' => 10,
                'name' => 'Education',
                'category' => 'Academic Learning',
                'icon' => '📚',
            ],
            [
                'id' => 11,
                'name' => 'Economic',
                'category' => 'Business & Finance',
                'icon' => '📈',
            ],
            [
                'id' => 12,
                'name' => 'Classification',
                'category' => 'Directory Services',
                'icon' => '🗂️',
            ],
            [
                'id' => 13,
                'name' => 'Devotion',
                'category' => 'Spiritual & Culture',
                'icon' => '🙏',
            ],
            [
                'id' => 14,
                'name' => 'Entertainment',
                'category' => 'Shows & Movies',
                'icon' => '🎬',
            ],
            [
                'id' => 15,
                'name' => 'Sports (Local)',
                'category' => 'Athletics & Games',
                'icon' => '⚽',
            ],
            [
                'id' => 16,
                'name' => 'Story (Billion/Story)',
                'category' => 'Literature & Audiobooks',
                'icon' => '📖',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['id' => $product['id']], $product);
        }
    }
}
