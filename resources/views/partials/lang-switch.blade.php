{{-- Перемикач мови: випадне меню в шапці, рядок кнопок у мобільному меню, посилання в підвалі --}}
@use('App\Support\Locale')
@php
    $currentLocale = Locale::current();
    $variant = $variant ?? 'menu';
@endphp
@if ($variant === 'menu')
    <details class="dropdown lang-menu hide-mobile" data-menu>
        <summary class="lang-menu__toggle" aria-label="{{ __('Мова сайту') }}: {{ Locale::SUPPORTED[$currentLocale]['name'] }}">
            <x-icon name="languages" />
            <span>{{ Locale::SUPPORTED[$currentLocale]['short'] }}</span>
        </summary>
        <div class="dropdown__panel">
            @foreach (Locale::SUPPORTED as $code => $lang)
                <a class="menu-link" href="{{ Locale::switchUrl($code) }}" hreflang="{{ $code }}" lang="{{ $code }}"
                   @if ($code === $currentLocale) aria-current="true" @endif>
                    <span class="lang-code">{{ $lang['short'] }}</span> {{ $lang['name'] }}
                </a>
            @endforeach
        </div>
    </details>
@else
    <div @class(['lang-row', 'lang-row--inline' => $variant === 'inline']) role="group" aria-label="{{ __('Мова сайту') }}">
        @foreach (Locale::SUPPORTED as $code => $lang)
            <a href="{{ Locale::switchUrl($code) }}" hreflang="{{ $code }}" lang="{{ $code }}" title="{{ $lang['name'] }}"
               @if ($code === $currentLocale) aria-current="true" @endif>{{ $variant === 'inline' ? $lang['name'] : $lang['short'] }}</a>
        @endforeach
    </div>
@endif
