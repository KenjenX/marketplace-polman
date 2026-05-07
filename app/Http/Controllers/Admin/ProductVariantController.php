<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // WAJIB ADA INI

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $variants = $product->variants()->latest()->get();
        return view('admin.variants.index', compact('product', 'variants'));
    }

    public function create(Product $product)
    {
        return view('admin.variants.create', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|max:255',
            'specification' => 'nullable',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Validasi Gambar
        ]);

        $data = $request->all();

        // Logika Simpan Gambar
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('variants', 'public');
        }

        $product->variants()->create($data);

        return redirect()->route('admin.products.variants.index', $product->id)
            ->with('success', 'Variasi dan Gambar berhasil ditambahkan');
    }

    public function edit(ProductVariant $variant)
    {
        $product = $variant->product;
        return view('admin.variants.edit', compact('product', 'variant'));
    }

    public function update(Request $request, ProductVariant $variant)
    {
        $request->validate([
            'name' => 'required|max:255',
            'specification' => 'nullable',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Validasi Gambar
        ]);

        $data = $request->all();

        // Logika Update Gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($variant->image) {
                Storage::disk('public')->delete($variant->image);
            }
            // Simpan gambar baru
            $data['image'] = $request->file('image')->store('variants', 'public');
        }

        $variant->update($data);

        return redirect()->route('admin.products.variants.index', $variant->product_id)
            ->with('success', 'Variasi dan Gambar berhasil diupdate');
    }

    public function destroy(ProductVariant $variant)
    {
        $productId = $variant->product_id;

        // Hapus file gambar dari storage saat data dihapus
        if ($variant->image) {
            Storage::disk('public')->delete($variant->image);
        }

        $variant->delete();

        return redirect()->route('admin.products.variants.index', $productId)
            ->with('success', 'Variasi berhasil dihapus');
    }
}