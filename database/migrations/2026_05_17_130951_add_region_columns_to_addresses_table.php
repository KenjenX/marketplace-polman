<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {

            if (!Schema::hasColumn('addresses', 'city')) {
                $table->string('city')->nullable()->after('province');
            }

            if (!Schema::hasColumn('addresses', 'district')) {
                $table->string('district')->nullable()->after('city');
            }

            if (!Schema::hasColumn('addresses', 'city_id')) {
                $table->string('city_id')->nullable()->after('city');
            }
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {

            $columns = [];

            if (Schema::hasColumn('addresses', 'city')) {
                $columns[] = 'city';
            }

            if (Schema::hasColumn('addresses', 'district')) {
                $columns[] = 'district';
            }

            if (Schema::hasColumn('addresses', 'city_id')) {
                $columns[] = 'city_id';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};