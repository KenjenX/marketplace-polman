<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    // 1. Update Tabel Users (Menambahkan ID Wilayah)
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'default_province_id')) {
            $table->string('default_province_id')->nullable()->after('default_recipient_name');
        }
        if (!Schema::hasColumn('users', 'default_city_id')) {
            $table->string('default_city_id')->nullable()->after('default_province');
        }
        if (!Schema::hasColumn('users', 'default_district_id')) {
            $table->string('default_district_id')->nullable()->after('default_city');
        }
    });

    // 2. Update Tabel Addresses (ID Wilayah untuk Integrasi Kurir)
    Schema::table('addresses', function (Blueprint $table) {
        if (!Schema::hasColumn('addresses', 'province_id')) {
            $table->string('province_id')->nullable()->after('province');
        }
        if (!Schema::hasColumn('addresses', 'city_id')) {
            $table->string('city_id')->nullable()->after('city');
        }
        if (!Schema::hasColumn('addresses', 'district_id')) {
            $table->string('district_id')->nullable()->after('district');
        }
    });

    // 3. Update Tabel Orders (Fitur Pengiriman & Resi)
    Schema::table('orders', function (Blueprint $table) {
        if (!Schema::hasColumn('orders', 'uuid')) {
            $table->char('uuid', 36)->after('id')->unique();
        }
        if (!Schema::hasColumn('orders', 'shipping_method')) {
            $table->string('shipping_method')->nullable()->after('payment_instruction');
        }
        if (!Schema::hasColumn('orders', 'courier_name')) {
            $table->string('courier_name')->nullable()->after('shipping_method');
        }
        if (!Schema::hasColumn('orders', 'shipping_cost')) {
            $table->decimal('shipping_cost', 12, 2)->default(0)->after('courier_name');
        }
        if (!Schema::hasColumn('orders', 'tracking_number')) {
            $table->string('tracking_number')->nullable()->after('payment_deadline_at');
        }
        if (!Schema::hasColumn('orders', 'courier_code')) {
            $table->string('courier_code')->nullable()->after('tracking_number');
        }
        if (!Schema::hasColumn('orders', 'payment_url')) {
            $table->text('payment_url')->nullable()->after('courier_code');
        }
    });

    // 4. Buat Tabel Notifications (Karena di database Kaa belum ada sama sekali)
    if (!Schema::hasTable('notifications')) {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
