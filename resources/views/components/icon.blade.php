{{-- Ikon garis sederhana (stroke mengikuti warna teks). --}}
@props(['name'])
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" {{ $attributes->merge(['class' => 'ico']) }}>
    @switch($name)
        @case('cup')
            <path d="M4 9h12v4a5 5 0 0 1-5 5H9a5 5 0 0 1-5-5V9Z"/><path d="M16 10h1.5a2.5 2.5 0 0 1 0 5H16"/><path d="M3 21h15"/><path d="M8 3c-.8 1 .8 2 0 3M12 3c-.8 1 .8 2 0 3"/>
            @break
        @case('dripper')
            <path d="M5 6h14l-4 8H9L5 6Z"/><path d="M9 14v2h6v-2"/><path d="M7 20h10"/><path d="M12 16v4"/><path d="M8 3h8"/>
            @break
        @case('glass')
            <path d="M6 4h12l-1.5 15a2 2 0 0 1-2 2h-5a2 2 0 0 1-2-2L6 4Z"/><path d="M6.6 10h10.8"/><path d="M14 2l-1 8"/>
            @break
        @case('croissant')
            <path d="M4 15c0-5 4-9 8-9s8 4 8 9c-2-1.5-4-1.5-5.5 0-1-2-4-2-5 0C8 13.5 6 13.5 4 15Z"/><path d="M9 9.5l1 4M15 9.5l-1 4"/>
            @break
        @case('bean')
            <ellipse cx="12" cy="12" rx="6" ry="8.5" transform="rotate(30 12 12)"/><path d="M9 6c3 3 0 9 6 12"/>
            @break
        @case('cart')
            <path d="M3 4h2l2.2 10.5a1.5 1.5 0 0 0 1.5 1.2h8.6a1.5 1.5 0 0 0 1.5-1.1L20.5 8H6"/><circle cx="9.5" cy="19.5" r="1.3"/><circle cx="17" cy="19.5" r="1.3"/>
            @break
        @case('plus')
            <path d="M12 5v14M5 12h14"/>
            @break
        @case('arrow')
            <path d="M5 12h14M13 6l6 6-6 6"/>
            @break
        @case('leaf')
            <path d="M5 19c0-8 5-14 15-14 0 10-6 15-14 15"/><path d="M5 19l7-7"/>
            @break
        @case('flame')
            <path d="M12 3c1 4 6 5.5 6 11a6 6 0 0 1-12 0c0-3 2-4.5 2-7 2 1 3 2.5 3 4 1-2 1-5 1-8Z"/>
            @break
        @case('wifi')
            <path d="M2.5 9a14 14 0 0 1 19 0M5.5 12.5a9.5 9.5 0 0 1 13 0M8.5 16a5 5 0 0 1 7 0"/><circle cx="12" cy="19" r=".8" fill="currentColor"/>
            @break
        @case('chat')
            <path d="M4 18.5V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H8l-4 3.5Z"/><path d="M8 9h8M8 12.5h5"/>
            @break
        @case('clock')
            <circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>
            @break
        @case('grid')
            <rect x="3.5" y="3.5" width="7" height="7" rx="2"/><rect x="13.5" y="3.5" width="7" height="7" rx="2"/><rect x="3.5" y="13.5" width="7" height="7" rx="2"/><rect x="13.5" y="13.5" width="7" height="7" rx="2"/>
            @break
        @case('receipt')
            <path d="M6 3h12v18l-3-2-3 2-3-2-3 2V3Z"/><path d="M9 8h6M9 12h6M9 16h3"/>
            @break
        @case('tag')
            <path d="M3.5 12.5V4.5a1 1 0 0 1 1-1h8l8 8a1.5 1.5 0 0 1 0 2.1l-6.9 6.9a1.5 1.5 0 0 1-2.1 0l-8-8Z"/><circle cx="8" cy="8" r="1.3"/>
            @break
        @case('external')
            <path d="M14 4h6v6M20 4l-9 9"/><path d="M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/>
            @break
        @case('logout')
            <path d="M15 4h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-3"/><path d="M10 16l-4-4 4-4M6 12h10"/>
            @break
        @case('wallet')
            <path d="M4 7a2 2 0 0 1 2-2h11v4"/><rect x="3.5" y="7" width="17" height="12.5" rx="2"/><path d="M16 13.2h2"/>
            @break
        @case('bell')
            <path d="M6 16V11a6 6 0 0 1 12 0v5l1.5 2h-15L6 16Z"/><path d="M10 20.5a2 2 0 0 0 4 0"/>
            @break
        @case('search')
            <circle cx="11" cy="11" r="6.5"/><path d="M16 16l4.5 4.5"/>
            @break
        @case('pin')
            <path d="M12 21s-6.5-5.5-6.5-11a6.5 6.5 0 0 1 13 0c0 5.5-6.5 11-6.5 11Z"/><circle cx="12" cy="10" r="2.3"/>
            @break
    @endswitch
</svg>
