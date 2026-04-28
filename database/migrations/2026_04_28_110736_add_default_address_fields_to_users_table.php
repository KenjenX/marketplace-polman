<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('default_recipient_name')->nullable()->after('contact_person');
            $table->string('default_province')->nullable()->after('default_recipient_name');
            $table->string('default_city')->nullable()->after('default_province');
            $table->string('default_district')->nullable()->after('default_city');
            $table->string('default_postal_code')->nullable()->after('default_district');
            $table->text('default_full_address')->nullable()->after('default_postal_code');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'default_recipient_name',
                'default_province',
                'default_city',
                'default_district',
                'default_postal_code',
                'default_full_address',
            ]);
        });
    }
};