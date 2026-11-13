{{-- Знаки доменів: sun (Азраель), life (Еліор), frost (Веларіс), library, void (Каель) --}}
<svg class="{{ $class ?? 'domain__sigil' }}" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
    @switch($name)
        @case('sun')
            <circle cx="32" cy="32" r="12"/>
            <circle cx="32" cy="32" r="16.5" stroke-opacity=".45" stroke-dasharray="1.5 3"/>
            @for ($i = 0; $i < 12; $i++)
                <line x1="32" y1="{{ $i % 2 ? 9 : 4 }}" x2="32" y2="13" transform="rotate({{ $i * 30 }} 32 32)"/>
            @endfor
            <path d="M24 32 Q32 25.5 40 32 Q32 38.5 24 32Z" fill="currentColor" fill-opacity=".14"/>
            <circle cx="32" cy="32" r="2.6" fill="currentColor"/>
            @break
        @case('life')
            <path d="M32 6 C 50 15, 53 39, 32 58 C 11 39, 14 15, 32 6 Z" fill="currentColor" fill-opacity=".08"/>
            <path d="M32 12 V 52"/>
            <path d="M32 24 L 24 18 M32 24 L 40 18"/>
            <path d="M32 34 L 22 27 M32 34 L 42 27"/>
            <path d="M32 44 L 25 39 M32 44 L 39 39"/>
            <circle cx="24" cy="18" r="1.8" fill="currentColor"/>
            <circle cx="40" cy="18" r="1.8" fill="currentColor"/>
            <circle cx="22" cy="27" r="1.8" fill="currentColor"/>
            <circle cx="42" cy="27" r="1.8" fill="currentColor"/>
            <rect x="29.5" y="49.5" width="5" height="5" transform="rotate(45 32 52)" fill="currentColor" fill-opacity=".35"/>
            @break
        @case('frost')
            @for ($i = 0; $i < 6; $i++)
                <g transform="rotate({{ $i * 60 }} 32 32)">
                    <line x1="32" y1="5" x2="32" y2="20"/>
                    <path d="M27.5 9.5 L32 13 L36.5 9.5"/>
                </g>
            @endfor
            <path d="M25 22 H 39 L 32 32 L 39 42 H 25 L 32 32 Z" fill="currentColor" fill-opacity=".1"/>
            <path d="M28.5 40 H 35.5 L 32 35.5 Z" fill="currentColor" fill-opacity=".55" stroke-width="1"/>
            @break
        @case('library')
            <path d="M8 46 V 22 C 17 19, 25 20, 32 25 C 39 20, 47 19, 56 22 V 46 C 47 43, 39 44, 32 49 C 25 44, 17 43, 8 46 Z" fill="currentColor" fill-opacity=".07"/>
            <path d="M32 25 V 49"/>
            <path d="M24 12 Q32 5.5 40 12 Q32 18.5 24 12Z"/>
            <circle cx="32" cy="12" r="2.2" fill="currentColor"/>
            <path d="M14 29 C 19 28, 23 28.5, 27 30.5 M14 35 C 19 34, 23 34.5, 27 36.5 M50 29 C 45 28, 41 28.5, 37 30.5 M50 35 C 45 34, 41 34.5, 37 36.5" stroke-opacity=".55"/>
            @break
        @case('void')
            <circle cx="32" cy="32" r="22" stroke-opacity=".35"/>
            <path d="M14 32 C 20 21, 44 21, 50 32 C 44 43, 20 43, 14 32 Z" fill="currentColor" fill-opacity=".1"/>
            <ellipse cx="32" cy="32" rx="2.4" ry="9" fill="currentColor"/>
            <ellipse cx="7" cy="32" rx="4.5" ry="2.6"/>
            <ellipse cx="57" cy="32" rx="4.5" ry="2.6"/>
            <ellipse cx="32" cy="7" rx="2.6" ry="4.5"/>
            <ellipse cx="32" cy="57" rx="2.6" ry="4.5"/>
            @break
    @endswitch
</svg>
