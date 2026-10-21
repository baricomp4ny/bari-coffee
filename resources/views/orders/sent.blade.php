@extends('layouts.app')

@section('title', 'Pesanan terkirim · Bari Coffee')

@section('content')
    <div class="center-card">
        <div class="eyebrow">Pesanan tersimpan</div>
        <h1>Terima kasih!</h1>
        <p>Nomor ordermu <strong class="num">{{ $orderNumber }}</strong>. WhatsApp akan terbuka otomatis dengan detail pesanan. Tinggal tekan kirim.</p>
        <a class="btn primary" id="waLink" href="{{ $waUrl }}" rel="noopener">Buka WhatsApp</a>
        <p class="note"><a class="link" href="{{ route('home') }}">← Kembali ke beranda</a></p>
    </div>
@endsection

@push('scripts')
<script>
try { localStorage.removeItem("bari-cart"); } catch (e) {}
setTimeout(() => { window.location.href = document.getElementById("waLink").href; }, 1200);
</script>
@endpush
