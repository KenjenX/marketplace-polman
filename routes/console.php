<?php

use App\Models\Order;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    $orders = Order::whereIn('status', ['waiting_payment', 'payment_rejected'])
        ->where('payment_deadline_at', '<', now())
        ->get();

    foreach ($orders as $order) {
        if ($order->expireIfNeeded()) {
            $this->info("Order {$order->uuid} has been marked as expired.");
        }
    }
})->everyMinute();

Schedule::command('orders:cancel-expired')->everyMinute();
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
