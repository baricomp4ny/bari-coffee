<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Pesanan contoh 7 hari terakhir agar dashboard admin terisi saat presentasi.
 * Tidak dipanggil otomatis. Jalankan: php artisan db:seed --class=DemoOrderSeeder
 */
class DemoOrderSeeder extends Seeder
{
    public function run(): void
    {
        $menus = Menu::where('is_available', true)->get();
        if ($menus->isEmpty()) {
            $this->command?->warn('Belum ada menu. Jalankan MenuSeeder dulu.');

            return;
        }

        $names = ['Rani', 'Budi', 'Sari', 'Dimas', 'Ayu', 'Fajar', 'Nadia', 'Rizky', 'Putri', 'Bayu', 'Intan', 'Galih'];
        mt_srand(2026);

        for ($day = 6; $day >= 0; $day--) {
            $perDay = mt_rand(2, 6);

            for ($n = 0; $n < $perDay; $n++) {
                $at = Carbon::today()->subDays($day)->setTime(mt_rand(8, 20), mt_rand(0, 59));
                if ($at->isFuture()) {
                    $at = now()->subMinutes(mt_rand(5, 120));
                }

                $lines = $menus->random(mt_rand(1, 3))->map(fn ($m) => [
                    'menu_id' => $m->id,
                    'menu_name' => $m->name,
                    'price' => $m->price,
                    'quantity' => $q = mt_rand(1, 3),
                    'subtotal' => $m->price * $q,
                ]);

                $order = new Order([
                    'customer_name' => $names[array_rand($names)],
                    'pickup_time' => Order::PICKUP_TIMES[array_rand(Order::PICKUP_TIMES)],
                    'total' => $lines->sum('subtotal'),
                    // pesanan lama sudah selesai, pesanan hari ini campuran
                    'status' => $day > 0 ? 'selesai' : ['baru', 'diproses', 'selesai'][mt_rand(0, 2)],
                ]);
                $order->created_at = $order->updated_at = $at;
                $order->save();

                $order->items()->createMany($lines->map(fn ($l) => $l + ['created_at' => $at, 'updated_at' => $at])->all());
                $order->update(['order_number' => 'BC-'.$at->format('ymd').'-'.str_pad($order->id, 4, '0', STR_PAD_LEFT)]);
            }
        }

        mt_srand();
    }
}
