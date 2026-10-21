@if ($errors->any())
    <div class="alert" role="alert">Periksa lagi isian yang ditandai.</div>
@endif

<div class="panel">
    <div class="form-grid">
        <label class="field full" for="name">Nama menu
            <input id="name" name="name" value="{{ old('name', $menu->name) }}" maxlength="100" required>
            @error('name')<span class="field-error">{{ $message }}</span>@enderror
        </label>

        <label class="field" for="category_id">Kategori
            <select id="category_id" name="category_id" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $menu->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')<span class="field-error">{{ $message }}</span>@enderror
        </label>

        <label class="field" for="price">Harga (Rp)
            <input id="price" name="price" type="number" min="0" step="500" value="{{ old('price', $menu->price) }}" required>
            @error('price')<span class="field-error">{{ $message }}</span>@enderror
        </label>

        <label class="field full" for="description">Deskripsi
            <textarea id="description" name="description" maxlength="500" required>{{ old('description', $menu->description) }}</textarea>
            @error('description')<span class="field-error">{{ $message }}</span>@enderror
        </label>

        <label class="field" for="tag">Tag (opsional)
            <input id="tag" name="tag" value="{{ old('tag', $menu->tag) }}" maxlength="30" placeholder="mis. Terlaris">
            @error('tag')<span class="field-error">{{ $message }}</span>@enderror
        </label>

        <label class="field" for="photo">Foto (opsional, JPG/PNG/WebP, maks. 2 MB)
            <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp">
            @error('photo')<span class="field-error">{{ $message }}</span>@enderror
        </label>

        @if ($menu->photo)
            <div class="field full">
                <img src="{{ $menu->photo_url }}" alt="Foto {{ $menu->name }}" style="max-width:200px;border-radius:14px">
                <label class="check"><input type="checkbox" name="remove_photo" value="1"> Hapus foto ini</label>
            </div>
        @endif

        <label class="field" for="color_light">Warna terang (dipakai bila tanpa foto)
            <input id="color_light" name="color_light" type="color" value="{{ old('color_light', $menu->color_light) }}">
            @error('color_light')<span class="field-error">{{ $message }}</span>@enderror
        </label>

        <label class="field" for="color_dark">Warna gelap
            <input id="color_dark" name="color_dark" type="color" value="{{ old('color_dark', $menu->color_dark) }}">
            @error('color_dark')<span class="field-error">{{ $message }}</span>@enderror
        </label>

        <label class="check full">
            <input type="checkbox" name="is_available" value="1" @checked(old('is_available', $menu->is_available))> Tersedia (tampil bisa dipesan)
        </label>
    </div>
</div>

<div class="actions">
    <button type="submit" class="btn primary">Simpan</button>
    <a class="btn ghost" href="{{ route('admin.menus.index') }}">Batal</a>
</div>
