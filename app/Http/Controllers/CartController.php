<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with(['items.variant.product'])
            ->firstOrCreate([
                'user_id' => auth()->id(),
            ]);

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, ProductVariant $variant)
    {
        if ($variant->status !== 'active') {
            return back()->with('error', 'Variasi produk tidak aktif.');
        }

        if ($variant->product->status !== 'active') {
            return back()->with('error', 'Produk tidak aktif.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id(),
        ]);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_variant_id', $variant->id)
            ->first();

        $newQuantity = $request->quantity;

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
        }

        if ($newQuantity > $variant->stock) {
            return back()->with('error', 'Jumlah melebihi stok yang tersedia.');
        }

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $newQuantity,
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_variant_id' => $variant->id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, CartItem $item)
    {
        if ($item->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($request->quantity > $item->variant->stock) {
            return back()->with('error', 'Jumlah melebihi stok yang tersedia.');
        }

        $item->update([
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('cart.index')->with('success', 'Jumlah produk berhasil diupdate.');
    }

    public function destroy(CartItem $item)
    {
        if ($item->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $item->delete();

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}