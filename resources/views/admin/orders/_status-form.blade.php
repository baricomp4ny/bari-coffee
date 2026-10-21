<form class="inline-form" method="POST" action="{{ route('admin.orders.status', $order) }}">
    @csrf
    @method('PATCH')
    <select name="status" aria-label="Ubah status {{ $order->order_number }}" onchange="this.form.submit()">
        @foreach (\App\Models\Order::STATUSES as $key => $label)
            <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
        @endforeach
    </select>
    <noscript><button type="submit" class="btn ghost small">Simpan</button></noscript>
</form>
