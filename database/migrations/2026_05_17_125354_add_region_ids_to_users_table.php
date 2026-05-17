<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('default_province_id')->nullable()->after('default_province');

            $table->string('default_city_id')->nullable()->after('default_city');

            $table->string('default_district_id')->nullable()->after('default_district');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'default_province_id',
                'default_city_id',
                'default_district_id',
            ]);
        });
    }
};