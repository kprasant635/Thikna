<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->unique()->after('name');
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
            $table->text('address')->nullable()->after('password');
            $table->string('referral_code', 30)->nullable()->unique()->after('address');
            $table->foreignId('referred_by_id')->nullable()->after('referral_code')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referred_by_id']);
            $table->dropColumn([
                'phone',
                'phone_verified_at',
                'address',
                'referral_code',
                'referred_by_id',
            ]);
        });
    }
};
