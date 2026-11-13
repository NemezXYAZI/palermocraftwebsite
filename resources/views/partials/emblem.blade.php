{{--
    Око Безодні: центральне око з вертикальною зіницею (Каель — Дракон Енду),
    навколо — дванадцять Очей трьох доменів. Один екземпляр на сторінку (id градієнтів).
--}}
@php
    $eyeColors = ['#f2a93b', '#f2a93b', '#f2a93b', '#f2a93b', '#7ccb8f', '#7ccb8f', '#7ccb8f', '#7ccb8f', '#8fd3ff', '#8fd3ff', '#8fd3ff', '#8fd3ff'];
    $label = $label ?? 'Око Безодні в колі дванадцяти Очей';
@endphp
<svg class="emblem {{ $class ?? '' }}" viewBox="0 0 400 400" role="img" aria-label="{{ $label }}">
    <defs>
        <radialGradient id="em-halo" cx="50%" cy="50%" r="50%">
            <stop offset="0" stop-color="#7b5cff" stop-opacity=".42"/>
            <stop offset=".5" stop-color="#4a2fb0" stop-opacity=".14"/>
            <stop offset="1" stop-color="#000" stop-opacity="0"/>
        </radialGradient>
        <linearGradient id="em-gold" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#f8e2a8"/>
            <stop offset=".5" stop-color="#e2b25d"/>
            <stop offset="1" stop-color="#a8712a"/>
        </linearGradient>
        <radialGradient id="em-iris" cx="50%" cy="45%" r="55%">
            <stop offset="0" stop-color="#f1e8ff"/>
            <stop offset=".3" stop-color="#b9a0ff"/>
            <stop offset=".7" stop-color="#5d3ddc"/>
            <stop offset="1" stop-color="#1b0f44"/>
        </radialGradient>
        <radialGradient id="em-sclera" cx="50%" cy="50%" r="60%">
            <stop offset="0" stop-color="#1e1538"/>
            <stop offset="1" stop-color="#06040b"/>
        </radialGradient>
        <filter id="em-glow" x="-60%" y="-60%" width="220%" height="220%">
            <feGaussianBlur stdDeviation="5" result="blur"/>
            <feMerge><feMergeNode in="blur"/><feMergeNode in="SourceGraphic"/></feMerge>
        </filter>
        <filter id="em-soft" x="-50%" y="-50%" width="200%" height="200%">
            <feGaussianBlur stdDeviation="1.6"/>
        </filter>
    </defs>

    <circle class="emblem__halo" cx="200" cy="200" r="200" fill="url(#em-halo)"/>

    {{-- Зовнішнє кільце з дванадцятьма Очима --}}
    <g class="emblem__spin">
        <circle cx="200" cy="200" r="184" fill="none" stroke="url(#em-gold)" stroke-opacity=".5" stroke-width="1"/>
        <circle cx="200" cy="200" r="164" fill="none" stroke="#e2b25d" stroke-opacity=".28" stroke-width="1" stroke-dasharray="1 6"/>
        @foreach ($eyeColors as $i => $color)
            <g transform="rotate({{ $i * 30 }} 200 200)">
                <g transform="translate(200 26)">
                    <path d="M-12 0 Q0 -9 12 0 Q0 9 -12 0Z" fill="#0a0713" stroke="{{ $color }}" stroke-width="1.5"/>
                    <circle r="3.4" fill="{{ $color }}" filter="url(#em-soft)" opacity=".85"/>
                    <circle r="1.6" fill="#fff8e6"/>
                </g>
                <path d="M200 46 L200 58" stroke="{{ $color }}" stroke-opacity=".35" stroke-width="1"/>
            </g>
        @endforeach
    </g>

    {{-- Середнє кільце з рунічними насічками, обертається назустріч --}}
    <g class="emblem__spin emblem__spin--rev">
        <circle cx="200" cy="200" r="140" fill="none" stroke="url(#em-gold)" stroke-opacity=".45" stroke-width="1.2"/>
        <circle cx="200" cy="200" r="128" fill="none" stroke="#e2b25d" stroke-opacity=".16" stroke-width="6"/>
        @for ($i = 0; $i < 48; $i++)
            <line x1="200" y1="{{ $i % 4 === 0 ? 54 : 58 }}" x2="200" y2="{{ $i % 4 === 0 ? 70 : 64 }}"
                  transform="rotate({{ $i * 7.5 }} 200 200)" stroke="#e2b25d" stroke-opacity="{{ $i % 4 === 0 ? '.6' : '.3' }}" stroke-width="1"/>
        @endfor
        @foreach ([0, 120, 240] as $angle)
            <rect x="195" y="55" width="10" height="10" transform="rotate({{ $angle }} 200 200) rotate(45 200 60)" fill="#0a0713" stroke="url(#em-gold)" stroke-width="1.2"/>
        @endforeach
    </g>

    {{-- Центральне око --}}
    <circle cx="200" cy="200" r="104" fill="none" stroke="#9c82ff" stroke-opacity=".22" stroke-width="1"/>
    <path d="M84 200 C 136 124, 264 124, 316 200 C 264 276, 136 276, 84 200 Z" fill="url(#em-sclera)" stroke="url(#em-gold)" stroke-width="2.2"/>
    <path d="M100 200 C 148 140, 252 140, 300 200" fill="none" stroke="#f8e2a8" stroke-opacity=".18" stroke-width="1"/>
    <g filter="url(#em-glow)">
        <circle class="emblem__iris" cx="200" cy="200" r="47" fill="url(#em-iris)"/>
    </g>
    <circle cx="200" cy="200" r="47" fill="none" stroke="#f1e8ff" stroke-opacity=".35" stroke-width="1"/>
    <ellipse cx="200" cy="200" rx="7.5" ry="38" fill="#05030b"/>
    <ellipse cx="200" cy="200" rx="2.2" ry="30" fill="#2a1a5c" opacity=".8"/>
    <circle cx="184" cy="184" r="5.5" fill="#fff" opacity=".75"/>
    <circle cx="217" cy="214" r="2.2" fill="#fff" opacity=".4"/>
</svg>
