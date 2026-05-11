<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Notifications\OrderNotification;

class XenditCallbackController extends Controller
{
    public function handleInvoice(Request $request)
    {
        // Log payload untuk mempermudah debugging jika terjadi masalah integrasi
        Log::info('Xendit Callback Inbound:', $request->all());

        try {
            $orderCode = $request->external_id;
            $status = $request->status;

            if (!$orderCode) {
                return response()->json(['message' => 'External ID missing'], 400);
            }

            // Gunakan database transaction untuk integritas data dan mencegah race condition
            return DB::transaction(function () use ($orderCode, $status) {
                // Lock row order agar tidak dimodifikasi oleh proses lain selama transaksi berlangsung
                $order = Order::where('order_code', $orderCode)
                             ->lockForUpdate()
                             ->first();

                if (!$order) {
                    Log::warning("Xendit Callback: Order {$orderCode} tidak ditemukan.");
                    return response()->json(['message' => 'Order not found'], 404);
                }

                // Logika: Jika pembayaran lunas (PAID atau SETTLED)
                if ($status === 'PAID' || $status === 'SETTLED') {
                    
                    // Pastikan kita hanya memproses order yang memang sedang menunggu pembayaran
                    // Ini mencegah pengiriman notifikasi berulang
                    if ($order->status === 'waiting_payment' || $order->status === 'payment_rejected') {
                        
                        $order->update([
                            'status' => 'processing',
                            'status_bukti' => 'approved', // Status bukti otomatis disetujui
                        ]);

                        // Kirim notifikasi ke user
                        if ($order->user) {
                            $order->user->notify(new OrderNotification([
                                'title' => 'Pembayaran Diterima',
                                'message' => "Pembayaran untuk pesanan #{$order->order_code} telah diterima secara otomatis. Pesanan Anda sedang diproses.",
                                'order_uuid' => $order->uuid,
                                'icon' => 'bi-check-circle',
                                'type' => 'success',
                                'url' => route('orders.show', $order->uuid),
                            ]));
                        }

                        Log::info("Order {$orderCode} berhasil diverifikasi otomatis via Xendit.");
                    }
                }
                // Logika: Jika invoice kadaluarsa di sistem Xendit
                elseif ($status === 'EXPIRED') {
                    if ($order->status !== 'expired') {
                        $order->restoreReservedStock();
                        $order->update(['status' => 'expired']);
                        Log::info("Order {$orderCode} ditandai kadaluarsa via Xendit.");
                    }
                }

                return response()->json(['message' => 'Callback Success'], 200);
            });

        } catch (\Exception $e) {
            Log::error('Xendit Callback Error: ' . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}