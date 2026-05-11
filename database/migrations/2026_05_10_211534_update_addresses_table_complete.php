<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {

            $table->string('province_id')->nullable()->after('province');

            $table->string('city_id')->nullable()->after('city');

            $table->string('district_id')->nullable()->after('district');
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {

            $table->dropColumn([
                'province_id',
                'city_id',
                'district_id'
            ]);
        });
    }
};