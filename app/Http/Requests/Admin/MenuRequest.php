<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_available' => $this->boolean('is_available')]);
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:500'],
            'price' => ['required', 'integer', 'min:0', 'max:10000000'],
            'tag' => ['nullable', 'string', 'max:30'],
            'is_available' => ['boolean'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
            'color_light' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'color_dark' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];
    }

    public function attributes(): array
    {
        return [
            'category_id' => 'kategori',
            'name' => 'nama',
            'description' => 'deskripsi',
            'price' => 'harga',
            'photo' => 'foto',
            'color_light' => 'warna terang',
            'color_dark' => 'warna gelap',
        ];
    }
}
