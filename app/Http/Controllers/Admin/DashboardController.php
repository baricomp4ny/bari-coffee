<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = Carbon::today();

        $todayOrders = Order::whereDate('created_at', $today);
        $stats = [
            'orders_today' => (clone $todayOrders)->count(),
            'revenue_today' => (int) (clone $todayOrders)->sum('total'),
            'waiting' => Order::where('status', 'baru')->count(),
            'processing' => Order::where('status', 'diproses')->count(),
            'menus_available' => Menu::where('is_available', true)->count(),
            'menus_total' => Menu::count(),
        ];

        // Pendapatan 7 hari terakhir (termasuk hari ini), hari tanpa pesanan tetap tampil sebagai 0
        $start = $today->copy()->subDays(6);
        $daily = Order::where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as orders, SUM(total) as revenue')
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        $week = collect(range(0, 6))->map(function ($i) use ($start, $daily, $today) {
            $date = $start->copy()->addDays($i);
            $row = $daily->get($date->toDateString());

            return [
                'label' => $date->isSameDay($today) ? 'Hari ini' : $date->translatedFormat('D d/m'),
                'is_today' => $date->isSameDay($today),
                'orders' => (int) ($row->orders ?? 0),
                'revenue' => (int) ($row->revenue ?? 0),
            ];
        });

        // Menu terlaris 30 hari terakhir
        $topMenus = OrderItem::where('created_at', '>=', $today->copy()->subDays(29))
            ->select('menu_name', DB::raw('SUM(quantity) as qty'), DB::raw('SUM(subtotal) as revenue'))
            ->groupBy('menu_name')
            ->orderByDesc('qty')
            ->limit(5)
            ->get();

        $recentOrders = Order::withCount('items')->latest()->limit(6)->get();

        return view('admin.dashboard', compact('stats', 'week', 'topMenus', 'recentOrders'));
    }
}
