<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\PaymentReceipt;
use App\Notifications\OrderNotification;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
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
        'shipping_method',
        'courier_name',
        'shipping_cost',
        'status',
        'payment_deadline_at',
        'notes',
        'tracking_number',
        'courier_code',
        'payment_url',
    ];

    protected $hidden = ['id'];

    protected $casts = [
        'payment_deadline_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | AUTO UUID
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::creating(function ($order) {
            if (empty($order->uuid)) {
                $order->uuid = (string) Str::uuid();
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ROUTE MODEL BINDING
    |--------------------------------------------------------------------------
    */

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | LOGIC METHODS
    |--------------------------------------------------------------------------
    */

    public function expireIfNeeded(): bool
    {
        $expired = false;

        DB::transaction(function () use (&$expired) {

            $order = self::query()
                ->where('id', $this->id)
                ->lockForUpdate()
                ->first();

            if (
                $order &&
                $order->payment_deadline_at &&
                now()->greaterThan($order->payment_deadline_at) &&
                in_array($order->status, [
                    'waiting_payment',
                    'payment_rejected'
                ])
            ) {

                $order->restoreReservedStock();

                $order->update([
                    'status' => 'expired'
                ]);

                // kirim notifikasi ke user
                $order->user->notify(new OrderNotification([
                    'title' => 'Pesanan Kadaluarsa',
                    'message' => "Pesanan #{$order->order_code} telah kadaluarsa karena melewati batas waktu pembayaran. Silakan buat pesanan baru jika Anda masih berminat dengan produk kami.",
                    'order_uuid' => $order->uuid,
                    'icon' => 'bi-x-circle',
                    'type' => 'danger',
                    'url' => route('orders.show', $order->uuid),
                ]));

                $expired = true;
            }
        });

        if ($expired) {
            $this->refresh();
        }

        return $expired;
    }

    public function restoreReservedStock(): void
    {
        $this->loadMissing('items.productVariant');

        foreach ($this->items as $item) {

            if ($item->productVariant) {

                $item->productVariant->increment(
                    'stock',
                    $item->quantity
                );
            }
        }
    }
}