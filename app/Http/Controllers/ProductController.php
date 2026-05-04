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
        // 1. Mulai Query Produk
        $query = Product::with(['category', 'variants'])
            ->where('status', 'active');

        // 2. Filter Kategori
        if ($request->has('categories') && is_array($request->categories)) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->whereIn('slug', $request->categories)
                  ->orWhereIn('id', $request->categories);
            });
        } elseif ($request->has('category') && $request->category != '') {
            $categoryFilter = $request->category;
            $query->whereHas('category', function ($q) use ($categoryFilter) {
                if (is_numeric($categoryFilter)) {
                    $q->where('id', $categoryFilter);
                } else {
                    $q->where('slug', $categoryFilter);
                }
            });
        }

        // 3. Filter Range Harga
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->whereHas('variants', function ($q) use ($request) {
                if ($request->filled('min_price')) {
                    $q->where('price', '>=', $request->min_price);
                }
                if ($request->filled('max_price')) {
                    $q->where('price', '<=', $request->max_price);
                }
            });
        }

        // 4. Filter Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // 5. Logika Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'az': $query->orderBy('name', 'asc'); break;
                case 'za': $query->orderBy('name', 'desc'); break;
                case 'newest': $query->orderBy('created_at', 'desc'); break;
                case 'price_low': $query->withMin('variants', 'price')->orderBy('variants_min_price', 'asc'); break;
                case 'price_high': $query->withMin('variants', 'price')->orderBy('variants_min_price', 'desc'); break;
                default: $query->latest(); break;
            }
        } else {
            $query->latest();
        }

        // 6. Eksekusi Pagination
        $products = $query->paginate(9)->withQueryString();

        // 7. Data Pendukung UI Sidebar
        $categories = Category::withCount(['products' => function ($query) {
            $query->where('status', 'active');
        }])->orderBy('name')->get();

        $selectedCategory = null;
        if ($request->category && !is_array($request->category)) {
            $selectedCategory = Category::where('id', $request->category)
                                        ->orWhere('slug', $request->category)
                                        ->first();
        }

        // 8. Cari Harga Termurah dan Termahal (Dikembalikan jadi Dinamis)
        $activeProducts = Product::where('status', 'active')
            ->withMin('variants', 'price')
            ->withMax('variants', 'price')
            ->get();

        $globalMinPrice = $activeProducts->min('variants_min_price') ?? 0;
        $globalMaxPrice = $activeProducts->max('variants_max_price') ?? 1000000;

        // 9. Hitung Total Keseluruhan
        $totalAllProducts = Product::where('status', 'active')->count();

        return view('products.index', compact(
            'products', 
            'categories', 
            'selectedCategory',
            'globalMinPrice',
            'globalMaxPrice',
            'totalAllProducts'
        ));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'variants' => function ($query) {
            $query->where('status', 'active');
        }]);

        if ($product->status !== 'active') {
            abort(404);
        }

        return view('products.show', compact('product'));
    }
}