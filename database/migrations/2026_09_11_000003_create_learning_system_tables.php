<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('learning_status', 20)->default('pending')->after('status');
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('course_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_url');
            $table->unsignedInteger('duration_seconds')->default(120);
            $table->unsignedTinyInteger('required_watch_percentage')->default(80);
            $table->unsignedInteger('sort_order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('user_video_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('video_id')->constrained('course_videos')->cascadeOnDelete();
            $table->unsignedInteger('watch_time_seconds')->default(0);
            $table->decimal('percentage_watched', 5, 2)->default(0.00);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'video_id']);
        });

        Schema::create('user_course_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'course_id']);
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained()->nullOnDelete();
            $table->string('certificate_number', 64)->unique();
            $table->timestamp('issued_at');
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'subscription_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('user_course_progress');
        Schema::dropIfExists('user_video_progress');
        Schema::dropIfExists('course_videos');
        Schema::dropIfExists('courses');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('learning_status');
        });
    }
};
