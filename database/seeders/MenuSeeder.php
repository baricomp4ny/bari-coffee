<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    /**
     * Data menu diambil dari desain acuan (kopi-rindang.html).
     */
    public function run(): void
    {
        $menus = [
            ['Kopi', 'Es Kopi Gula Aren', 'Espresso, susu segar, gula aren cair dari Banten.', 22000, 'Terlaris', '#C9A27A', '#5A321A'],
            ['Kopi', 'Americano', 'Double shot arabika Gunung Halu, panas atau dingin.', 18000, null, '#B88B5E', '#2A170C'],
            ['Kopi', 'Kafe Latte', 'Espresso lembut dengan susu dan lapisan foam tipis.', 24000, null, '#D9C2A3', '#9C6B43'],
            ['Manual Brew', 'V60 Kerinci', 'Fruity, aroma jeruk. Seduh 1:15, 3 menit.', 28000, 'Single origin', '#9DB7A2', '#5E3418'],
            ['Manual Brew', 'Japanese Iced', 'V60 diseduh langsung ke atas es, segar dan ringan.', 28000, null, '#A9C4CF', '#6A3B1D'],
            ['Non-Kopi', 'Matcha Latte', 'Matcha Uji dikocok manual dengan susu segar.', 26000, null, '#C7D9B0', '#6E9450'],
            ['Non-Kopi', 'Cokelat Bari', 'Cokelat hitam 70% dari Jembrana, Bali.', 23000, null, '#D4B49A', '#4A2715'],
            ['Non-Kopi', 'Teh Serai Madu', 'Teh hitam, serai segar, dan madu hutan.', 18000, null, '#E6CF91', '#B4802A'],
            ['Camilan', 'Croissant Mentega', 'Dipanggang tiap pagi, renyah di luar.', 20000, null, '#EBD5A8', '#C48A3C'],
            ['Camilan', 'Pisang Goreng Keju', 'Pisang kepok, keju parut, susu kental manis.', 17000, 'Favorit', '#F0DDA0', '#D49B3A'],
        ];

        $categories = [];
        foreach (array_values(array_unique(array_column($menus, 0))) as $i => $name) {
            $categories[$name] = Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'sort_order' => $i + 1],
            );
        }

        foreach ($menus as [$cat, $name, $desc, $price, $tag, $light, $dark]) {
            $menu = Menu::updateOrCreate(['name' => $name], [
                'category_id' => $categories[$cat]->id,
                'description' => $desc,
                'price' => $price,
                'tag' => $tag,
                'is_available' => true,
                'color_light' => $light,
                'color_dark' => $dark,
            ]);

            // Foto bawaan (Unsplash, lihat README); foto yang sudah diupload admin tidak ditimpa
            $photo = 'images/menu/'.Str::slug($name).'.webp';
            if (! $menu->photo && file_exists(public_path($photo))) {
                $menu->update(['photo' => $photo]);
            }
        }
    }
}
