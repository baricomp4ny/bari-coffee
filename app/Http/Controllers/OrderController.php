<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:60'],
            'ambil' => ['required', Rule::in(Order::PICKUP_TIMES)],
            'items' => ['required', 'array', 'min:1', 'max:30'],
            'items.*' => ['integer', 'min:1', 'max:50'],
        ], [
            'nama.required' => 'Nama pemesan wajib diisi.',
            'items.required' => 'Pesanan masih kosong.',
        ]);

        // Harga selalu diambil dari database, bukan dari browser
        $menus = Menu::whereIn('id', array_keys($data['items']))
            ->where('is_available', true)
            ->get()
            ->keyBy('id');

        if ($menus->count() !== count($data['items'])) {
            throw ValidationException::withMessages([
                'items' => 'Ada menu yang sudah habis atau tidak tersedia. Silakan periksa pesananmu lagi.',
            ]);
        }

        $order = DB::transaction(function () use ($data, $menus) {
            $lines = collect($data['items'])->map(function ($qty, $menuId) use ($menus) {
                $menu = $menus[$menuId];

                return [
                    'menu_id' => $menu->id,
                    'menu_name' => $menu->name,
                    'price' => $menu->price,
                    'quantity' => (int) $qty,
                    'subtotal' => $menu->price * (int) $qty,
                ];
            });

            $order = Order::create([
                'customer_name' => $data['nama'],
                'pickup_time' => $data['ambil'],
                'total' => $lines->sum('subtotal'),
                'status' => 'baru',
            ]);
            $order->items()->createMany($lines->values()->all());
            $order->update(['order_number' => 'BC-'.now()->format('ymd').'-'.str_pad($order->id, 4, '0', STR_PAD_LEFT)]);

            return $order;
        });

        return redirect()->route('orders.sent')->with([
            'order_number' => $order->order_number,
            'wa_url' => $this->whatsappUrl($order->load('items')),
        ]);
    }

    /**
     * Halaman singkat setelah pesanan tersimpan: kosongkan keranjang lalu buka WhatsApp.
     */
    public function sent(): View|RedirectResponse
    {
        if (! session('wa_url')) {
            return redirect()->route('home');
        }

        return view('orders.sent', [
            'orderNumber' => session('order_number'),
            'waUrl' => session('wa_url'),
        ]);
    }

    private function whatsappUrl(Order $order): string
    {
        $message = "Halo Bari Coffee, saya {$order->customer_name} mau pesan:\n"
            ."No. order: {$order->order_number}\n"
            .$order->items->map(fn ($i) => "- {$i->quantity}x {$i->menu_name} (".rupiah($i->subtotal).')')->implode("\n")
            ."\nTotal: ".rupiah($order->total)
            ."\nDiambil: {$order->pickup_time}";

        return 'https://wa.me/'.preg_replace('/\D/', '', config('cafe.whatsapp')).'?text='.rawurlencode($message);
    }
}
