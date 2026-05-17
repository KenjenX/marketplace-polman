<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            if (!Schema::hasColumn('orders', 'uuid')) {
                $table->uuid('uuid')->nullable()->after('id');
            }

            if (!Schema::hasColumn('orders', 'address_id')) {
                $table->foreignId('address_id')->nullable();
            }

            if (!Schema::hasColumn('orders', 'payment_method_id')) {
                $table->foreignId('payment_method_id')->nullable();
            }

            if (!Schema::hasColumn('orders', 'payment_method_name')) {
                $table->string('payment_method_name')->nullable();
            }

            if (!Schema::hasColumn('orders', 'shipping_method')) {
                $table->string('shipping_method')->nullable();
            }

            if (!Schema::hasColumn('orders', 'shipping_cost')) {
                $table->integer('shipping_cost')->default(0);
            }

            if (!Schema::hasColumn('orders', 'payment_deadline_at')) {
                $table->timestamp('payment_deadline_at')->nullable();
            }

        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $columns = [];

            if (Schema::hasColumn('orders', 'uuid')) {
                $columns[] = 'uuid';
            }

            if (Schema::hasColumn('orders', 'address_id')) {
                $columns[] = 'address_id';
            }

            if (Schema::hasColumn('orders', 'payment_method_id')) {
                $columns[] = 'payment_method_id';
            }

            if (Schema::hasColumn('orders', 'payment_method_name')) {
                $columns[] = 'payment_method_name';
            }

            if (Schema::hasColumn('orders', 'shipping_method')) {
                $columns[] = 'shipping_method';
            }

            if (Schema::hasColumn('orders', 'shipping_cost')) {
                $columns[] = 'shipping_cost';
            }

            if (Schema::hasColumn('orders', 'payment_deadline_at')) {
                $columns[] = 'payment_deadline_at';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }

        });
    }
};
