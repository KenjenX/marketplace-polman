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
        Schema::table('addresses', function (Blueprint $table) {

            if (!Schema::hasColumn('addresses', 'province_id')) {
                $table->string('province_id')
                    ->nullable()
                    ->after('province');
            }

            if (!Schema::hasColumn('addresses', 'city_id')) {
                $table->string('city_id')
                    ->nullable()
                    ->after('city');
            }

            if (!Schema::hasColumn('addresses', 'district_id')) {
                $table->string('district_id')
                    ->nullable()
                    ->after('district');
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {

            if (Schema::hasColumn('addresses', 'province_id')) {
                $table->dropColumn('province_id');
            }

            if (Schema::hasColumn('addresses', 'city_id')) {
                $table->dropColumn('city_id');
            }

            if (Schema::hasColumn('addresses', 'district_id')) {
                $table->dropColumn('district_id');
            }

        });
    }
};