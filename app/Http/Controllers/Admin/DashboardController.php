<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'products' => Product::query()->count(),
            'active_products' => Product::query()->where('is_active', true)->count(),
            'pending_orders' => Order::query()->where('status', 'pending')->count(),
            'revenue' => Order::query()->whereIn('status', ['confirmed', 'shipped', 'completed'])->sum('total'),
        ];

        $recentOrders = Order::query()->latest()->take(6)->get();

        $lowStock = ProductVariant::query()
            ->with('product')
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->take(6)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'lowStock' => $lowStock,
        ]);
    }
}
