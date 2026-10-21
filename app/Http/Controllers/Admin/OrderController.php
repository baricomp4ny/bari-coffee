<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $status = is_string($status) && isset(Order::STATUSES[$status]) ? $status : null;

        $search = is_string($request->query('q')) ? trim($request->query('q')) : '';
        $like = '%'.addcslashes($search, '%_\\').'%';

        $orders = Order::withCount('items')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search !== '', fn ($q) => $q->where(fn ($w) => $w->where('customer_name', 'like', $like)->orWhere('order_number', 'like', $like)))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $counts = Order::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        return view('admin.orders.index', compact('orders', 'status', 'counts', 'search'));
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', ['order' => $order->load('items')]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Order::STATUSES))],
        ]);

        $order->update($data);

        return back()->with('status', "Status {$order->order_number} diubah menjadi {$order->status_label}.");
    }
}
