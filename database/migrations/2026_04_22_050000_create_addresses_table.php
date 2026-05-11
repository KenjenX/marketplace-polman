<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('recipient_name');
            $table->string('phone');
            
            // Urutan wilayah
            $table->string('province');
            $table->string('province_id')->nullable();
            
            $table->string('city'); // Tambahkan ini karena sebelumnya belum ada
            $table->string('city_id')->nullable();
            
            $table->string('district');
            $table->string('district_id')->nullable();
            
            $table->string('postal_code')->nullable();
            $table->text('full_address');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};