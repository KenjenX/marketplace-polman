<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'payment_method_id',
        'order_code',
        'total_price',
        'payment_method',
        'payment_method_name',
        'payment_bank_name',
        'payment_account_number',
        'payment_account_name',
        'payment_instruction',
        'status',
        'payment_deadline_at',
        'notes',
    ];

    protected $casts = [
        'payment_deadline_at' => 'datetime',
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentReceipt()
    {
        return $this->hasOne(PaymentReceipt::class);
    }

    public function isWaitingPaymentLike(): bool
    {
        return in_array($this->status, ['waiting_payment', 'payment_rejected']);
    }

    public function isPaymentExpired(): bool
    {
        return $this->payment_deadline_at
            && now()->greaterThan($this->payment_deadline_at)
            && $this->isWaitingPaymentLike();
    }

    public function restoreReservedStock(): void
    {
        $this->loadMissing('items.variant');

        foreach ($this->items as $item) {
            if ($item->variant) {
                $item->variant->increment('stock', $item->quantity);
            }
        }
    }

    public function expireIfNeeded(): bool
    {
        $expired = false;

        DB::transaction(function () use (&$expired) {
            $order = self::query()->lockForUpdate()->find($this->id);

            if (!$order) {
                return;
            }

            if (
                !$order->payment_deadline_at ||
                !now()->greaterThan($order->payment_deadline_at) ||
                !in_array($order->status, ['waiting_payment', 'payment_rejected'])
            ) {
                return;
            }

            $order->load('items.variant');
            $order->restoreReservedStock();

            $order->update([
                'status' => 'expired',
            ]);

            $expired = true;
        });

        if ($expired) {
            $this->refresh();
        }

        return $expired;
    }
}