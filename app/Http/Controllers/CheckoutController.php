<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\ProductVariant;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::with(['items.variant.product'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return view('checkout.index', compact('cart', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|max:255',
            'phone' => 'required|max:30',
            'province' => 'required|max:255',
            'city' => 'required|max:255',
            'district' => 'required|max:255',
            'postal_code' => 'nullable|max:20',
            'full_address' => 'required',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'notes' => 'nullable',
        ]);

        $cart = Cart::with(['items.variant.product'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        DB::beginTransaction();

        try {
            $lockedVariants = [];
            $grandTotal = 0;

            foreach ($cart->items as $item) {
                $variant = ProductVariant::with('product')
                    ->lockForUpdate()
                    ->find($item->product_variant_id);

                if (!$variant) {
                    DB::rollBack();
                    return redirect()->route('cart.index')->with('error', 'Variasi produk tidak ditemukan.');
                }

                if ($variant->status !== 'active' || $variant->product->status !== 'active') {
                    DB::rollBack();
                    return redirect()->route('cart.index')->with('error', 'Ada produk atau variasi yang tidak aktif.');
                }

                if ($item->quantity > $variant->stock) {
                    DB::rollBack();
                    return redirect()->route('cart.index')->with('error', 'Ada jumlah produk yang melebihi stok.');
                }

                $lockedVariants[$variant->id] = $variant;
                $grandTotal += $variant->price * $item->quantity;
            }

            $paymentMethod = PaymentMethod::where('id', $request->payment_method_id)
                ->where('is_active', true)
                ->first();

            if (!$paymentMethod) {
                DB::rollBack();
                return redirect()->route('checkout.index')->with('error', 'Metode pembayaran tidak valid atau tidak aktif.');
            }

            $address = auth()->user()->addresses()->create([
                'recipient_name' => $request->recipient_name,
                'phone' => $request->phone,
                'province' => $request->province,
                'city' => $request->city,
                'district' => $request->district,
                'postal_code' => $request->postal_code,
                'full_address' => $request->full_address,
            ]);

            $order = auth()->user()->orders()->create([
                'address_id' => $address->id,
                'payment_method_id' => $paymentMethod->id,
                'order_code' => 'ORD-' . now()->format('YmdHis') . '-' . rand(100, 999),
                'total_price' => $grandTotal,
                'payment_method' => $paymentMethod->type,
                'payment_method_name' => $paymentMethod->name,
                'payment_bank_name' => $paymentMethod->bank_name,
                'payment_account_number' => $paymentMethod->account_number,
                'payment_account_name' => $paymentMethod->account_name,
                'payment_instruction' => $paymentMethod->instruction,
                'status' => 'waiting_payment',
                'payment_deadline_at' => now()->addHours(24),
                'notes' => $request->notes,
            ]);

            foreach ($cart->items as $item) {
                $variant = $lockedVariants[$item->product_variant_id];
                $subtotal = $variant->price * $item->quantity;

                $variant->decrement('stock', $item->quantity);

                $order->items()->create([
                    'product_id' => $variant->product->id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'variant_name' => $variant->name,
                    'price' => $variant->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $subtotal,
                ]);
            }

            $cart->items()->delete();

            DB::commit();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Checkout berhasil. Silakan lanjut ke pembayaran.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->route('checkout.index')
                ->with('error', 'Checkout gagal. Silakan coba lagi.');
        }
    }
}