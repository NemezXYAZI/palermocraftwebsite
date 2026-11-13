{{-- Логотип у кільцях Безодні: головний екран, сторінки входу --}}
<div class="hero-logo {{ $class ?? '' }}" aria-hidden="true">
    <svg class="hero-logo__rings" viewBox="0 0 400 400" focusable="false">
        <circle cx="200" cy="200" r="197" fill="none" stroke="#e5b763" stroke-opacity=".22" stroke-width="1"/>
        <g class="emblem__spin">
            <circle cx="200" cy="200" r="184" fill="none" stroke="#e5b763" stroke-opacity=".4" stroke-width="1" stroke-dasharray="1 7"/>
            @foreach ([0, 120, 240] as $angle)
                <rect x="195" y="11" width="10" height="10" transform="rotate({{ $angle }} 200 200) rotate(45 200 16)" fill="#0a0713" stroke="#e5b763" stroke-opacity=".85" stroke-width="1.2"/>
            @endforeach
        </g>
        <g class="emblem__spin emblem__spin--rev">
            <circle cx="200" cy="200" r="166" fill="none" stroke="#9c82ff" stroke-opacity=".28" stroke-width="1"/>
            @for ($i = 0; $i < 60; $i++)
                <line x1="200" y1="{{ $i % 5 === 0 ? 29 : 32 }}" x2="200" y2="{{ $i % 5 === 0 ? 39 : 36 }}" transform="rotate({{ $i * 6 }} 200 200)"
                      stroke="#e5b763" stroke-opacity="{{ $i % 5 === 0 ? '.55' : '.22' }}" stroke-width="1"/>
            @endfor
        </g>
    </svg>
    <img class="hero-logo__mark" src="{{ asset('images/logo.svg') }}" alt="" width="320" height="320" decoding="async">
</div>
