<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\OrderNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\Cart;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class CheckoutController extends Controller
{
    /**
     * Menampilkan Halaman Checkout
     */
    public function index()
    {
        $cart = Cart::with(['items.variant.product'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        $userAddress = auth()->user();

        return view('checkout.index', compact('cart', 'paymentMethods', 'userAddress'));
    }

    /**
     * Memproses Order & Integrasi Xendit
     */
    public function store(Request $request)
    {
        // Validasi disesuaikan dengan input form checkout
        $request->validate([
            'recipient_name'    => 'required|max:255',
            'phone'             => 'required|max:30',
            'province'          => 'required',
            'city'              => 'required',
            'city_id'           => 'required',
            'district'          => 'required',
            'postal_code'       => 'nullable|max:20',
            'full_address'      => 'required',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'shipping_method'   => 'required|in:jne,pos,tiki',
            'notes'             => 'nullable|string'
        ]);

        try {
            Log::info('CHECKOUT START');

            $user = auth()->user();

            $cart = Cart::with(['items.variant.product'])
                ->where('user_id', auth()->id())
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                return back()->with('error', 'Keranjang kosong.');
            }

            $subtotal = 0;
            $totalWeight = 0;

            foreach ($cart->items as $item) {
                $variant = ProductVariant::find($item->product_variant_id);

                if (!$variant || $item->quantity > $variant->stock) {
                    return back()->with('error', 'Stok produk tidak mencukupi.');
                }

                $subtotal += $variant->price * $item->quantity;
                $totalWeight += ($variant->weight ?? 1000) * $item->quantity;
            }

            Log::info('CALCULATE SHIPPING');

            $shippingCost = $this->calculateShipping(
                $request->city_id,
                $totalWeight,
                $request->shipping_method
            );

            Log::info('SHIPPING DONE');

            $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

            $order = DB::transaction(function () use (
                $request,
                $cart,
                $paymentMethod,
                $subtotal,
                $shippingCost,
                $user
            ) {
                foreach ($cart->items as $item) {
                    $variant = ProductVariant::find($item->product_variant_id);

                    if (!$variant || $item->quantity > $variant->stock) {
                        throw new \Exception('Stok tidak cukup.');
                    }

                    $variant->decrement('stock', $item->quantity);
                }

                $address = $user->addresses()->create([
                    'recipient_name' => $request->recipient_name,
                    'phone'          => $request->phone,
                    'province'       => $request->province,
                    'city'           => $request->city,
                    'city_id'        => $request->city_id,
                    'district'       => $request->district,
                    'postal_code'    => $request->postal_code,
                    'full_address'   => $request->full_address,
                ]);

                $order = $user->orders()->create([
                    'uuid'                => Str::uuid(),
                    'address_id'          => $address->id,
                    'payment_method_id'   => $paymentMethod->id,
                    'order_code'          => 'ORD-' . now()->format('YmdHis'),
                    'total_price'         => $subtotal + $shippingCost,
                    'payment_method_name' => $paymentMethod->name,
                    'shipping_method'     => strtoupper($request->shipping_method),
                    'shipping_cost'       => $shippingCost,
                    'status'              => 'waiting_payment',
                    'payment_deadline_at' => now()->addHour(),
                ]);

                foreach ($cart->items as $item) {
                    $variant = ProductVariant::find($item->product_variant_id);

                    $order->items()->create([
                        'product_id'         => $variant->product_id,
                        'product_variant_id' => $variant->id,
                        'product_name'       => $variant->product->name,
                        'variant_name'       => $variant->name,
                        'price'              => $variant->price,
                        'quantity'           => $item->quantity,
                        'subtotal'           => $variant->price * $item->quantity,
                    ]);
                }

                $cart->items()->delete();

                return $order;
            });

            Log::info('DB TRANSACTION DONE');

            // --- TRIGGER NOTIFIKASI KE SEMUA ADMIN ---
            // Mengambil user dengan role admin (menggunakan LOWER untuk keamanan)
            $admins = User::whereRaw('LOWER(role) = ?', ['admin'])->get();
            
            if ($admins->isNotEmpty()) {
                $details = [
                    'for_admin'  => true, // PENANDA KHUSUS UNTUK ADMIN
                    'title'      => 'Pesanan Baru Masuk!',
                    'message'    => 'Order ' . $order->order_code . ' menunggu pembayaran.',
                    'order_uuid' => $order->uuid,
                    'url'        => route('admin.orders.show', $order->uuid),
                    'icon'       => 'bi-cart-plus-fill',
                    'type'       => 'primary' 
                ];
                Notification::send($admins, new OrderNotification($details));
            }

            $isXendit = Str::contains(strtolower($paymentMethod->name), 'xendit');
            if ($isXendit) {
                try {
                    Configuration::setXenditKey(config('services.xendit.secret_key'));
                    $apiInstance = new InvoiceApi();
                    $invoiceRequest = new CreateInvoiceRequest([
                        'external_id' => $order->order_code,
                        'amount' => (float) $order->total_price,
                        'description' => 'Pembayaran Order ' . $order->order_code,
                        'success_redirect_url' => route('orders.show', $order->uuid),
                    ]);
                    $response = $apiInstance->createInvoice($invoiceRequest);
                    $order->update(['payment_url' => $response['invoice_url']]);
                    return redirect()->away($response['invoice_url']);
                } catch (\Exception $e) {
                    Log::error('XENDIT ERROR: ' . $e->getMessage());
                    return back()->with('error', 'Gagal membuat invoice Xendit.');
                }
            }

            return redirect()->route('orders.show', $order->uuid)->with('success', 'Pesanan berhasil dibuat.');

        } catch (\Exception $e) {
            Log::error('CHECKOUT ERROR: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    private function calculateShipping($destination, $weight, $courier)
    {
        try {
            $response = Http::timeout(15)->withHeaders(['key' => config('services.rajaongkir.key')])
                ->post(config('services.rajaongkir.url') . 'cost', [
                    'origin' => 153, 'destination' => $destination, 'weight' => $weight, 'courier' => $courier,
                ]);
            if ($response->successful() && isset($response['rajaongkir']['results'][0]['costs'][0]['cost'][0]['value'])) {
                return $response['rajaongkir']['results'][0]['costs'][0]['cost'][0]['value'];
            }
        } catch (\Exception $e) {
            Log::error('RAJAONGKIR ERROR: ' . $e->getMessage());
        }
        return 15000;
    }
}