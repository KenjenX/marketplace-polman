<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['address', 'paymentReceipt'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        foreach ($orders as $order) {
            $order->expireIfNeeded();
        }

        $orders = Order::with(['address', 'paymentReceipt'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->expireIfNeeded();
        $order->refresh()->load(['address', 'items', 'paymentReceipt']);

        return view('orders.show', compact('order'));
    }
}