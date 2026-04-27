<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpirePendingOrders extends Command
{
    protected $signature = 'orders:expire-pending';
    protected $description = 'Expire unpaid orders that passed payment deadline and restore stock';

    public function handle(): int
    {
        $orders = Order::query()
            ->whereIn('status', ['waiting_payment', 'payment_rejected'])
            ->whereNotNull('payment_deadline_at')
            ->where('payment_deadline_at', '<', now())
            ->get();

        $expiredCount = 0;

        foreach ($orders as $order) {
            DB::transaction(function () use ($order, &$expiredCount) {
                $lockedOrder = Order::query()->lockForUpdate()->find($order->id);

                if (!$lockedOrder) {
                    return;
                }

                if (
                    !in_array($lockedOrder->status, ['waiting_payment', 'payment_rejected']) ||
                    !$lockedOrder->payment_deadline_at ||
                    now()->lessThanOrEqualTo($lockedOrder->payment_deadline_at)
                ) {
                    return;
                }

                $lockedOrder->load('items.variant');

                foreach ($lockedOrder->items as $item) {
                    if ($item->variant) {
                        $item->variant->increment('stock', $item->quantity);
                    }
                }

                $lockedOrder->update([
                    'status' => 'expired',
                ]);

                $expiredCount++;
            });
        }

        $this->info("Expired orders processed: {$expiredCount}");

        return self::SUCCESS;
    }
}