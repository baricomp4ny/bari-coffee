<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::whereHas('menus')
            ->with(['menus' => fn ($q) => $q->orderBy('id')])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $menus = $categories->flatMap->menus->each(
            fn ($menu) => $menu->setRelation('category', $categories->firstWhere('id', $menu->category_id))
        );

        // Data untuk keranjang di JavaScript: hanya menu yang tersedia
        $menuData = $menus->where('is_available', true)
            ->mapWithKeys(fn ($m) => [$m->id => ['name' => $m->name, 'price' => $m->price]]);

        // Racikan andalan: menu bertag "Terlaris", lalu dua minuman unggulan lain (yang bertag didahulukan)
        $available = $menus->where('is_available', true);
        $signature = $available->first(fn ($m) => strcasecmp((string) $m->tag, 'Terlaris') === 0) ?? $available->first();
        $featured = $available->reject(fn ($m) => $m->is($signature))
            ->sortBy(fn ($m) => [$m->art === 'plate' ? 1 : 0, $m->tag ? 0 : 1, $m->id])
            ->take(2)
            ->values();

        return view('home', compact('categories', 'menus', 'menuData', 'signature', 'featured'));
    }
}
