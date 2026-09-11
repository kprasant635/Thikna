<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('icon', 50)->default('📦');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('amount')->default(200);
            $table->string('status', 20)->default('pending'); // pending, active, failed, cancelled
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('subscription_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_reference', 64)->unique();
            $table->string('gateway_transaction_id', 64)->nullable()->unique();
            $table->unsignedInteger('amount')->default(200);
            $table->string('status', 20)->default('pending'); // pending, success, failed, cancelled
            $table->timestamp('paid_at')->nullable();
            $table->json('gateway_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_payments');
        Schema::dropIfExists('subscription_product');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('products');
    }
};
