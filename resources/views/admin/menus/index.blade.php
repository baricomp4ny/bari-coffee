@extends('layouts.admin')

@section('title', 'Kelola menu')

@section('content')
    <div class="admin-head">
        <div><div class="eyebrow">{{ $menus->count() }} menu ditampilkan</div><h1>Kelola menu</h1><p>Klik status untuk menandai menu habis atau tersedia.</p></div>
        <a class="btn primary" href="{{ route('admin.menus.create') }}"><x-icon name="plus" /> Tambah menu</a>
    </div>

    <div class="toolbar">
        <div class="tabs" role="tablist">
            <a class="tab" role="tab" aria-selected="{{ $categorySlug ? 'false' : 'true' }}" href="{{ route('admin.menus.index', array_filter(['q' => $search])) }}">Semua</a>
            @foreach ($categories as $category)
                <a class="tab" role="tab" aria-selected="{{ $categorySlug === $category->slug ? 'true' : 'false' }}" href="{{ route('admin.menus.index', array_filter(['kategori' => $category->slug, 'q' => $search])) }}">{{ $category->name }}</a>
            @endforeach
        </div>
        <form class="search" method="GET" action="{{ route('admin.menus.index') }}" role="search">
            @if ($categorySlug)<input type="hidden" name="kategori" value="{{ $categorySlug }}">@endif
            <x-icon name="search" />
            <input type="search" name="q" value="{{ $search }}" placeholder="Cari menu…" aria-label="Cari menu">
        </form>
    </div>

    <div class="panel table-wrap">
        <table class="tbl">
            <thead>
                <tr><th></th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Tag</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($menus as $menu)
                    <tr>
                        <td>
                            @if ($menu->photo)
                                <img class="mini" src="{{ $menu->photo_url }}" alt="">
                            @else
                                <x-cup class="mini" :art="$menu->art" :coffee="$menu->color_dark" :crema="$menu->color_light" />
                            @endif
                        </td>
                        <td><strong>{{ $menu->name }}</strong></td>
                        <td>{{ $menu->category->name }}</td>
                        <td class="num">{{ rupiah($menu->price) }}</td>
                        <td>{{ $menu->tag ?? '—' }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.menus.toggle', $menu) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="badge-btn" title="{{ $menu->is_available ? 'Tandai habis' : 'Tandai tersedia' }}">
                                    @if ($menu->is_available)
                                        <span class="badge selesai">Tersedia</span>
                                    @else
                                        <span class="badge off">Habis</span>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn ghost small" href="{{ route('admin.menus.edit', $menu) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" data-name="{{ $menu->name }}" onsubmit="return confirm('Hapus menu ' + this.dataset.name + '?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn danger small">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty">Tidak ada menu yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
