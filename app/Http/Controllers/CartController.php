<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CartController extends Controller
{
    public function index(): View
    {
        return view('cart.index', [
            'items' => Cart::items(),
            'subtotal' => Cart::subtotal(),
            'grindTypes' => OrderItem::GRIND_TYPES,
        ]);
    }

    public function add(Request $request, ProductVariant $variant): RedirectResponse
    {
        $validated = $request->validate([
            'qty' => ['nullable', 'integer', 'between:1,20'],
            'grind' => ['nullable', Rule::in(array_keys(OrderItem::GRIND_TYPES))],
        ]);

        abort_unless($variant->product?->is_active, 404);

        if ($variant->stock < 1) {
            return back()->with('error', 'Stok varian ini sedang habis. Coba berat atau biji lain dulu, ya.');
        }

        Cart::add($variant, $validated['grind'] ?? 'whole', $validated['qty'] ?? 1);

        return redirect()
            ->route('cart.index')
            ->with('success', $variant->product->name.' '.$variant->label.' masuk keranjang.');
    }

    public function update(Request $request, ProductVariant $variant): RedirectResponse
    {
        $validated = $request->validate([
            'qty' => ['required', 'integer', 'between:1,20'],
            'grind' => ['required', Rule::in(array_keys(OrderItem::GRIND_TYPES))],
            'original_grind' => ['nullable', Rule::in(array_keys(OrderItem::GRIND_TYPES))],
        ]);

        Cart::update(
            $variant,
            $validated['grind'],
            min($validated['qty'], max(1, $variant->stock)),
            $validated['original_grind'] ?? null,
        );

        return back()->with('success', 'Jumlah pesanan diperbarui.');
    }

    public function remove(Request $request, ProductVariant $variant): RedirectResponse
    {
        $grind = (string) $request->query('grind', 'whole');

        Cart::remove(Cart::lineKey($variant, $grind));

        return back()->with('success', 'Satu baris pesanan dihapus dari keranjang.');
    }
}
