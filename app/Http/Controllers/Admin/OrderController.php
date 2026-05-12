<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\OrderNotification;

class OrderController extends Controller
{
    /**
     * List Order
     */
    public function index()
    {
        $orders = Order::with([
                'user',
                'paymentReceipt'
            ])
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Detail Order
     */
    public function show(Order $order)
    {
        $order->load([
            'user',
            'address',
            'items.productVariant.product',
            'paymentReceipt',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Approve / Reject Bukti Pembayaran
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'action' => 'required|in:accept,reject',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        if (!$order->paymentReceipt) {
            return back()->with('error', 'Bukti pembayaran tidak ditemukan.');
        }

        DB::transaction(function () use ($request, $order) {

            if ($request->action === 'accept') {

                $order->paymentReceipt()->update([
                    'validation_status' => 'approved',
                    'admin_note' => $request->admin_note,
                ]);

                $order->update([
                    'status' => 'processing',
                ]);

                // Kirim notifikasi ke USER
                $order->user->notify(new OrderNotification([
                    'for_admin'  => false, // Milik User
                    'title'      => 'Pembayaran Diterima',
                    'message'    => "Pembayaran untuk pesanan #{$order->order_code} telah diterima. Pesanan Anda sedang diproses.",
                    'order_uuid' => $order->uuid,
                    'icon'       => 'bi-check-circle-fill',
                    'type'       => 'success',
                    'url'        => route('orders.show', $order->uuid),
                ]));

            } else {

                $order->paymentReceipt()->update([
                    'validation_status' => 'rejected',
                    'admin_note' => $request->admin_note,
                ]);

                $order->update([
                    'status' => 'payment_rejected',
                ]);

                // Kirim notifikasi ke USER
                $order->user->notify(new OrderNotification([
                    'for_admin'  => false, // Milik User
                    'title'      => 'Pembayaran Ditolak',
                    'message'    => "Pembayaran untuk pesanan #{$order->order_code} ditolak. Alasan: " . ($request->admin_note ?? 'Bukti tidak valid.'),
                    'order_uuid' => $order->uuid,
                    'icon'       => 'bi-x-circle-fill',
                    'type'       => 'danger',
                    'url'        => route('orders.show', $order->uuid),
                ]));
            }
        });

        return back()->with(
            'success',
            $request->action === 'accept'
                ? 'Pembayaran berhasil diterima.'
                : 'Pembayaran berhasil ditolak.'
        );
    }

    /**
     * Update Status Order (Manual)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:processing,shipped,completed,cancelled'
        ]);

        DB::transaction(function () use ($request, $order) {

            // Jika order dibatalkan -> restore stok
            if ($request->status === 'cancelled') {
                if (!in_array($order->status, ['completed', 'cancelled'])) {
                    $order->restoreReservedStock();
                }
            }

            $order->update([
                'status' => $request->status
            ]);

            // Map detail notif berdasarkan status
            $statusData = match($request->status) {
                'processing' => [
                    'title' => 'Pesanan Diproses',
                    'message' => "Pesanan #{$order->order_code} sedang disiapkan oleh penjual.",
                    'icon' => 'bi-box-seam',
                    'type' => 'info'
                ],
                'completed' => [
                    'title' => 'Pesanan Selesai',
                    'message' => "Pesanan #{$order->order_code} telah selesai. Terima kasih sudah berbelanja!",
                    'icon' => 'bi-bag-check-fill',
                    'type' => 'success'
                ],
                'cancelled' => [
                    'title' => 'Pesanan Dibatalkan',
                    'message' => "Pesanan #{$order->order_code} telah dibatalkan oleh admin.",
                    'icon' => 'bi-x-octagon-fill',
                    'type' => 'danger'
                ],
                default => null
            };

            // Kirim notif ke User jika ada perubahan status yang relevan
            if ($statusData) {
                $order->user->notify(new OrderNotification([
                    'for_admin'  => false,
                    'title'      => $statusData['title'],
                    'message'    => $statusData['message'],
                    'order_uuid' => $order->uuid,
                    'icon'       => $statusData['icon'],
                    'type'       => $statusData['type'],
                    'url'        => route('orders.show', $order->uuid),
                ]));
            }
        });

        return back()->with(
            'success',
            'Status order berhasil diperbarui menjadi '
            . str_replace('_', ' ', $request->status)
        );
    }

    /**
     * Update Resi & Kirim Barang
     */
    public function updateTracking(Request $request, Order $order)
    {
        $request->validate([
            'courier_code' => 'required|string|max:50',
            'tracking_number' => 'required|string|max:255',
        ]);

        $courierName = match(strtolower($request->courier_code)) {
            'jne' => 'JNE',
            'jnt' => 'J&T Express',
            'pos' => 'POS Indonesia',
            'tiki' => 'TIKI',
            default => strtoupper($request->courier_code),
        };

        $order->update([
            'courier_code' => strtolower($request->courier_code),
            'courier_name' => $courierName,
            'tracking_number' => $request->tracking_number,
            'status' => 'shipped',
        ]);

        // Kirim notifikasi ke USER
        $order->user->notify(new OrderNotification([
            'for_admin'  => false, // Milik User
            'title'      => 'Pesanan Dikirim',
            'message'    => "Pesanan #{$order->order_code} telah dikirim via {$courierName} dengan nomor resi {$request->tracking_number}.",
            'order_uuid' => $order->uuid,
            'icon'       => 'bi-truck',
            'type'       => 'info',
            'url'        => route('orders.show', $order->uuid),
        ]));

        return back()->with(
            'success',
            'Nomor resi berhasil disimpan dan pesanan dikirim.'
        );
    }
}