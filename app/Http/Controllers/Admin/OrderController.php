<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'paymentReceipt'])
            ->latest()
            ->get();

        foreach ($orders as $order) {
            $order->expireIfNeeded();
        }

        $orders = Order::with(['user', 'paymentReceipt'])
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->expireIfNeeded();
        $order->refresh()->load(['user', 'address', 'items', 'paymentReceipt']);

        return view('admin.orders.show', compact('order'));
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $order->expireIfNeeded();
        $order->refresh();

        $request->validate([
            'action' => 'required|in:accept,reject',
            'admin_note' => 'nullable',
        ]);

        if (!$order->paymentReceipt) {
            return back()->with('error', 'Bukti pembayaran belum ada.');
        }

        if ($order->status !== 'waiting_receipt_validation') {
            return back()->with('error', 'Order ini tidak dalam status menunggu validasi.');
        }

        if ($request->action === 'accept') {
            $order->paymentReceipt->update([
                'validation_status' => 'accepted',
                'admin_note' => $request->admin_note,
            ]);

            $order->update([
                'status' => 'processing',
            ]);

            return back()->with('success', 'Pembayaran berhasil diterima.');
        }

        if ($request->action === 'reject') {
            $order->paymentReceipt->update([
                'validation_status' => 'rejected',
                'admin_note' => $request->admin_note,
            ]);

            $order->update([
                'status' => 'payment_rejected',
            ]);

            return back()->with('success', 'Pembayaran berhasil ditolak.');
        }

        return back()->with('error', 'Aksi tidak valid.');
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $order->expireIfNeeded();
        $order->refresh();

        $request->validate([
            'status' => 'required|in:completed,cancelled',
        ]);

        if ($request->status === 'completed') {
            if ($order->status !== 'processing') {
                return back()->with('error', 'Order hanya bisa diselesaikan jika statusnya processing.');
            }

            $order->update([
                'status' => 'completed',
            ]);

            return back()->with('success', 'Order berhasil diselesaikan.');
        }

        if ($request->status === 'cancelled') {
            if (!in_array($order->status, ['waiting_payment', 'payment_rejected', 'waiting_receipt_validation', 'processing'])) {
                return back()->with('error', 'Order ini tidak bisa dibatalkan.');
            }

            DB::transaction(function () use ($order) {
                $lockedOrder = Order::query()->lockForUpdate()->find($order->id);

                if (!$lockedOrder) {
                    return;
                }

                if (!in_array($lockedOrder->status, ['waiting_payment', 'payment_rejected', 'waiting_receipt_validation', 'processing'])) {
                    return;
                }

                $lockedOrder->load('items.variant');
                $lockedOrder->restoreReservedStock();

                $lockedOrder->update([
                    'status' => 'cancelled',
                ]);
            });

            return back()->with('success', 'Order berhasil dibatalkan dan stok telah dikembalikan.');
        }

        return back()->with('error', 'Status tidak valid.');
    }
}