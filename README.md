# Bari Coffee ☕

Website kedai kopi dengan pemesanan online lewat WhatsApp dan panel admin. Dibangun dengan **Laravel 10** sebagai proyek kuliah.

Pelanggan memilih menu, mengisi nama dan jam ambil, lalu pesanannya tersimpan di database dan WhatsApp terbuka dengan pesan berisi detail pesanan dan nomor order. Pengelola kedai mengatur menu, kategori, dan status pesanan dari panel admin.

## Fitur

**Situs pelanggan**
- Beranda dengan hero, menu unggulan, racikan andalan, etalase menu, dan info lokasi/jam buka
- Menu diambil dari database, dengan filter kategori dan label "Habis" untuk menu yang tidak tersedia
- Keranjang di sisi kanan layar, jumlah bisa ditambah/dikurangi, tersimpan saat halaman dimuat ulang
- Pesanan disimpan ke tabel `orders` dan `order_items`, lalu diarahkan ke `wa.me` dengan nomor order
- Harga selalu dihitung ulang di server, jadi tidak bisa diubah dari browser
- Ilustrasi cangkir SVG otomatis bila menu belum punya foto

**Panel admin** (`/admin`)
- Login khusus admin di `/admin/login`, terpisah dari situs pelanggan; hanya akun `is_admin` yang bisa masuk
- Dashboard: pesanan menunggu, pesanan dan pendapatan hari ini, grafik pendapatan 7 hari, menu terlaris
- Kelola menu: tambah, edit, hapus, upload foto, tandai habis/tersedia dengan satu klik, cari dan filter
- Kelola kategori dan urutan tab filter
- Daftar pesanan dengan filter status, pencarian, dan ubah status (baru → diproses → selesai)

**Tampilan**
- Palet keluarga cokelat (krem, latte, toffee, hazelnut, kayu manis, mocha, kakao)
- Mode gelap otomatis mengikuti perangkat
- Responsif dari layar HP 320 px sampai desktop
- Animasi hover, muncul saat digulir, dan notifikasi "ditambahkan"; mati otomatis bila perangkat meminta gerakan dikurangi

## Teknologi

Laravel 10 · PHP 8.1 · MySQL · Laravel Breeze (autentikasi) · Blade · CSS & JavaScript tanpa build step · PHPUnit

## Menjalankan di komputer sendiri

Butuh PHP 8.1+, Composer, dan MySQL.

```bash
git clone https://github.com/baricomp4ny/bari-coffee.git
cd bari-coffee
composer install
cp .env.example .env
php artisan key:generate
```

Buat database `bari_coffee` di MySQL, lalu sesuaikan `.env`:

```env
DB_DATABASE=bari_coffee
DB_USERNAME=root
DB_PASSWORD=

CAFE_WHATSAPP=6281234567890      # nomor WA kafe, format internasional tanpa +
ADMIN_EMAIL=admin@baricoffee.test
ADMIN_PASSWORD=ganti-kata-sandi-ini
```

Lalu jalankan:

```bash
php artisan migrate --seed       # tabel + menu contoh + akun admin
php artisan storage:link         # agar foto menu yang diupload bisa tampil
php artisan serve
```

Buka http://localhost:8000. Panel admin ada di http://localhost:8000/admin/login.

Untuk mengisi dashboard dengan pesanan contoh 7 hari terakhir (misalnya untuk presentasi):

```bash
php artisan db:seed --class=DemoOrderSeeder
```

## Tes

Tes memakai database terpisah `bari_coffee_test` (lihat `phpunit.xml`), jadi data asli tidak terhapus.

```bash
php artisan test
```

## Struktur penting

| Bagian | Lokasi |
|---|---|
| Halaman beranda | `resources/views/home.blade.php` |
| Layout situs & admin | `resources/views/layouts/` |
| Panel admin | `resources/views/admin/`, `app/Http/Controllers/Admin/` |
| Pemesanan & WhatsApp | `app/Http/Controllers/OrderController.php` |
| Gaya & interaksi | `public/css/bari.css`, `public/js/bari.js` |
| Data menu contoh | `database/seeders/MenuSeeder.php` |

---

Data menu, alamat, dan nomor WhatsApp di proyek ini adalah data contoh.
