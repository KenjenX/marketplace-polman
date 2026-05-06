<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CancelExpiredOrders extends Command
{
    protected $signature = 'orders:cancel-expired';
    protected $description = 'Membatalkan pesanan yang melewati deadline 2 menit';

    public function handle()
    {
        // Ambil order yang masih waiting_payment dan sudah lewat deadline
        $orders = Order::where('status', 'waiting_payment')
            ->where('payment_deadline_at', '<', now())
            ->with('items.variant')
            ->get();

        foreach ($orders as $order) {
            DB::transaction(function () use ($order) {
                // 1. Kembalikan stok untuk setiap item
                foreach ($order->items as $item) {
                    if ($item->variant) {
                        $item->variant->increment('stock', $item->quantity);
                    }
                }

                // 2. Ubah status jadi cancelled
                $order->update(['status' => 'cancelled']);
            });

            $this->info("Order {$order->order_code} berhasil dibatalkan dan stok dikembalikan.");
        }
    }
}