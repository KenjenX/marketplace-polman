<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar pesanan user
     */
    public function index()
    {
        $orders = Order::with([
                'address',
                'paymentReceipt'
            ])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        /**
         * Auto expire order jika melewati deadline
         */
        foreach ($orders as $order) {
            $order->expireIfNeeded();
        }

        /**
         * Refresh collection setelah kemungkinan status berubah
         */
        $orders->load([
            'address',
            'paymentReceipt'
        ]);

        return view('orders.index', compact('orders'));
    }

    /**
     * Detail pesanan
     */
    public function show(Order $order)
    {
        /**
         * Proteksi akses order milik user lain
         */
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        /**
         * Auto expire jika deadline lewat
         */
        $order->expireIfNeeded();

        /**
         * Reload data terbaru + eager loading
         */
        $order->refresh()->load([
            'address',
            'paymentReceipt',

            // Order Items
            'items',

            // Variant Produk
            'items.productVariant',

            // Produk dari Variant
            'items.productVariant.product',
        ]);

        return view('orders.show', compact('order'));
    }

    /**
     * Tracking pesanan
     */
    public function track(Order $order)
    {
        /**
         * Proteksi akses order user lain
         */
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        /**
         * Simulasi demo TA
         */
        if ($order->tracking_number === 'TEST-RESI-001') {

            $trackingData = [
                'summary' => [
                    'courier' => 'JNE Express',
                    'status'  => 'On Process',
                ],

                'history' => [
                    [
                        'date' => '2026-05-07 19:00',
                        'desc' => 'Paket tiba di gudang penyortiran (DC Jakarta)',
                    ],
                    [
                        'date' => '2026-05-07 14:00',
                        'desc' => 'Paket sedang dalam perjalanan ke Jakarta',
                    ],
                    [
                        'date' => '2026-05-07 10:00',
                        'desc' => 'Paket telah diterima oleh kurir (Drop Point Bandung)',
                    ],
                ]
            ];

            return view('orders.track', compact('order', 'trackingData'));
        }

        /**
         * Jika belum ada resi
         */
        if (
            empty($order->tracking_number) ||
            empty($order->courier_code)
        ) {
            return view('orders.track', [
                'order' => $order,
                'trackingData' => null
            ]);
        }

        /**
         * Cache tracking agar API tidak dipanggil terus
         */
        $cacheKey = 'tracking_' . $order->tracking_number;

        $trackingData = Cache::remember(
            $cacheKey,
            now()->addHour(),
            function () use ($order) {

                try {

                    $response = Http::timeout(15)
                        ->get(config('services.binderbyte.endpoint'), [
                            'api_key' => config('services.binderbyte.key'),
                            'courier' => $order->courier_code,
                            'awb'     => $order->tracking_number,
                        ]);

                    if (
                        $response->successful() &&
                        ($response->json()['status'] ?? 0) == 200
                    ) {
                        return $response->json()['data'];
                    }

                    Log::warning('BinderByte gagal response', [
                        'response' => $response->json()
                    ]);

                } catch (\Throwable $e) {

                    Log::error('BinderByte Error: ' . $e->getMessage(), [
                        'order_uuid' => $order->uuid,
                        'tracking_number' => $order->tracking_number
                    ]);
                }

                return null;
            }
        );

        return view('orders.track', compact('order', 'trackingData'));
    }
}