<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class XenditCallbackController extends Controller
{
    public function handle(Request $request)
    {
        try {
            $callbackData = $request->all();
            
            // Log data agar kita bisa intip di storage/logs/laravel.log jika error lagi
            Log::info('Xendit Callback Data:', $callbackData);

            // Ambil External ID (Order Code)
            $externalId = $callbackData['external_id'] ?? null;

            if (!$externalId) {
                return response()->json(['message' => 'External ID not found'], 400);
            }

            // Cari Order
            $order = Order::where('order_code', $externalId)->first();

            if (!$order) {
                Log::warning('Order tidak ditemukan untuk ID: ' . $externalId);
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Update status ke 'processing'
            // Kita tidak cek $callbackData['status'] == 'PAID' karena untuk VA, 
            // callback ini hanya dikirim saat sudah lunas.
            $order->update([
                'status' => 'processing',
                'paid_at' => now(),
            ]);

            return response()->json(['message' => 'Success'], 200);

        } catch (\Exception $e) {
            Log::error('Callback Error: ' . $e->getMessage());
            return response()->json(['message' => 'Server Error'], 500);
        }
    }
}