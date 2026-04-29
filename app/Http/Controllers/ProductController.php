<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function home()
    {
        $categories = Category::withCount(['products' => function ($query) {
            $query->where('status', 'active');
        }])
        ->orderBy('name')
        ->get();

        $latestProducts = Product::with(['category', 'variants'])
            ->where('status', 'active')
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('categories', 'latestProducts'));
    }

    public function index(Request $request)
    {
        // 1. Mulai Query Produk (Sekali saja)
        $query = Product::with(['category', 'variants'])
            ->where('status', 'active');

        // 2. Filter Kategori (Mendukung ID atau Slug)
        if ($request->has('category') && $request->category != '') {
            $categoryFilter = $request->category;

            $query->whereHas('category', function ($q) use ($categoryFilter) {
                if (is_numeric($categoryFilter)) {
                    $q->where('id', $categoryFilter);
                } else {
                    $q->where('slug', $categoryFilter);
                }
            });
        }

        // 3. Filter Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // 4. Logika Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'az':
                    $query->orderBy('name', 'asc');
                    break;
                case 'za':
                    $query->orderBy('name', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'price_low':
                    $query->withMin('variants', 'price')->orderBy('variants_min_price', 'asc');
                    break;
                case 'price_high':
                    $query->withMin('variants', 'price')->orderBy('variants_min_price', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        // 5. Eksekusi Pagination
        $products = $query->paginate(20)->withQueryString();

        // 6. Ambil Data Pendukung untuk Sidebar/UI
        $categories = Category::withCount(['products' => function ($query) {
            $query->where('status', 'active');
        }])->orderBy('name')->get();

        $sidebarProducts = Product::where('status', 'active')->latest()->take(4)->get();
        
        // Cari category yang terpilih agar di UI bisa muncul nama kategorinya
        $selectedCategory = null;
        if ($request->category) {
            $selectedCategory = Category::where('id', $request->category)
                                        ->orWhere('slug', $request->category)
                                        ->first();
        }

        return view('products.index', compact(
            'products', 
            'categories', 
            'sidebarProducts', 
            'selectedCategory'
        ));
    }

    public function show(Product $product)
    {
        // Memuat relasi category dan varian yang aktif saja
        $product->load(['category', 'variants' => function ($query) {
            $query->where('status', 'active');
        }]);

        // Jika produk utama tidak aktif, kasih 404
        if ($product->status !== 'active') {
            abort(404);
        }

        return view('products.show', compact('product'));
    }
}