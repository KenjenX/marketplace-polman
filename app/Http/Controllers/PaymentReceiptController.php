<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentReceiptController extends Controller
{
    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($order->status, ['waiting_payment', 'payment_rejected'])) {
            return back()->with('error', 'Order ini tidak bisa upload bukti pembayaran.');
        }

        $request->validate([
            'receipt_file' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $file = $request->file('receipt_file');
        $path = $file->store('payment-receipts', 'public');

        if ($order->paymentReceipt) {
            $order->paymentReceipt()->update([
                'receipt_file' => $path,
                'validation_status' => 'pending',
                'admin_note' => null,
                'uploaded_at' => now(),
            ]);
        } else {
            $order->paymentReceipt()->create([
                'receipt_file' => $path,
                'validation_status' => 'pending',
                'uploaded_at' => now(),
            ]);
        }

        $order->update([
            'status' => 'waiting_receipt_validation',
        ]);

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Bukti pembayaran berhasil diupload.');
    }
}