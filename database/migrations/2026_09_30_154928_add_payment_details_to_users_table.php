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
            $table->string('payment_method', 10)->nullable()->after('profile_photo');
            $table->string('bank_account_holder')->nullable()->after('payment_method');
            $table->string('bank_name')->nullable()->after('bank_account_holder');
            $table->text('bank_account_number')->nullable()->after('bank_name');
            $table->string('bank_ifsc', 11)->nullable()->after('bank_account_number');
            $table->string('upi_id')->nullable()->after('bank_ifsc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'bank_account_holder',
                'bank_name',
                'bank_account_number',
                'bank_ifsc',
                'upi_id',
            ]);
        });
    }
};
