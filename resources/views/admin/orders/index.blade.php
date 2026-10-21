@extends('layouts.admin')

@section('title', 'Pesanan')

@section('content')
    <div class="admin-head">
        <div><div class="eyebrow">{{ $counts->sum() }} pesanan</div><h1>Daftar pesanan</h1><p>Ubah status langsung dari daftar. Pesanan baru tampil paling atas.</p></div>
    </div>

    <div class="toolbar">
        <div class="tabs" role="tablist">
            <a class="tab" role="tab" aria-selected="{{ $status ? 'false' : 'true' }}" href="{{ route('admin.orders.index', array_filter(['q' => $search])) }}">Semua · {{ $counts->sum() }}</a>
            @foreach (\App\Models\Order::STATUSES as $key => $label)
                <a class="tab" role="tab" aria-selected="{{ $status === $key ? 'true' : 'false' }}" href="{{ route('admin.orders.index', array_filter(['status' => $key, 'q' => $search])) }}">{{ $label }} · {{ $counts[$key] ?? 0 }}</a>
            @endforeach
        </div>
        <form class="search" method="GET" action="{{ route('admin.orders.index') }}" role="search">
            @if ($status)<input type="hidden" name="status" value="{{ $status }}">@endif
            <x-icon name="search" />
            <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama / no. order…" aria-label="Cari pesanan">
        </form>
    </div>

    <div class="panel table-wrap">
        <table class="tbl">
            <thead>
                <tr><th>No. order</th><th>Waktu</th><th>Nama</th><th>Diambil</th><th>Item</th><th>Total</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td class="num"><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td>
                        <td class="num">{{ $order->created_at->format('d/m H:i') }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->pickup_time }}</td>
                        <td class="num">{{ $order->items_count }}</td>
                        <td class="num">{{ rupiah($order->total) }}</td>
                        <td><span class="badge {{ $order->status }}">{{ $order->status_label }}</span></td>
                        <td>@include('admin.orders._status-form')</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="empty">Belum ada pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>

        @if ($orders->hasPages())
            <div class="pager">
                @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                    <a href="{{ $url }}" @class(['on' => $page === $orders->currentPage()])>{{ $page }}</a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
