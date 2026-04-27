<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->nullable()->after('address_id')->constrained()->nullOnDelete();
            $table->string('payment_method_name')->nullable()->after('payment_method');
            $table->string('payment_bank_name')->nullable()->after('payment_method_name');
            $table->string('payment_account_number')->nullable()->after('payment_bank_name');
            $table->string('payment_account_name')->nullable()->after('payment_account_number');
            $table->text('payment_instruction')->nullable()->after('payment_account_name');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_method_id');
            $table->dropColumn([
                'payment_method_name',
                'payment_bank_name',
                'payment_account_number',
                'payment_account_name',
                'payment_instruction',
            ]);
        });
    }
};