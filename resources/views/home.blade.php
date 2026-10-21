@extends('layouts.app')

@php
    $categoryIcons = ['kopi' => 'cup', 'manual-brew' => 'dripper', 'non-kopi' => 'glass', 'camilan' => 'croissant'];
@endphp

@section('nav-action')
    <button class="cart-btn" id="openCart" aria-label="Buka pesanan">Pesanan <b id="count">0</b></button>
@endsection

@section('content')
    {{-- Hero --}}
    <header class="hero">
        <div>
            <h1><span>Kopi Lokal</span><span>Terbaik Untukmu</span></h1>
            <p>Arabika Gunung Halu dan Kerinci, disangrai sendiri setiap Senin. Pilih menu, kami siapkan, kamu tinggal ambil di kedai.</p>
            <a class="btn primary" href="#menu">Lihat Menu</a>
            <div class="quick" aria-label="Lompat ke kategori">
                @foreach ($categories as $category)
                    <button type="button" data-jump="{{ $category->slug }}" title="{{ $category->name }}" aria-label="Lihat {{ $category->name }}">
                        <x-icon :name="$categoryIcons[$category->slug] ?? 'bean'" />
                    </button>
                @endforeach
            </div>
        </div>
        <div class="hero-cup">
            <x-cup art="heart" :handle="true" coffee="#B98654" crema="#EAD2AE" foam="#6A3014" />
        </div>
    </header>

    {{-- Menu unggulan --}}
    @if ($featured->isNotEmpty())
        <section class="featured" aria-label="Menu unggulan">
            @foreach ($featured as $menu)
                <article class="fcard">
                    <div class="cup-wrap">
                        @include('partials.menu-visual', ['menu' => $menu])
                    </div>
                    <h3>{{ $menu->name }}</h3>
                    <p>{{ $menu->description }}</p>
                    <div class="acts">
                        <button type="button" class="icon-btn add-btn" data-id="{{ $menu->id }}" aria-label="Tambah {{ $menu->name }} ke pesanan">
                            <x-icon name="cart" />
                        </button>
                    </div>
                    <span class="pill num">{{ rupiah($menu->price) }}</span>
                </article>
            @endforeach
            <div class="intro">
                <div class="eyebrow">Pilihan barista</div>
                <h2>Secangkir yang bikin kamu balik lagi.</h2>
                <p>Semua minuman diracik dari biji yang kami beli langsung dari petani. Tidak pakai sirup instan, gula aren pun kami masak sendiri.</p>
                <a class="btn primary sm" href="#menu">Selengkapnya</a>
            </div>
        </section>
    @endif

    {{-- Racikan andalan --}}
    @if ($signature)
        <section class="signature" id="andalan">
            <div>
                <div class="eyebrow">Racikan andalan</div>
                <h2>{{ $signature->name }}, manisnya pas dan kopinya tetap terasa.</h2>
                <p>{{ $signature->description }} Diseduh dari biji yang kami sangrai sendiri setiap Senin, jadi rasanya selalu segar.</p>
                <div class="recipe">
                    <div><span>Espresso double</span><span class="num">36 g</span></div>
                    <div><span>Susu segar</span><span class="num">150 ml</span></div>
                    <div><span>Rasio seduh</span><span class="num">1 : 2</span></div>
                </div>
                <button type="button" class="btn primary sm add-btn" data-id="{{ $signature->id }}" data-open="1">
                    <x-icon name="plus" /> Pesan sekarang
                </button>
            </div>
            <div class="sig-art">
                <x-beans :count="64" :inner="120" :outer="198" />
                @if ($signature->photo)
                    <img class="cup-art" src="{{ $signature->photo_url }}" alt="{{ $signature->name }}" style="border-radius:50%;object-fit:cover">
                @else
                    <x-cup art="rosetta" coffee="#5A2E15" crema="#D19A62" />
                @endif
                <span class="bubble num">{{ rupiah($signature->price) }}</span>
            </div>
        </section>
    @endif

    {{-- Etalase menu --}}
    <section id="menu">
        <div class="menu-head">
            <div><div class="eyebrow">Menu kami</div><h2>Mau minum apa hari ini?</h2></div>
            <p>Dari kopi susu sampai manual brew single origin, plus camilan hangat dari oven setiap pagi.</p>
            <a class="btn ghost sm" href="#kontak">Cara pesan <x-icon name="arrow" /></a>
        </div>
        <div class="tabs" role="tablist" id="tabs">
            <button class="tab" role="tab" aria-selected="true" data-c="semua">Semua</button>
            @foreach ($categories as $category)
                <button class="tab" role="tab" aria-selected="false" data-c="{{ $category->slug }}">{{ $category->name }}</button>
            @endforeach
        </div>
        <div class="grid" id="grid">
            @forelse ($menus as $menu)
                <article class="item {{ $menu->is_available ? '' : 'soldout' }}" data-cat="{{ $menu->category->slug }}">
                    <div class="thumb" style="background:radial-gradient(120% 90% at 50% 20%, {{ $menu->color_light }}, {{ $menu->color_dark }})">
                        @include('partials.menu-visual', ['menu' => $menu])
                    </div>
                    @if (! $menu->is_available)
                        <span class="tag">Habis</span>
                    @elseif ($menu->tag)
                        <span class="tag">{{ $menu->tag }}</span>
                    @endif
                    <div class="info">
                        <div class="eyebrow">{{ $menu->category->name }}</div>
                        <h3>{{ $menu->name }}</h3>
                        <p>{{ $menu->description }}</p>
                        <div class="row">
                            <span class="price num">{{ rupiah($menu->price) }}</span>
                            @if ($menu->is_available)
                                <button type="button" class="add add-btn" data-id="{{ $menu->id }}" aria-label="Tambah {{ $menu->name }} ke pesanan"><x-icon name="plus" /></button>
                            @else
                                <button type="button" class="add" disabled aria-label="{{ $menu->name }} sedang habis"><x-icon name="plus" /></button>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <p class="empty">Menu belum tersedia.</p>
            @endforelse
        </div>
    </section>

    {{-- Keunggulan --}}
    <section class="perks" aria-label="Kenapa Bari Coffee">
        <div><x-icon name="leaf" /><p><strong>Langsung dari petani</strong><span>Tiga kebun kopi lokal</span></p></div>
        <div><x-icon name="flame" /><p><strong>Sangrai sendiri</strong><span>Segar setiap Senin</span></p></div>
        <div><x-icon name="wifi" /><p><strong>Wi-Fi & colokan</strong><span>Di setiap meja</span></p></div>
        <div><x-icon name="chat" /><p><strong>Pesan via WhatsApp</strong><span>Tinggal ambil di kedai</span></p></div>
    </section>

    {{-- Tentang & lokasi --}}
    <section class="about" id="tentang">
        <div class="story">
            <div class="eyebrow">Tentang kami</div>
            <h2>Kedai kecil di bawah pohon ketapang.</h2>
            <p>Bari Coffee berawal dari gerobak di depan kampus. Sekarang kami punya 24 kursi, satu mesin roasting kecil, dan pemasok tetap dari tiga kebun kopi petani lokal.</p>
            <p>Semua biji kami beli langsung dari petani, jadi harga di cangkirmu ikut menghidupi kebunnya.</p>
            <x-cup art="tulip" coffee="#6B3E22" crema="#D6A26E" />
        </div>
        <div class="visit" id="lokasi">
            <div class="eyebrow">Lokasi & jam buka</div>
            <h2>Mampir ke kedai</h2>
            <p class="addr"><x-icon name="pin" /> Jl. Pajajaran No. 12, Bogor</p>
            <div class="hours">
                <div><span>Senin – Jumat</span><span class="num">08.00 – 22.00</span></div>
                <div><span>Sabtu</span><span class="num">09.00 – 23.00</span></div>
                <div><span>Minggu</span><span class="num">09.00 – 21.00</span></div>
            </div>
        </div>
    </section>

    {{-- Ajakan pesan --}}
    <section class="cta-block" id="kontak">
        <div class="cta-row">
            <div><div class="eyebrow">Pesan dulu, ambil nanti</div><h2>Mau kopinya siap saat kamu datang?</h2></div>
            <button type="button" class="btn primary" id="ctaOrder">Buat pesanan</button>
        </div>
        <p>Pilih menu, tekan tombol tambah, lalu isi nama dan jam ambil di keranjang. Pesananmu tersimpan dan WhatsApp terbuka dengan detail pesanan beserta nomor order. Tinggal kirim, lalu ambil di kedai.</p>
    </section>
