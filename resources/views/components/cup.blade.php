{{-- Ilustrasi cangkir tampak atas (dipakai bila menu belum punya foto).
     art: heart | tulip | rosetta | plate ; foam: warna latte art ; handle: gagang cangkir --}}
@props(['coffee' => '#5A321A', 'crema' => '#D9C2A3', 'foam' => '#F7EBDB', 'art' => 'heart', 'handle' => false])
@php($id = 'c'.\Illuminate\Support\Str::random(6))
<svg viewBox="0 0 200 200" aria-hidden="true" {{ $attributes->merge(['class' => 'cup-art']) }}>
    <defs>
        <radialGradient id="{{ $id }}s" cx="45%" cy="40%" r="65%">
            <stop offset="0" stop-color="#FFFFFF"/><stop offset=".75" stop-color="#F4EFEA"/><stop offset="1" stop-color="#DCD3CB"/>
        </radialGradient>
        <radialGradient id="{{ $id }}k" cx="50%" cy="50%" r="50%">
            <stop offset="0" stop-color="{{ $crema }}"/><stop offset=".55" stop-color="{{ $crema }}" stop-opacity=".85"/><stop offset="1" stop-color="{{ $coffee }}"/>
        </radialGradient>
    </defs>
    @if ($art === 'plate')
        <circle cx="100" cy="100" r="96" fill="url(#{{ $id }}s)"/>
        <circle cx="100" cy="100" r="74" fill="none" stroke="#E6DED6" stroke-width="2"/>
        <g transform="rotate(-18 100 100)">
            <path d="M48 112 C 52 70, 148 70, 152 112 C 140 98, 128 96, 118 104 C 110 94, 90 94, 82 104 C 72 96, 60 98, 48 112 Z" fill="{{ $crema }}"/>
            <path d="M58 110 C 66 82, 134 82, 142 110" fill="none" stroke="{{ $coffee }}" stroke-width="5" stroke-linecap="round" opacity=".55"/>
            <path d="M82 104 C 86 88, 114 88, 118 104" fill="none" stroke="{{ $coffee }}" stroke-width="4" stroke-linecap="round" opacity=".45"/>
        </g>
    @else
        <circle cx="100" cy="100" r="96" fill="url(#{{ $id }}s)"/>
        @if ($handle)
            <rect x="146" y="132" width="46" height="24" rx="12" transform="rotate(38 146 132)" fill="#FAF8F5" stroke="#E2DAD2"/>
        @endif
        <circle cx="100" cy="100" r="70" fill="#FCFBF9" stroke="#E6DFD8" stroke-width="1.5"/>
        <circle cx="100" cy="100" r="58" fill="url(#{{ $id }}k)"/>
        <g fill="{{ $foam }}">
            @if ($art === 'heart')
                <path d="M100 132 C 66 108, 60 84, 76 72 C 88 63, 100 72, 100 82 C 100 72, 112 63, 124 72 C 140 84, 134 108, 100 132 Z" opacity=".95"/>
            @elseif ($art === 'tulip')
                <ellipse cx="100" cy="72" rx="15" ry="11"/>
                <path d="M74 96 C 80 80, 120 80, 126 96 C 116 90, 84 90, 74 96 Z"/>
                <path d="M100 136 C 74 120, 68 104, 80 98 C 90 94, 100 100, 100 106 C 100 100, 110 94, 120 98 C 132 104, 126 120, 100 136 Z"/>
                <path d="M100 66 V 140" stroke="{{ $foam }}" stroke-width="2"/>
            @else
                @foreach ([[128, 30], [118, 27], [108, 24], [98, 21], [88, 18], [78, 14]] as [$cy, $rx])
                    <path d="M{{ 100 - $rx }} {{ $cy }} C {{ 100 - $rx }} {{ $cy - 9 }}, {{ 100 + $rx }} {{ $cy - 9 }}, {{ 100 + $rx }} {{ $cy }}" fill="none" stroke="{{ $foam }}" stroke-width="5" stroke-linecap="round"/>
                @endforeach
                <ellipse cx="100" cy="64" rx="9" ry="8"/>
                <path d="M100 58 V 140" stroke="{{ $foam }}" stroke-width="2.5"/>
            @endif
        </g>
    @endif
</svg>
