<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function create(): View|RedirectResponse
    {
        $items = Cart::items();

        if ($items->isEmpty()) {
            return redirect()->route('catalog.index')
                ->with('error', 'Keranjang masih kosong. Pilih biji kopi dulu, ya.');
        }

        return view('checkout.create', [
            'items' => $items,
            'subtotal' => Cart::subtotal(),
        ]);
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $items = Cart::items();

        if ($items->isEmpty()) {
            return redirect()->route('catalog.index')
                ->with('error', 'Keranjang kosong, pesanan nggak bisa diproses.');
        }

        $order = DB::transaction(function () use ($request, $items): Order {
            $order = Order::create([
                'order_code' => Order::generateCode(),
                'customer_name' => $request->string('customer_name')->trim()->value(),
                'phone' => $request->string('phone')->trim()->value(),
                'address' => $request->string('address')->trim()->value(),
                'status' => 'pending',
                'total' => $items->sum('subtotal'),
                'payment_method' => $request->string('payment_method')->value(),
                'notes' => $request->input('notes'),
            ]);

            foreach ($items as $item) {
                /** @var ProductVariant $variant */
                $variant = ProductVariant::query()->lockForUpdate()->find($item['variant']->id);

                if (! $variant || $variant->stock < $item['qty']) {
                    abort(422, 'Stok '.$item['product']->name.' tidak mencukupi. Silakan ubah jumlah pesanan.');
                }

                $variant->decrement('stock', $item['qty']);

                $order->items()->create([
                    'product_variant_id' => $variant->id,
                    'product_name' => $item['product']->name,
                    'weight_gram' => $variant->weight_gram,
                    'grind_type' => $item['grind'],
                    'qty' => $item['qty'],
                    'price' => $variant->price,
                ]);
            }

            return $order;
        });

        Cart::clear();

        return redirect()->route('checkout.success', $order)
            ->with('success', 'Pesanan '.$order->order_code.' sudah dicatat. Tinggal konfirmasi via WhatsApp.');
    }

    public function success(Order $order): View
    {
        $order->load('items');

        $message = "Halo Kopi Ayam Jago, saya mau konfirmasi pesanan {$order->order_code}.\n\n";
        foreach ($order->items as $item) {
            $message .= "- {$item->product_name} {$item->weight_gram} g ({$item->grindLabel()}) x {$item->qty}\n";
        }
        $message .= "\nTotal: {$order->formattedTotal()}\nNama: {$order->customer_name}\nAlamat: {$order->address}";

        return view('checkout.success', [
            'order' => $order,
            'whatsappUrl' => Setting::whatsappLink($message),
        ]);
    }
}