@endsection

@section('after')
    <div class="scrim" id="scrim" hidden></div>
    <aside class="tray" id="tray" aria-label="Pesanan kamu" aria-hidden="true">
        <div class="tray-head"><h3>Pesanan kamu</h3><button class="x" id="closeCart" aria-label="Tutup">✕</button></div>
        <form method="POST" action="{{ route('orders.store') }}" id="orderForm" style="display:contents">
            @csrf
            @if ($errors->any())
                <div class="alert" role="alert">{{ $errors->first() }}</div>
            @endif
            <div class="lines" id="lines"></div>
            <div id="cartInputs"></div>
            <label class="field" for="nama">Nama pemesan
                <input id="nama" name="nama" value="{{ old('nama') }}" placeholder="mis. Rani" autocomplete="name" maxlength="60" required>
            </label>
            <label class="field" for="ambil">Diambil jam
                <select id="ambil" name="ambil">
                    @foreach (\App\Models\Order::PICKUP_TIMES as $time)
                        <option @selected(old('ambil') === $time)>{{ $time }}</option>
                    @endforeach
                </select>
            </label>
            <div class="total"><span>Total</span><span class="num" id="total">Rp0</span></div>
            <button type="submit" class="btn primary wa" id="wa" disabled>Kirim pesanan via WhatsApp</button>
            <p class="note">Pesanan disimpan, lalu WhatsApp terbuka dengan pesan otomatis. Nomor kedai: {{ $cafeWhatsappDisplay }}.</p>
        </form>
    </aside>
