<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('menus')->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $category = Category::create($this->validated($request));

        return back()->with('status', "Kategori \"{$category->name}\" ditambahkan.");
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $category->update($this->validated($request, $category));

        return back()->with('status', "Kategori \"{$category->name}\" diperbarui.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->menus()->exists()) {
            return back()->with('error', "Kategori \"{$category->name}\" masih dipakai menu. Pindahkan atau hapus menunya dulu.");
        }

        $category->delete();

        return back()->with('status', "Kategori \"{$category->name}\" dihapus.");
    }

    private function validated(Request $request, ?Category $category = null): array
    {
        $request->merge(['slug' => Str::slug((string) $request->input('name'))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'slug' => ['required', Rule::unique('categories', 'slug')->ignore($category)],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ], [
            'slug.unique' => 'Nama kategori itu sudah ada.',
            'slug.required' => 'Nama kategori wajib diisi.',
        ]);

        $data['sort_order'] ??= (int) Category::max('sort_order') + 1;

        return $data;
    }
}
