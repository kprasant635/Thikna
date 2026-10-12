<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('landing_sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('eyebrow')->nullable();
            $table->text('description')->nullable();
            $table->string('image');
            $table->string('button_text')->default('Join Now');
            $table->string('button_url')->default('/register');
            $table->integer('order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        // Seed initial default slides
        DB::table('landing_sliders')->insert([
            [
                'title' => 'Skill Today, Better Tomorrow',
                'eyebrow' => 'SKOP-X LEARNING',
                'description' => 'Build in-demand practical skills, earn certifications, and accelerate your career with SKOP-X.',
                'image' => 'images/hero-female-learner.jpg',
                'button_text' => 'Join Now',
                'button_url' => '/register',
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Learn, Network & Grow Together',
                'eyebrow' => 'CAREER & BUSINESS DEVELOPMENT',
                'description' => 'Explore digital marketing, modern business tools, and connect with a supportive professional community.',
                'image' => 'images/hero-female-learner1.jpg',
                'button_text' => 'Get Started',
                'button_url' => '/register',
                'order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Unlock High-Income Skills',
                'eyebrow' => 'PRACTICAL TRAINING',
                'description' => 'Hands-on courses crafted to help you succeed in today\'s fast-moving business world.',
                'image' => 'images/promo-male-learner.jpg',
                'button_text' => 'Explore Courses',
                'button_url' => '/course-catalog',
                'order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_sliders');
    }
};
