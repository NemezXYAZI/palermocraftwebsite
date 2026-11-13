{{-- Знак PALERMOCRAFT: око з вертикальною зіницею в кільці дванадцяти Очей --}}
<svg class="{{ $class ?? 'brand__mark' }}" viewBox="0 0 64 64" aria-hidden="true" focusable="false">
    <circle cx="32" cy="32" r="29" fill="#0b0814" stroke="#e5b763" stroke-width="2"/>
    @foreach (['#f2a93b', '#f2a93b', '#f2a93b', '#f2a93b', '#7ccb8f', '#7ccb8f', '#7ccb8f', '#7ccb8f', '#8fd3ff', '#8fd3ff', '#8fd3ff', '#8fd3ff'] as $i => $color)
        <circle cx="32" cy="7.6" r="1.7" fill="{{ $color }}" transform="rotate({{ $i * 30 }} 32 32)"/>
    @endforeach
    <path d="M12 32 C 21 19.5, 43 19.5, 52 32 C 43 44.5, 21 44.5, 12 32 Z" fill="#140d26" stroke="#e5b763" stroke-width="2" stroke-linejoin="round"/>
    <circle cx="32" cy="32" r="8.6" fill="#9c82ff"/>
    <circle cx="32" cy="32" r="8.6" fill="none" stroke="#e7dcff" stroke-opacity=".5" stroke-width=".8"/>
    <ellipse cx="32" cy="32" rx="1.9" ry="7.2" fill="#05030b"/>
    <circle cx="29" cy="29" r="1.5" fill="#fff" opacity=".8"/>
</svg>