@endsection

@push('scripts')
<script>
const MENU = @json($menuData);
const CART_KEY = "bari-cart";
const rp = n => "Rp" + n.toLocaleString("id-ID");
const $ = id => document.getElementById(id);
const esc = s => String(s).replace(/[&<>"']/g, c => ({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"}[c]));

let cart = {};
try { cart = JSON.parse(localStorage.getItem(CART_KEY) || "{}") || {}; } catch (e) { cart = {}; }
// buang item yang sudah tidak ada atau sedang habis
for (const k of Object.keys(cart)) { if (!MENU[k] || !(cart[k] > 0)) delete cart[k]; }

function save(){ try { localStorage.setItem(CART_KEY, JSON.stringify(cart)); } catch (e) {} }

function renderButtons(){
  document.querySelectorAll(".add-btn[data-id]").forEach(b => {
    const q = cart[b.dataset.id] || 0;
    if (b.dataset.open) return; // tombol "Pesan sekarang" tidak menampilkan jumlah
    b.classList.toggle("on", q > 0);
    b.dataset.qty = q ? q : "";
  });
}
function renderCart(){
  const ids = Object.keys(cart);
  const count = ids.reduce((s,k)=>s+cart[k],0);
  const total = ids.reduce((s,k)=>s+cart[k]*MENU[k].price,0);
  $("count").textContent = count;
  $("total").textContent = rp(total);
  $("lines").innerHTML = ids.length ? ids.map(k=>{const m=MENU[k];return `
    <div class="line${k === lastAdded ? " added" : ""}"><strong>${esc(m.name)}</strong><span class="num">${rp(m.price*cart[k])}</span>
    <small>${rp(m.price)} / item</small>
    <div class="qty"><button type="button" data-dec="${k}" aria-label="Kurangi">−</button><span class="num">${cart[k]}</span><button type="button" data-inc="${k}" aria-label="Tambah">+</button></div></div>`}).join("")
    : `<div class="empty">Belum ada pesanan.<br>Pilih menu lalu tekan tombol “+”.</div>`;
  $("cartInputs").innerHTML = ids.map(k=>`<input type="hidden" name="items[${k}]" value="${cart[k]}">`).join("");
  $("wa").disabled = !ids.length;
}
function render(){ renderButtons(); renderCart(); save(); }

function filterMenu(slug){
  document.querySelectorAll(".tab").forEach(b => b.setAttribute("aria-selected", b.dataset.c === slug));
  document.querySelectorAll(".item").forEach(i => {
    i.hidden = !(slug === "semua" || i.dataset.cat === slug);
    i.classList.remove("filtered-in");
    if (!i.hidden) { void i.offsetWidth; i.classList.add("in", "filtered-in"); }
  });
}
let lastAdded = null;
function added(id){
  lastAdded = id;
  render();
  lastAdded = null;
  window.bariBump?.($("openCart"));
  window.bariToast?.(`${MENU[id].name} ditambahkan · ${cart[id]} di pesanan`);
}
function setTray(open){ $("tray").classList.toggle("open", open); $("tray").setAttribute("aria-hidden", !open); $("scrim").hidden = !open; }

document.addEventListener("click", e => {
  const t = e.target.closest("button"); if (!t) return;
  if (t.dataset.c) filterMenu(t.dataset.c);
  if (t.dataset.jump) { filterMenu(t.dataset.jump); $("menu").scrollIntoView(); }
  if (t.dataset.id && MENU[t.dataset.id]) { cart[t.dataset.id] = (cart[t.dataset.id] || 0) + 1; added(t.dataset.id); if (t.dataset.open) setTray(true); }
  if (t.dataset.inc) { cart[t.dataset.inc]++; lastAdded = t.dataset.inc; render(); lastAdded = null; }
  if (t.dataset.dec) { cart[t.dataset.dec]--; if (cart[t.dataset.dec] <= 0) delete cart[t.dataset.dec]; render(); }
});
$("openCart").onclick = () => setTray(true);
$("ctaOrder").onclick = () => setTray(true);
$("closeCart").onclick = () => setTray(false);
$("scrim").onclick = () => setTray(false);
document.addEventListener("keydown", e => { if (e.key === "Escape") setTray(false); });
render();
@if ($errors->any()) setTray(true); @endif
</script>
@endpush
