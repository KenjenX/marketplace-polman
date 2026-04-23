<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

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
        ]);

        $product->variants()->create([
            'name' => $request->name,
            'specification' => $request->specification,
            'price' => $request->price,
            'stock' => $request->stock,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.products.variants.index', $product->id)
            ->with('success', 'Spesifikasi berhasil ditambahkan');
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
        ]);

        $variant->update([
            'name' => $request->name,
            'specification' => $request->specification,
            'price' => $request->price,
            'stock' => $request->stock,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.products.variants.index', $variant->product_id)
            ->with('success', 'Spesifikasi berhasil diupdate');
    }

    public function destroy(ProductVariant $variant)
    {
        $productId = $variant->product_id;

        $variant->delete();

        return redirect()->route('admin.products.variants.index', $productId)
            ->with('success', 'Spesifikasi berhasil dihapus');
    }
}