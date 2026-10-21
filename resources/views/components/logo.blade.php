@props(['href' => route('home'), 'tagline' => 'Diseduh pelan, sejak 2021.'])
<a {{ $attributes->merge(['class' => 'logo', 'href' => $href]) }}>
    <span class="logo-name">Bari Coffee <x-icon name="cup" class="logo-ico" /></span>
    @if ($tagline)<span class="logo-tag">{{ $tagline }}</span>@endif
</a>
