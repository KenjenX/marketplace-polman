<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PaymentReceiptController extends Controller
{
    public function store(Request $request, Order $order)
    {
        // Proteksi user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // Cek expired
        $order->expireIfNeeded();
        $order->refresh();

        // Jika order expired
        if ($order->status === 'expired') {
            return redirect()
                ->route('orders.show', $order->uuid)
                ->with('error', 'Batas waktu pembayaran sudah habis.');
        }

        // Validasi status
        if (!in_array($order->status, [
            'waiting_payment',
            'payment_rejected'
        ])) {
            return back()->with(
                'error',
                'Order ini tidak bisa upload bukti pembayaran.'
            );
        }

        // Validasi file
        $request->validate([
            'receipt_file' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048'
            ],
        ]);

        try {

            // Upload file
            $file = $request->file('receipt_file');

            $path = $file->store(
                'payment-receipts',
                'public'
            );

            // Jika sudah ada receipt sebelumnya
            if ($order->paymentReceipt) {

                $oldFile = $order->paymentReceipt->receipt_file;

                if (
                    $oldFile &&
                    Storage::disk('public')->exists($oldFile)
                ) {
                    Storage::disk('public')->delete($oldFile);
                }

                $order->paymentReceipt()->update([
                    'receipt_file'     => $path,
                    'validation_status'=> 'pending',
                    'admin_note'       => null,
                    'uploaded_at'      => now(),
                ]);

            } else {

                $order->paymentReceipt()->create([
                    'receipt_file'      => $path,
                    'validation_status' => 'pending',
                    'uploaded_at'       => now(),
                ]);
            }

            // Update status order
            $order->update([
                'status' => 'waiting_receipt_validation',
            ]);

            return redirect()
                ->route('orders.show', $order->uuid)
                ->with(
                    'success',
                    'Bukti pembayaran berhasil diupload.'
                );

        } catch (\Exception $e) {

            Log::error(
                'Upload Payment Receipt Error: ' .
                $e->getMessage()
            );

            return back()->with(
                'error',
                'Gagal upload bukti pembayaran.'
            );
        }
    }
}