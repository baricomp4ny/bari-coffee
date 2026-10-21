@if ($menu->photo)
    <img src="{{ $menu->photo_url }}" alt="{{ $menu->name }}" loading="lazy">
@else
    <x-cup :art="$menu->art" :coffee="$menu->color_dark" :crema="$menu->color_light" />
@endif
