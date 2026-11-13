@php
    $discord = config('palermo.community.discord');
    $telegram = config('palermo.community.telegram');
    $bot = config('services.telegram.bot_username');
    $address = trim((string) config('palermo.server_address'));
@endphp
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="{{ route('home') }}" class="brand" aria-label="{{ __('PALERMOCRAFT — на головну') }}">
                    @include('partials.mark')
                    <span class="brand__name">PALERMOCRAFT</span>
                </a>
                <p class="footer-about">
                    {{ __('Сезон I «Ехо Безодні» — сюжетний SMP на Minecraft Java :version.', ['version' => config('palermo.game_version')]) }}
                    {{ __('Поселення з жителями, підземелля й Повелителі.') }}
                </p>
                @if ($address !== '')
                    <button type="button" class="btn btn--outline btn--sm" data-copy="{{ $address }}" data-copied="{{ __('IP скопійовано') }}">
                        <x-icon name="copy" />
                        <span data-copy-label translate="no">{{ $address }}</span>
                    </button>
                @else
                    <p class="footer-soon"><x-icon name="clock" /> {{ __('Адресу сервера оголосимо перед стартом сезону') }}</p>
                @endif
            </div>

            <div>
                <p class="footer-title">{{ __('Гра') }}</p>
                <ul class="footer-links">
                    <li><a href="{{ route('world') }}">{{ __('Світ сезону') }}</a></li>
                    <li><a href="{{ route('guide') }}">{{ __('Путівник') }}</a></li>
                    <li><a href="{{ route('mods') }}">{{ __('Моди') }}</a></li>
                    <li><a href="{{ route('rules') }}">{{ __('Правила') }}</a></li>
                    <li><a href="{{ route('news.index') }}">{{ __('Новини') }}</a></li>
                    <li><a href="{{ route('support') }}">{{ __('Підтримка') }}</a></li>
                </ul>
            </div>

            <div>
                <p class="footer-title">{{ __('Акаунт') }}</p>
                <ul class="footer-links">
                    @auth
                        <li><a href="{{ route('profile.index') }}">{{ __('Профіль') }}</a></li>
                        <li><a href="{{ route('profile.skin') }}">{{ __('Мій скін') }}</a></li>
                    @else
                        <li><a href="{{ route('login') }}">{{ __('Увійти') }}</a></li>
                        <li><a href="{{ route('register') }}">{{ __('Створити акаунт') }}</a></li>
                    @endauth
                    <li><a href="{{ route('guide') }}#account">{{ __('Навіщо акаунт') }}</a></li>
                </ul>
            </div>

            <div>
                <p class="footer-title">{{ __('Спільнота') }}</p>
                <ul class="footer-links">
                    @if ($discord)
                        <li><a href="{{ $discord }}" rel="noopener" target="_blank"><x-brand-icon name="discord" /> Discord</a></li>
                    @endif
                    @if ($telegram)
                        <li><a href="{{ $telegram }}" rel="noopener" target="_blank"><x-brand-icon name="telegram" /> Telegram</a></li>
                    @endif
                    @if ($bot)
                        <li><a href="https://t.me/{{ $bot }}" rel="noopener" target="_blank"><x-icon name="shield-check" /> {{ __('Бот входу') }}</a></li>
                    @endif
                    @unless ($discord || $telegram || $bot)
                        <li class="faint">{{ __('Посилання на спільноти зʼявляться перед стартом сезону.') }}</li>
                    @endunless
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© {{ date('Y') }} PALERMOCRAFT · {{ __('Сезон I «Ехо Безодні»') }}</p>
            @include('partials.lang-switch', ['variant' => 'inline'])
            <p>{{ __('Не є офіційним продуктом Minecraft. Не схвалено Mojang чи Microsoft і не повʼязано з ними.') }}</p>
        </div>
    </div>
</footer>
