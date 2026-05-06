<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderTrackingController extends Controller
{
    public function track($order_code)
    {
        // 1. Cari order berdasarkan kode
        $order = Order::where('order_code', $order_code)->firstOrFail();

        // 2. Jika resi belum ada, arahkan ke halaman "Sedang Diproses"
        if (!$order->tracking_number) {
            return view('orders.track_empty', compact('order'));
        }

        // 3. LOGIKA UNTUK AKUN STARTER (SIMULASI)
        // Karena Starter tidak bisa akses API Waybill, kita buat data simulasi
        // agar Anda bisa mendesain UI-nya sekarang.
        
        $data = [
            'summary' => [
                'waybill_number' => $order->tracking_number,
                'courier_name' => $order->courier_name ?? 'JNE',
                'status' => 'ON PROCESS',
                'receiver_name' => $order->address->recipient_name,
            ],
            'details' => [
                'origin' => 'JAKARTA',
                'destination' => $order->address->city,
                'shipper' => 'TOKO ANDA',
            ],
            'manifest' => [
                [
                    'manifest_date' => '2026-05-06',
                    'manifest_time' => '10:00',
                    'manifest_description' => 'Paket telah diterima oleh kurir (Pick Up)',
                    'city_name' => 'JAKARTA'
                ],
                [
                    'manifest_date' => '2026-05-06',
                    'manifest_time' => '15:30',
                    'manifest_description' => 'Paket sedang transit di gudang pusat',
                    'city_name' => 'JAKARTA'
                ],
                [
                    'manifest_date' => '2026-05-07',
                    'manifest_time' => '09:00',
                    'manifest_description' => 'Paket dikirim ke kota tujuan',
                    'city_name' => 'BANDUNG'
                ],
            ]
        ];

        return view('orders.track_show', compact('order', 'data'));
    }
}