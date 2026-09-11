<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('yellow_pages', function (Blueprint $table) { $table->id(); $table->string('name'); $table->string('category'); $table->string('location'); $table->text('description'); $table->text('eligibility')->nullable(); $table->json('required_documents')->nullable(); $table->unsignedInteger('application_fee'); $table->boolean('is_active')->default(true); $table->timestamps(); });
        Schema::create('yellow_page_applications', function (Blueprint $table) { $table->id(); $table->foreignId('yellow_page_id')->constrained()->cascadeOnDelete(); $table->uuid('owner_key')->index(); $table->string('reference')->unique(); $table->string('applicant_name'); $table->string('mobile', 20); $table->string('email'); $table->text('address'); $table->json('documents')->nullable(); $table->unsignedInteger('amount'); $table->string('status')->default('payment_pending'); $table->timestamps(); });
        Schema::create('payments', function (Blueprint $table) { $table->id(); $table->foreignId('yellow_page_application_id')->constrained()->cascadeOnDelete(); $table->string('order_reference')->unique(); $table->string('gateway_transaction_id')->nullable()->unique(); $table->unsignedInteger('amount'); $table->string('status')->default('pending'); $table->timestamp('paid_at')->nullable(); $table->json('gateway_payload')->nullable(); $table->timestamps(); });
        Schema::create('receipts', function (Blueprint $table) { $table->id(); $table->foreignId('yellow_page_application_id')->unique()->constrained()->cascadeOnDelete(); $table->string('reference')->unique(); $table->uuid('verification_token')->unique(); $table->timestamps(); });
    }
    public function down(): void { Schema::dropIfExists('receipts'); Schema::dropIfExists('payments'); Schema::dropIfExists('yellow_page_applications'); Schema::dropIfExists('yellow_pages'); }
};
