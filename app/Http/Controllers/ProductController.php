<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
public function index(Request $request)
{
    $categories = Category::orderBy('name')->get();

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

    return view('products.index', compact('products', 'categories'));
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