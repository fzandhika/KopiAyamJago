<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $orders = Order::query()
            ->withCount('items')
            ->when($status && array_key_exists($status, Order::STATUSES), fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'status' => $status,
            'totalOrders' => Order::query()->count(),
        ]);
    }

    public function show(Order $order): View
    {
        $order->load('items');

        return view('admin.orders.show', ['order' => $order]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(Order::STATUSES))],
        ], [
            'status.required' => 'Pilih status pesanan dulu.',
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Status pesanan '.$order->order_code.' jadi "'.$order->statusLabel().'".');
    }
}
