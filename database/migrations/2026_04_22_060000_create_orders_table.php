<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('address_id')->constrained()->cascadeOnDelete();
            $table->string('order_code')->unique();
            $table->decimal('total_price', 12, 2)->default(0);
            $table->string('payment_method')->default('bank_transfer');
            $table->string('status')->default('waiting_payment');
            $table->text('notes')->nullable();

            // Tambahkan kolom pelengkap agar tidak error di Model/Controller
            $table->string('payment_method_name')->nullable();
            $table->string('payment_bank_name')->nullable();
            $table->string('payment_account_number')->nullable();
            $table->string('payment_account_name')->nullable();
            $table->text('payment_instruction')->nullable();
            $table->string('shipping_method')->nullable();
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->timestamp('payment_deadline_at')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('courier_code')->nullable();
            $table->text('payment_url')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};