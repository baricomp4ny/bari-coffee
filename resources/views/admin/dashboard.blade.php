@extends('layouts.admin')

@section('title', 'Dashboard')

@php
    $maxRevenue = max(1, $week->max('revenue'));
    $weekRevenue = $week->sum('revenue');
    $weekOrders = $week->sum('orders');
    $maxQty = max(1, (int) $topMenus->max('qty'));
@endphp

@section('content')
    <div class="admin-head">
        <div>
            <div class="eyebrow">{{ now()->translatedFormat('l, d F Y') }}</div>
            <h1>Halo, {{ \Illuminate\Support\Str::words(auth()->user()->name, 1, '') }}</h1>
            <p>Ringkasan kedai hari ini.</p>
        </div>
        <a class="btn primary" href="{{ route('admin.menus.create') }}"><x-icon name="plus" /> Tambah menu</a>
    </div>

    <section class="stats" aria-label="Ringkasan">
        <a class="stat hot" href="{{ route('admin.orders.index', ['status' => 'baru']) }}">
            <span class="top">Menunggu diproses <span class="ico-wrap"><x-icon name="bell" /></span></span>
            <strong class="num">{{ $stats['waiting'] }}</strong>
            <small>{{ $stats['processing'] }} sedang diproses</small>
        </a>
        <div class="stat">
            <span class="top">Pesanan hari ini <span class="ico-wrap"><x-icon name="receipt" /></span></span>
            <strong class="num">{{ $stats['orders_today'] }}</strong>
            <small>{{ $weekOrders }} dalam 7 hari</small>
        </div>
        <div class="stat">
            <span class="top">Pendapatan hari ini <span class="ico-wrap"><x-icon name="wallet" /></span></span>
            <strong class="num">{{ rupiah($stats['revenue_today']) }}</strong>
            <small>{{ rupiah($weekRevenue) }} dalam 7 hari</small>
        </div>
        <a class="stat" href="{{ route('admin.menus.index') }}">
            <span class="top">Menu tersedia <span class="ico-wrap"><x-icon name="cup" /></span></span>
            <strong class="num">{{ $stats['menus_available'] }}<small> / {{ $stats['menus_total'] }}</small></strong>
            <small>{{ $stats['menus_total'] - $stats['menus_available'] }} sedang habis</small>
        </a>
    </section>

    <div class="dash-grid">
        <section class="panel" aria-labelledby="chart-title">
            <div class="panel-head">
                <h2 id="chart-title">Pendapatan 7 hari terakhir</h2>
                <small>Total {{ rupiah($weekRevenue) }} · {{ $weekOrders }} pesanan</small>
            </div>
            <div class="chart" aria-hidden="true">
                <span class="ymax num">{{ rupiah($maxRevenue > 1 ? $maxRevenue : 0) }}</span>
                <div class="bars">
                    @foreach ($week as $day)
                        <div class="bar" tabindex="0" style="--h: {{ round($day['revenue'] / $maxRevenue * 100, 1) }}%">
                            <span></span>
                            <span class="tip"><strong>{{ $day['label'] }}</strong><br>{{ rupiah($day['revenue']) }} · {{ $day['orders'] }} pesanan</span>
                        </div>
                    @endforeach
                </div>
                <div class="bars-x">
                    @foreach ($week as $day)
                        <span @class(['now' => $day['is_today']])>{{ $day['label'] }}</span>
                    @endforeach
                </div>
            </div>
            <table class="sr-only">
                <caption>Pendapatan dan jumlah pesanan per hari</caption>
                <thead><tr><th>Hari</th><th>Pendapatan</th><th>Pesanan</th></tr></thead>
                <tbody>
                    @foreach ($week as $day)
                        <tr><td>{{ $day['label'] }}</td><td>{{ rupiah($day['revenue']) }}</td><td>{{ $day['orders'] }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <section class="panel" aria-labelledby="top-title">
            <div class="panel-head">
                <h2 id="top-title">Menu terlaris</h2>
                <small>30 hari terakhir</small>
            </div>
            @if ($topMenus->isEmpty())
                <p class="empty">Belum ada penjualan.</p>
            @else
                <ol class="rank">
                    @foreach ($topMenus as $i => $item)
                        <li>
                            <span class="pos">{{ $i + 1 }}</span>
                            <strong>{{ $item->menu_name }}</strong>
                            <span class="num">{{ $item->qty }}× <small>· {{ rupiah($item->revenue) }}</small></span>
                            <span class="meter" aria-hidden="true"><i style="--w: {{ round($item->qty / $maxQty * 100, 1) }}%"></i></span>
                        </li>
                    @endforeach
                </ol>
            @endif
        </section>

        <section class="panel wide table-wrap" aria-labelledby="recent-title">
            <div class="panel-head">
                <h2 id="recent-title">Pesanan terbaru</h2>
                <a class="btn ghost small" href="{{ route('admin.orders.index') }}">Semua pesanan <x-icon name="arrow" /></a>
            </div>
            <table class="tbl">
                <thead>
                    <tr><th>No. order</th><th>Waktu</th><th>Nama</th><th>Diambil</th><th>Total</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($recentOrders as $order)
                        <tr>
                            <td class="num"><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td>
                            <td class="num">{{ $order->created_at->format('d/m H:i') }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->pickup_time }}</td>
                            <td class="num">{{ rupiah($order->total) }}</td>
                            <td><span class="badge {{ $order->status }}">{{ $order->status_label }}</span></td>
                            <td>@include('admin.orders._status-form')</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty">Belum ada pesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </div>
@endsection
