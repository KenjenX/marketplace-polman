<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil kategori untuk slider (maksimal 4 untuk logika tombol panahmu)
        $categories = Category::withCount('products')->get();

        // Mengambil 4 produk terbaru untuk section Produk Terbaru
        $products = Product::with(['category', 'variants'])->latest()->take(4)->get();

        // Kirim data ke view home.blade.php
        return view('home', compact('categories', 'products'));
    }
}