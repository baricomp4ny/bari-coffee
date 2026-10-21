<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuRequest;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $categorySlug = is_string($request->query('kategori')) ? $request->query('kategori') : null;
        $search = is_string($request->query('q')) ? trim($request->query('q')) : '';

        $menus = Menu::with('category')
            ->join('categories', 'categories.id', '=', 'menus.category_id')
            ->when($categorySlug, fn ($q) => $q->where('categories.slug', $categorySlug))
            ->when($search !== '', fn ($q) => $q->where('menus.name', 'like', '%'.addcslashes($search, '%_\\').'%'))
            ->orderBy('categories.sort_order')
            ->orderBy('menus.id')
            ->select('menus.*')
            ->get();

        $categories = Category::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.menus.index', compact('menus', 'categories', 'categorySlug', 'search'));
    }

    public function create(): View
    {
        return view('admin.menus.create', [
            'menu' => new Menu(['is_available' => true, 'color_light' => '#D9C2A3', 'color_dark' => '#5A321A']),
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function store(MenuRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['photo', 'remove_photo']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('menus', 'public');
        }

        $menu = Menu::create($data);

        return redirect()->route('admin.menus.index')->with('status', "Menu \"{$menu->name}\" ditambahkan.");
    }

    public function edit(Menu $menu): View
    {
        return view('admin.menus.edit', [
            'menu' => $menu,
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function update(MenuRequest $request, Menu $menu): RedirectResponse
    {
        $data = $request->safe()->except(['photo', 'remove_photo']);

        if ($request->hasFile('photo') || $request->boolean('remove_photo')) {
            $this->deletePhoto($menu);
            $data['photo'] = $request->hasFile('photo')
                ? $request->file('photo')->store('menus', 'public')
                : null;
        }

        $menu->update($data);

        return redirect()->route('admin.menus.index')->with('status', "Menu \"{$menu->name}\" diperbarui.");
    }

    /**
     * Tandai menu habis / tersedia lagi dengan satu klik.
     */
    public function toggle(Menu $menu): RedirectResponse
    {
        $menu->update(['is_available' => ! $menu->is_available]);

        return back()->with('status', "Menu \"{$menu->name}\" ".($menu->is_available ? 'tersedia lagi.' : 'ditandai habis.'));
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $this->deletePhoto($menu);
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('status', "Menu \"{$menu->name}\" dihapus.");
    }

    private function deletePhoto(Menu $menu): void
    {
        if ($menu->photo && ! $menu->hasBundledPhoto()) {
            Storage::disk('public')->delete($menu->photo);
        }
    }
}
