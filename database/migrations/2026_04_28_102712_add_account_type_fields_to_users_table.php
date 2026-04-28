<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('account_type')->default('individual')->after('role');
            $table->string('phone')->nullable()->after('account_type');
            $table->string('company_name')->nullable()->after('phone');
            $table->string('contact_person')->nullable()->after('company_name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'account_type',
                'phone',
                'company_name',
                'contact_person',
            ]);
        });
    }
};