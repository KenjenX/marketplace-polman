<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PaymentReceiptController extends Controller
{
    /**
     * User mengunggah bukti pembayaran
     */
    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $order->expireIfNeeded();
        $order->refresh();

        if ($order->status === 'expired') {
            return redirect()->route('orders.show', $order->uuid)->with('error', 'Batas waktu pembayaran sudah habis.');
        }

        if (!in_array($order->status, ['waiting_payment', 'payment_rejected'])) {
            return back()->with('error', 'Order ini tidak bisa upload bukti pembayaran.');
        }

        $request->validate([
            'receipt_file' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        try {
            $file = $request->file('receipt_file');
            $path = $file->store('payment-receipts', 'public');

            if ($order->paymentReceipt) {
                $oldFile = $order->paymentReceipt->receipt_file;
                if ($oldFile && Storage::disk('public')->exists($oldFile)) {
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

            $order->update(['status' => 'waiting_receipt_validation']);

            // --- TRIGGER NOTIFIKASI KE SEMUA ADMIN ---
            $admins = User::whereRaw('LOWER(role) = ?', ['admin'])->get();
            if ($admins->isNotEmpty()) {
                $details = [
                    'for_admin'  => true, // PENANDA UNTUK ADMIN
                    'title'      => 'Bukti Bayar Diunggah!',
                    'message'    => 'Order ' . $order->order_code . ' telah mengunggah bukti transfer.',
                    'order_uuid' => $order->uuid,
                    'url'        => route('admin.orders.show', $order->uuid),
                    'icon'       => 'bi-file-earmark-check-fill',
                    'type'       => 'warning'
                ];
                Notification::send($admins, new OrderNotification($details));
            }

            return redirect()->route('orders.show', $order->uuid)->with('success', 'Bukti pembayaran berhasil diupload.');

        } catch (\Exception $e) {
            Log::error('Upload Payment Receipt Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal upload bukti pembayaran.');
        }
    }

    /**
     * Admin menyetujui pembayaran (Contoh penempatan notifikasi untuk User)
     * Mas bisa sesuaikan dengan nama method verifikasi yang Mas punya
     */
    public function approvePayment(Order $order) 
    {
        // ... logika update status order ke 'paid' atau 'processing' ...
        
        // --- TRIGGER NOTIFIKASI KE USER ---
        $details = [
            'for_admin'  => false, // PENANDA UNTUK USER
            'title'      => 'Pembayaran Diterima!',
            'message'    => 'Pembayaran untuk order ' . $order->order_code . ' telah diverifikasi. Pesanan Anda sedang diproses.',
            'order_uuid' => $order->uuid,
            'url'        => route('orders.show', $order->uuid),
            'icon'       => 'bi-check-circle-fill',
            'type'       => 'success'
        ];
        
        $order->user->notify(new OrderNotification($details));

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }
}