{{-- Логотип у кільцях Безодні: головний екран, сторінки входу.
     Руни — власне «письмо Безодні» в дусі символів біля столу зачарувань: мерехтять на кільці
     й злітаються до лого. Гліфи намальовані тут, це не алфавіт із Minecraft. --}}
@php
    // Гліфи 8×10 одиниць, лише лінії; «h.01» з круглим кінцем лінії — крапка
    $glyphs = [
        'M1 0v6h6M7 3v7',
        'M1 0v10M4.5 2h.01M4.5 5h.01M4.5 8h.01',
        'M0 3h5v7M7.5 0v4',
        'M1 10V4h6M4 1h.01M7 1h.01',
        'M0 0l4 4 4-4M4 4v6M2 8h4',
        'M2 0v10M6 0v4M6 7v3',
        'M0 5h3M5 5h3M4 0v3M4 7v3',
        'M1 0h6L1 5h6L1 10h6',
        'M7 0v10H1M1 2h.01M1 6h.01',
        'M0 10h8M4 10V3M1 0l3 3 3-3',
        'M1 1h6M1 1v4h6M7 5v5',
        'M4 0v4M0 4h8M0 4v3M8 4v3M4 9h.01',
        'M0 0v4h8V0M4 6v4',
        'M2 0l4 5-4 5M7 0h.01M7 10h.01',
        'M1 0v10M1 0l6 4M1 6l6 4',
        'M0 2l4 3 4-3M4 5v5M1.5 10h5',
    ];
    $count = count($glyphs);
@endphp
<div class="hero-logo {{ $class ?? '' }}" aria-hidden="true">
    <svg class="hero-logo__rings" viewBox="0 0 400 400" focusable="false">
        <circle cx="200" cy="200" r="197" fill="none" stroke="#e5b763" stroke-opacity=".22" stroke-width="1"/>
        {{-- Кільце рун: 24 знаки, між ними крапки --}}
        <g class="emblem__spin">
            @for ($i = 0; $i < 24; $i++)
                <path class="rune" d="{{ $glyphs[($i * 5) % $count] }}" transform="rotate({{ $i * 15 }} 200 200) translate(196 11)"
                      style="--d: -{{ (($i * 7) % 24) / 4 }}s"/>
                <circle cx="200" cy="16" r="1" transform="rotate({{ $i * 15 + 7.5 }} 200 200)" fill="#e5b763" fill-opacity=".5"/>
            @endfor
        </g>
        <g class="emblem__spin emblem__spin--rev">
            <circle cx="200" cy="200" r="166" fill="none" stroke="#9c82ff" stroke-opacity=".28" stroke-width="1"/>
            @for ($i = 0; $i < 60; $i++)
                <line x1="200" y1="{{ $i % 5 === 0 ? 29 : 32 }}" x2="200" y2="{{ $i % 5 === 0 ? 39 : 36 }}" transform="rotate({{ $i * 6 }} 200 200)"
                      stroke="#e5b763" stroke-opacity="{{ $i % 5 === 0 ? '.55' : '.22' }}" stroke-width="1"/>
            @endfor
        </g>
        {{-- Руни, що злітаються до лого (як до столу зачарувань) --}}
        <g class="emblem__spin emblem__spin--drift">
            @foreach ([[8, 0], [47, -5.3], [92, -2.2], [131, -8.6], [176, -4.1], [219, -9.4], [258, -1.4], [301, -6.8], [337, -3.3]] as [$angle, $delay])
                <g transform="rotate({{ $angle }} 200 200) translate(196 -18)">
                    <path class="rune rune--drift" d="{{ $glyphs[($loop->index * 3 + 1) % $count] }}" style="--d: {{ $delay }}s"/>
                </g>
            @endforeach
        </g>
    </svg>
    <img class="hero-logo__mark" src="{{ asset('images/logo.svg') }}" alt="" width="320" height="320" decoding="async">
</div>
