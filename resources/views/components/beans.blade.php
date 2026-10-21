{{-- Biji kopi berserakan melingkar (dekorasi). Posisi acak tapi tetap sama tiap render. --}}
@props(['count' => 34, 'inner' => 132, 'outer' => 196, 'seed' => 7])
@php
    mt_srand($seed);
    $beans = [];
    for ($i = 0; $i < $count; $i++) {
        $a = $i / $count * 2 * M_PI + mt_rand(-20, 20) / 100;
        $r = mt_rand($inner, $outer);
        $beans[] = [200 + cos($a) * $r, 200 + sin($a) * $r, mt_rand(0, 360), mt_rand(80, 115) / 100];
    }
    mt_srand();
@endphp
<svg viewBox="0 0 400 400" aria-hidden="true" {{ $attributes->merge(['class' => 'beans']) }}>
    @foreach ($beans as [$x, $y, $rot, $s])
        <g transform="translate({{ round($x, 1) }} {{ round($y, 1) }}) rotate({{ $rot }}) scale({{ $s }})">
            <ellipse rx="9" ry="6.2" fill="#4A2817"/>
            <path d="M-8 0 C -3 -3, 3 3, 8 0" stroke="#24120A" stroke-width="1.6" fill="none"/>
        </g>
    @endforeach
</svg>
