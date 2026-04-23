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
        $categories = Category::withCount(['products' => function ($query) {
            $query->where('status', 'active');
        }])
        ->orderBy('name')
        ->get();

        $products = Product::with(['category', 'variants'])
            ->where('status', 'active')
            ->when($request->category, function ($query) use ($request) {
                $query->where('category_id', $request->category);
            })
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->get();

        $sidebarProducts = Product::with(['category', 'variants'])
            ->where('status', 'active')
            ->latest()
            ->take(4)
            ->get();

        $selectedCategory = null;

        if ($request->category) {
            $selectedCategory = Category::find($request->category);
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
        $product->load(['category', 'variants' => function ($query) {
            $query->where('status', 'active');
        }]);

        if ($product->status !== 'active') {
            abort(404);
        }

        return view('products.show', compact('product'));
    }
}