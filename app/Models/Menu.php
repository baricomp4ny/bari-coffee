<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'description', 'price', 'photo', 'tag',
        'is_available', 'color_light', 'color_dark',
    ];

    protected $casts = [
        'price' => 'integer',
        'is_available' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * URL foto menu (disimpan di disk "public"), atau null bila belum ada.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo) {
            return null;
        }

        // Foto bawaan proyek ada di public/images, foto upload admin ada di storage
        return $this->hasBundledPhoto() ? asset($this->photo) : asset('storage/'.$this->photo);
    }

    /**
     * Foto bawaan (images/menu/...) ikut di repo dan tidak boleh dihapus dari disk.
     */
    public function hasBundledPhoto(): bool
    {
        return str_starts_with((string) $this->photo, 'images/');
    }

    /**
     * Jenis ilustrasi bila tanpa foto: piring untuk makanan, latte art untuk minuman.
     */
    public function getArtAttribute(): string
    {
        $slug = $this->category?->slug ?? '';

        if (str_contains($slug, 'camilan') || str_contains($slug, 'makan')) {
            return 'plate';
        }

        return ['heart', 'rosetta', 'tulip'][$this->id % 3];
    }
}
