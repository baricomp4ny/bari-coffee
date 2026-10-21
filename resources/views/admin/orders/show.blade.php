@extends('layouts.admin')

@section('title', $order->order_number)

@section('content')
    <div class="admin-head">
        <div>
            <div class="eyebrow"><a class="link" href="{{ route('admin.orders.index') }}">← Pesanan</a></div>
            <h1 class="num">{{ $order->order_number }}</h1>
        </div>
        <div class="actions">
            <span class="badge {{ $order->status }}">{{ $order->status_label }}</span>
            @include('admin.orders._status-form')
        </div>
    </div>

    <section class="strip" aria-label="Ringkasan pesanan">
        <div><span class="eyebrow">Pemesan</span><strong>{{ $order->customer_name }}</strong></div>
        <div><span class="eyebrow">Diambil</span><strong>{{ $order->pickup_time }}</strong></div>
        <div><span class="eyebrow">Dipesan</span><strong>{{ $order->created_at->format('d/m/Y H:i') }}</strong></div>
    </section>

    <div class="panel table-wrap">
        <table class="tbl">
            <thead><tr><th>Menu</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr></thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td><strong>{{ $item->menu_name }}</strong></td>
                        <td class="num">{{ rupiah($item->price) }}</td>
                        <td class="num">{{ $item->quantity }}</td>
                        <td class="num">{{ rupiah($item->subtotal) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="total"><span>Total</span><span class="num">{{ rupiah($order->total) }}</span></div>
    </div>
@endsection
