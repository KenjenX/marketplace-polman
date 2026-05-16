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
        // Log payload untuk mempermudah debugging
        Log::info('Xendit Callback Inbound:', $request->all());

        try {
            $orderCode = $request->external_id;
            $status = $request->status;

            if (!$orderCode) {
                return response()->json(['message' => 'External ID missing'], 400);
            }

            return DB::transaction(function () use ($orderCode, $status) {
                // Mencari order berdasarkan order_code
                $order = Order::where('order_code', $orderCode)
                             ->lockForUpdate()
                             ->first();

                if (!$order) {
                    Log::warning("Xendit Callback: Order {$orderCode} tidak ditemukan.");
                    return response()->json(['message' => 'Order not found'], 404);
                }

                // Logika: Jika pembayaran lunas (PAID atau SETTLED)
                if ($status === 'PAID' || $status === 'SETTLED') {
                    
                    /**
                     * PERBAIKAN LOGIKA:
                     * Mengizinkan transisi dari 'pending', 'waiting-payment', atau 'waiting-validation'
                     * ke 'processing' karena pembayaran sudah terverifikasi otomatis oleh Xendit.
                     */
                    $allowedStatuses = ['pending', 'waiting-payment', 'waiting_payment', 'waiting-validation', 'payment_rejected'];
                    
                    if (in_array($order->status, $allowedStatuses)) {
                        
                        $order->update([
                            'status' => 'processing', // Langsung ke processing (Warna Biru di Dashboard)
                            'status_bukti' => 'approved', // Set disetujui (Warna Hijau di Dashboard)
                        ]);

                        // Mengirim notifikasi ke sisi user
                        if ($order->user) {
                            $order->user->notify(new OrderNotification([
                                'title' => 'Pembayaran Diterima',
                                'message' => "Pembayaran untuk pesanan #{$order->order_code} telah diterima otomatis. Pesanan Anda kini sedang diproses.",
                                'order_uuid' => $order->uuid,
                                'icon' => 'bi-check-circle',
                                'type' => 'success',
                                'url' => route('orders.show', $order->uuid),
                            ]));
                        }

                        Log::info("Order {$orderCode} BERHASIL dibayar otomatis. Status sekarang: processing.");
                    } else {
                        Log::info("Order {$orderCode} dilewati. Status saat ini sudah: {$order->status}");
                    }
                }
                // Jika invoice kadaluarsa
                elseif ($status === 'EXPIRED') {
                    if ($order->status !== 'expired') {
                        if (method_exists($order, 'restoreReservedStock')) {
                            $order->restoreReservedStock();
                        }
                        $order->update(['status' => 'expired']);
                        Log::info("Order {$orderCode} ditandai kadaluarsa via Xendit.");
                    }
                }

                return response()->json(['message' => 'Callback Success'], 200);
            });

        } catch (\Exception $e) {
            Log::error('Xendit Callback Error: ' . $e->getMessage() . ' Line: ' . $e->getLine());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}