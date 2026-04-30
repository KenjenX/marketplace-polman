<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; // <-- WAJIB TAMBAH INI

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'variants'])->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'slug' => 'required|max:255|unique:products,slug',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            // Kalau admin upload gambar, simpan normal
            $imagePath = $request->file('image')->store('products', 'public');
        } else {
            // KALAU KOSONG: Copy FISIK gambar dari assets ke storage
            $sourcePath = public_path('assets/img/foto_tidak_tersedia.png');
            $newFileName = 'products/default_' . time() . '.png'; // Bikin nama unik
            $destinationPath = storage_path('app/public/' . $newFileName);

            if (File::exists($sourcePath)) {
                // Pastikan folder products di storage sudah ada
                if (!File::exists(storage_path('app/public/products'))) {
                    File::makeDirectory(storage_path('app/public/products'), 0755, true);
                }
                
                // Copy file aslinya!
                File::copy($sourcePath, $destinationPath);
                
                // Simpan path hasil copy ke database
                $imagePath = $newFileName; 
            }
        }

        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'image' => $imagePath,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'slug' => 'required|max:255|unique:products,slug,' . $product->id,
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            // Hapus gambar lama (termasuk kalau itu gambar copy-an default)
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // Upload gambar baru
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'image' => $imagePath,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diupdate');
    }

    public function destroy(Product $product)
    {
        // Hapus file fisik (aman dihapus karena ini copy-an unik per produk)
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}