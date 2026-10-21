<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Database\Seeders\MenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BariCoffeeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(MenuSeeder::class);
    }

    public function test_beranda_menampilkan_menu_dari_database(): void
    {
        Menu::where('name', 'Americano')->update(['is_available' => false]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Bari Coffee')
            ->assertDontSee('Kopi Rindang')
            ->assertSee('Es Kopi Gula Aren')
            ->assertSee('data-c="manual-brew"', false)
            ->assertSee('Rp22.000')
            ->assertSee('Habis');
    }

    public function test_pesanan_disimpan_dan_diarahkan_ke_whatsapp(): void
    {
        config(['cafe.whatsapp' => '6289900001111']);
        $ekga = Menu::where('name', 'Es Kopi Gula Aren')->first();
        $pisang = Menu::where('name', 'Pisang Goreng Keju')->first();

        $response = $this->post('/pesanan', [
            'nama' => 'Rani',
            'ambil' => '15 menit lagi',
            'items' => [$ekga->id => 2, $pisang->id => 1],
        ]);

        $order = Order::with('items')->sole();
        $this->assertSame(61000, $order->total);
        $this->assertSame('baru', $order->status);
        $this->assertCount(2, $order->items);
        $this->assertMatchesRegularExpression('/^BC-\d{6}-0001$/', $order->order_number);

        $response->assertRedirect(route('orders.sent'));
        $waUrl = session('wa_url');
        $this->assertStringStartsWith('https://wa.me/6289900001111?text=', $waUrl);
        $text = rawurldecode(explode('?text=', $waUrl)[1]);
        $this->assertStringContainsString($order->order_number, $text);
        $this->assertStringContainsString('2x Es Kopi Gula Aren (Rp44.000)', $text);
        $this->assertStringContainsString('Total: Rp61.000', $text);
    }

    public function test_pesanan_menolak_menu_habis_dan_keranjang_kosong(): void
    {
        $menu = Menu::first();
        $menu->update(['is_available' => false]);

        $this->post('/pesanan', ['nama' => 'Rani', 'ambil' => 'Secepatnya', 'items' => [$menu->id => 1]])
            ->assertSessionHasErrors('items');
        $this->post('/pesanan', ['nama' => 'Rani', 'ambil' => 'Secepatnya'])
            ->assertSessionHasErrors('items');
        $this->assertSame(0, Order::count());
    }

    public function test_ekspor_demo_statis(): void
    {
        $path = 'storage/framework/testing/demo';

        $this->artisan('demo:export', ['--path' => $path])->assertSuccessful();

        $html = file_get_contents(base_path("{$path}/index.html"));
        $this->assertStringContainsString('Es Kopi Gula Aren', $html);
        $this->assertStringContainsString('href="./css/bari.css', $html);
        $this->assertStringContainsString('src="./js/bari.js', $html);
        $this->assertStringContainsString('src="./images/menu/hero-cup.webp"', $html);
        $this->assertFileExists(base_path("{$path}/images/menu/es-kopi-gula-aren.webp"));
        $this->assertStringContainsString('Lihat kode di GitHub', $html);
        $this->assertStringNotContainsString('csrf-token', $html);
        $this->assertStringNotContainsString('name="_token"', $html);
        $this->assertStringNotContainsString('/pesanan', $html);
        $this->assertFileExists(base_path("{$path}/css/bari.css"));
        $this->assertFileExists(base_path("{$path}/.nojekyll"));

        \Illuminate\Support\Facades\File::deleteDirectory(base_path($path));
    }

    public function test_halaman_admin_wajib_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/pesanan')->assertRedirect('/admin/login');
        $this->get('/admin/kategori')->assertRedirect('/admin/login');
        $this->get('/admin/menu')->assertRedirect('/admin/login');
        $this->get('/register')->assertNotFound();
    }

    public function test_akun_bukan_admin_ditolak_dan_dikeluarkan(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin')
            ->assertRedirect('/admin/login')
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $menu = Menu::first();
        $this->actingAs($user)->patch("/admin/menu/{$menu->id}/stok")->assertRedirect('/admin/login');
        $this->assertTrue($menu->fresh()->is_available);

        // login dengan akun bukan admin juga ditolak di halaman login
        $this->post('/admin/login', ['email' => $user->email, 'password' => 'password'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_bisa_login_dan_keluar(): void
    {
        $admin = User::factory()->admin()->create();

        $this->get('/admin/login')->assertOk()->assertSee('Masuk admin');
        $this->get('/login')->assertNotFound();
        $this->post('/admin/login', ['email' => $admin->email, 'password' => 'password'])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
        $this->get('/admin')->assertOk();

        $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest();
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_admin_kelola_menu_dengan_foto(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $category = Menu::first()->category;

        $this->actingAs($admin)->post('/admin/menu', [
            'category_id' => $category->id,
            'name' => 'Kopi Susu Pandan',
            'description' => 'Espresso, susu, sirup pandan.',
            'price' => 25000,
            'is_available' => '1',
            'color_light' => '#C7D9B0',
            'color_dark' => '#5A321A',
            'photo' => UploadedFile::fake()->image('pandan.jpg'),
        ])->assertRedirect('/admin/menu');

        $menu = Menu::where('name', 'Kopi Susu Pandan')->sole();
        Storage::disk('public')->assertExists($menu->photo);

        $this->actingAs($admin)->put("/admin/menu/{$menu->id}", [
            'category_id' => $category->id,
            'name' => 'Kopi Susu Pandan',
            'description' => 'Espresso, susu, sirup pandan.',
            'price' => 27000,
            'color_light' => '#C7D9B0',
            'color_dark' => '#5A321A',
            'remove_photo' => '1',
        ])->assertRedirect('/admin/menu');

        $menu->refresh();
        $this->assertSame(27000, $menu->price);
        $this->assertFalse($menu->is_available);
        $this->assertNull($menu->photo);

        $this->actingAs($admin)->delete("/admin/menu/{$menu->id}")->assertRedirect('/admin/menu');
        $this->assertModelMissing($menu);
    }

    public function test_dashboard_menampilkan_ringkasan(): void
    {
        $admin = User::factory()->admin()->create();
        $menu = Menu::where('name', 'Americano')->first();
        $this->post('/pesanan', ['nama' => 'Sari', 'ambil' => 'Secepatnya', 'items' => [$menu->id => 3]]);

        $this->actingAs($admin)->get('/admin')
            ->assertOk()
            ->assertSee('Pendapatan 7 hari terakhir')
            ->assertSee('Rp54.000')
            ->assertSee('Americano')
            ->assertSee('Sari')
            ->assertSee('class="count"', false);
    }

    public function test_admin_kelola_kategori(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post('/admin/kategori', ['name' => 'Makanan Berat'])->assertRedirect();
        $category = \App\Models\Category::where('slug', 'makanan-berat')->sole();
        $this->assertSame(5, $category->sort_order);

        $this->actingAs($admin)->post('/admin/kategori', ['name' => 'makanan berat'])->assertSessionHasErrors('slug');

        $this->actingAs($admin)->put("/admin/kategori/{$category->id}", ['name' => 'Makanan', 'sort_order' => 9])->assertRedirect();
        $this->assertSame('makanan', $category->fresh()->slug);

        $kopi = \App\Models\Category::where('slug', 'kopi')->sole();
        $this->actingAs($admin)->delete("/admin/kategori/{$kopi->id}")->assertSessionHas('error');
        $this->assertModelExists($kopi);

        $this->actingAs($admin)->delete("/admin/kategori/{$category->id}")->assertSessionHas('status');
        $this->assertModelMissing($category);
    }

    public function test_admin_ubah_stok_dan_cari_menu(): void
    {
        $admin = User::factory()->admin()->create();
        $menu = Menu::where('name', 'Matcha Latte')->first();

        $this->actingAs($admin)->patch("/admin/menu/{$menu->id}/stok")->assertRedirect();
        $this->assertFalse($menu->fresh()->is_available);
        $this->get('/')->assertSee('Matcha Latte sedang habis');

        $this->actingAs($admin)->get('/admin/menu?q=matcha')->assertOk()->assertSee('Matcha Latte')->assertDontSee('Americano');
        $this->actingAs($admin)->get('/admin/menu?kategori=camilan')->assertOk()->assertSee('Croissant')->assertDontSee('Matcha Latte');
    }

    public function test_admin_lihat_dan_ubah_status_pesanan(): void
    {
        $admin = User::factory()->admin()->create();
        $menu = Menu::first();
        $this->post('/pesanan', ['nama' => 'Budi', 'ambil' => 'Secepatnya', 'items' => [$menu->id => 1]]);
        $order = Order::sole();

        $this->actingAs($admin)->get('/admin/pesanan')->assertOk()->assertSee($order->order_number)->assertSee('Budi');
        $this->actingAs($admin)->get("/admin/pesanan/{$order->id}")->assertOk()->assertSee($menu->name);

        $this->actingAs($admin)->patch("/admin/pesanan/{$order->id}/status", ['status' => 'diproses'])->assertRedirect();
        $this->assertSame('diproses', $order->fresh()->status);

        $this->actingAs($admin)->patch("/admin/pesanan/{$order->id}/status", ['status' => 'batal'])->assertSessionHasErrors('status');
        $this->actingAs($admin)->get('/admin/pesanan?status=selesai')->assertOk()->assertDontSee('Budi');
        $this->actingAs($admin)->get('/admin/pesanan?q=bud')->assertOk()->assertSee($order->order_number);
        $this->actingAs($admin)->get('/admin/pesanan?q=zzz')->assertOk()->assertDontSee($order->order_number);
    }
}
