<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Category, Product, Order};
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function getNotifications()
    {
        // Menggunakan whereIn untuk mencari banyak status sekaligus
        $unreadCount = Order::whereIn('status', ['waiting-validation', 'processing'])->count();

        $latestOrders = Order::with('user')
                            ->whereIn('status', ['waiting-validation', 'processing'])
                            ->latest()
                            ->take(5)
                            ->get()
                            ->map(function($order) {
                                return [
                                    'uuid'     => $order->uuid,
                                    'customer' => $order->user->name ?? 'Pembeli',
                                    'time'     => $order->created_at->diffForHumans(),
                                    'code'     => $order->order_code,
                                    'status'   => $order->status
                                ];
                            });

        return response()->json([
            'count' => $unreadCount,
            'orders' => $latestOrders
        ]);
    }
    public function index(Request $request)
    {
        // 1. Statistik Dasar
        $data['categoryCount'] = Category::count();
        $data['productCount'] = Product::count();
        $data['orderCount'] = Order::count();
        $data['waitingValidationCount'] = Order::where('status', 'waiting-validation')->count();

        // 2. Mengambil 5 Order Terbaru dengan relasi User
        // Memastikan model Order memiliki method relationship 'user'
        $data['recentOrders'] = Order::with('user')->latest()->take(5)->get();

        // 3. Logika Filter Grafik
        $filter = $request->get('filter', 'month');
        $data['currentFilter'] = $filter;

        $query = Order::where('status', 'completed');

        if ($filter == 'week') {
            $sales = $query->where('created_at', '>=', now()->subDays(7))
                ->selectRaw('DATE(created_at) as date, SUM(total_price) as total')
                ->groupBy('date')->orderBy('date')->pluck('total', 'date')->all();
            
            $data['labels'] = array_keys($sales);
            $data['chartData'] = array_values($sales);
        } elseif ($filter == 'year') {
            $sales = $query->selectRaw('YEAR(created_at) as year, SUM(total_price) as total')
                ->groupBy('year')->orderBy('year')->pluck('total', 'year')->all();
            
            $data['labels'] = array_keys($sales);
            $data['chartData'] = array_values($sales);
        } else {
            // Default: Bulanan (Tahun Berjalan)
            $sales = $query->whereYear('created_at', date('Y'))
                ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
                ->groupBy('month')->pluck('total', 'month')->all();
            
            $data['labels'] = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            $data['chartData'] = array_values(array_replace(array_fill(1, 12, 0), $sales));
        }

        return view('admin.dashboard', $data);
    }

    public function exportCSV()
    {
        $fileName = 'Laporan_Penjualan_Polman_' . date('Y-m-d') . '.csv';
        $orders = Order::with('user')->where('status', 'completed')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID Pesanan', 'Nama Pelanggan', 'Total Harga', 'Tanggal'];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->uuid,
                    $order->user->name ?? 'Guest',
                    $order->total_price,
                    $order->created_at->format('Y-m-d H:i')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}