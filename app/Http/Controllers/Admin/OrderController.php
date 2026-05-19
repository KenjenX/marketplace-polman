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

                // Kirim notifikasi ke user
                $order->user->notify(new OrderNotification([
                    'title' => 'Pembayaran Diterima',
                    'message' => "Pembayaran untuk pesanan #{$order->id} telah diterima. Pesanan Anda sedang diproses.",
                    'order_uuid' => $order->uuid,
                    'icon' => 'bi-check-circle',
                    'type' => 'success',
                    'url' => route('orders.show', $order->uuid),
                ]));

            } else {

                $order->paymentReceipt()->update([
                    'validation_status' => 'rejected',
                    'admin_note' => $request->admin_note,
                ]);

                $order->update([
                    'status' => 'payment_rejected',
                ]);

                // Kirim notifikasi ke user
                $order->user->notify(new OrderNotification([
                    'title' => 'Pembayaran Ditolak',
                    'message' => "Pembayaran untuk pesanan #{$order->id} ditolak. Silakan periksa kembali bukti pembayaran Anda atau hubungi layanan pelanggan.",
                    'order_uuid' => $order->uuid,
                    'icon' => 'bi-x-circle',
                    'type' => 'danger',
                    'url' => route('orders.show', $order->uuid),
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
     * Update Status Order
     */
    public function updateOrderStatus(Request $request, Order $order)
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

            // Kirim notifikasi jika status diubah menjadi 'completed'
            if ($request->status === 'completed') {
                $order->user->notify(new OrderNotification([
                    'title' => 'Pesanan Selesai',
                    'message' => "Pesanan #{$order->order_code} telah selesai. Terima kasih telah berbelanja!",
                    'order_uuid' => $order->uuid,
                    'icon' => 'bi-check2-all',
                    'type' => 'success',
                    'url' => route('orders.show', $order->uuid),
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
            'jnt_kargo' => 'J&T Kargo',
            default => strtoupper($request->courier_code),
        };

        $order->update([
            'courier_code' => strtolower($request->courier_code),
            'courier_name' => $courierName,
            'tracking_number' => $request->tracking_number,
            'status' => 'shipped',
        ]);

        // Kirim notifikasi ke user
        $order->user->notify(new OrderNotification([
            'title' => 'Pesanan Dikirim',
            'message' => "Pesanan #{$order->order_code} telah dikirim dengan nomor resi {$request->tracking_number}.",
            'order_uuid' => $order->uuid,
            'icon' => 'bi-truck',
            'type' => 'info',
            'url' => route('orders.show', $order->uuid),
        ]));

        return back()->with(
            'success',
            'Nomor resi berhasil disimpan dan pesanan dikirim.'
        );
    }
}