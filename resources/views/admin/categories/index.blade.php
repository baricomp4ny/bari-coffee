@extends('layouts.admin')

@section('title', 'Kategori')

@section('content')
    <div class="admin-head">
        <div>
            <div class="eyebrow">{{ $categories->count() }} kategori</div>
            <h1>Kategori menu</h1>
            <p>Urutan menentukan posisi tab filter di beranda.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert" role="alert">{{ $errors->first() }}</div>
    @endif

    <section class="panel" aria-labelledby="add-title">
        <div class="panel-head"><h2 id="add-title">Tambah kategori</h2></div>
        <form class="cat-add" method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <label>Nama<input class="inp" name="name" maxlength="50" required placeholder="mis. Makanan berat"></label>
            <label>Urutan<input class="inp" name="sort_order" type="number" min="0" max="999" placeholder="otomatis"></label>
            <button type="submit" class="btn primary small"><x-icon name="plus" /> Tambah</button>
        </form>
    </section>

    <section class="panel" aria-label="Daftar kategori">
        @forelse ($categories as $category)
            <form class="cat-row" method="POST" action="{{ route('admin.categories.update', $category) }}">
                @csrf
                @method('PUT')
                <input class="inp" name="name" value="{{ $category->name }}" maxlength="50" required aria-label="Nama kategori">
                <input class="inp" name="sort_order" type="number" min="0" max="999" value="{{ $category->sort_order }}" aria-label="Urutan">
                <span class="meta">{{ $category->menus_count }} menu</span>
                <button type="submit" class="btn ghost small">Simpan</button>
                <button type="submit" class="btn danger small" form="del-{{ $category->id }}" @disabled($category->menus_count) title="{{ $category->menus_count ? 'Masih dipakai menu' : 'Hapus kategori' }}">Hapus</button>
            </form>
            <form id="del-{{ $category->id }}" method="POST" action="{{ route('admin.categories.destroy', $category) }}" data-name="{{ $category->name }}" onsubmit="return confirm('Hapus kategori ' + this.dataset.name + '?')" hidden>
                @csrf
                @method('DELETE')
            </form>
        @empty
            <p class="empty">Belum ada kategori.</p>
        @endforelse
    </section>
@endsection
