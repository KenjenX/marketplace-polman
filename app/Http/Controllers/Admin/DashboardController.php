<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Data Card Atas
        $categoryCount = Category::count();
        $productCount = Product::count();
        $orderCount = Order::count();
        $waitingValidationCount = Order::whereIn('status', ['waiting_payment', 'pending'])->count();
        
        // Total Pendapatan (Hanya pesanan selesai/dibayar/dikirim)
        $totalRevenue = Order::whereIn('status', ['paid', 'completed', 'shipped'])->sum('total_price');
        
        // Total Pelanggan (Selain Admin)
        $userCount = User::where('role', '!=', 'admin')->count();

        // 2. Data Tabel & List
        $recentOrders = Order::with('address')->latest()->take(5)->get();
        $lowStockVariants = ProductVariant::with('product')->where('stock', '<=', 5)->get();

        // 3. Logika Filter Grafik Penjualan
        $filter = $request->input('filter', 'bulanan'); // Default bulanan
        $chartLabels = [];
        $chartData = [];

        if ($filter == 'harian') {
            // 7 Hari Terakhir
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $chartLabels[] = $date->format('d M');
                $chartData[] = Order::whereDate('created_at', $date->format('Y-m-d'))
                    ->whereIn('status', ['paid', 'completed', 'shipped'])
                    ->sum('total_price');
            }
        } elseif ($filter == 'mingguan') {
            // 4 Minggu Terakhir
            for ($i = 3; $i >= 0; $i--) {
                $start = Carbon::now()->startOfWeek()->subWeeks($i);
                $end = Carbon::now()->endOfWeek()->subWeeks($i);
                $chartLabels[] = 'Mg ' . $start->weekOfMonth . ' ' . $start->format('M');
                $chartData[] = Order::whereBetween('created_at', [$start, $end])
                    ->whereIn('status', ['paid', 'completed', 'shipped'])
                    ->sum('total_price');
            }
        } elseif ($filter == 'bulanan') {
            // 6 Bulan Terakhir
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $chartLabels[] = $date->format('M Y');
                $chartData[] = Order::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->whereIn('status', ['paid', 'completed', 'shipped'])
                    ->sum('total_price');
            }
        } elseif ($filter == 'tahunan') {
            // 3 Tahun Terakhir
            for ($i = 2; $i >= 0; $i--) {
                $year = Carbon::now()->subYears($i)->year;
                $chartLabels[] = (string)$year;
                $chartData[] = Order::whereYear('created_at', $year)
                    ->whereIn('status', ['paid', 'completed', 'shipped'])
                    ->sum('total_price');
            }
        }

        return view('admin.dashboard', compact(
            'categoryCount', 'productCount', 'orderCount', 'waitingValidationCount', 
            'totalRevenue', 'userCount', 'recentOrders', 'lowStockVariants', 
            'chartLabels', 'chartData', 'filter'
        ));
    }
}