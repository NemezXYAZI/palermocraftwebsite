@php
    $discord = config('palermo.community.discord');
    $telegram = config('palermo.community.telegram');
    $bot = config('services.telegram.bot_username');
@endphp
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="{{ route('home') }}" class="brand" aria-label="PALERMOCRAFT — на головну">
                    @include('partials.mark')
                    <span class="brand__name">PALERMOCRAFT</span>
                </a>
                <p class="footer-about">
                    Сезон I «Ехо Безодні» — український сюжетний SMP на Minecraft Java {{ config('palermo.game_version') }}.
                    Поселення, підземелля, Повелителі та дванадцять Очей Безодні.
                </p>
                <button type="button" class="btn btn--outline btn--sm" data-copy="{{ config('palermo.server_address') }}" data-copied="IP скопійовано">
                    <x-icon name="copy" />
                    <span data-copy-label translate="no">{{ config('palermo.server_address') }}</span>
                </button>
            </div>

            <div>
                <p class="footer-title">Гра</p>
                <ul class="footer-links">
                    <li><a href="{{ route('world') }}">Світ сезону</a></li>
                    <li><a href="{{ route('guide') }}">Путівник</a></li>
                    <li><a href="{{ route('rules') }}">Правила</a></li>
                    <li><a href="{{ route('news.index') }}">Новини</a></li>
                    <li><a href="{{ route('support') }}">Підтримка</a></li>
                </ul>
            </div>

            <div>
                <p class="footer-title">Акаунт</p>
                <ul class="footer-links">
                    @auth
                        <li><a href="{{ route('profile.index') }}">Профіль</a></li>
                        <li><a href="{{ route('profile.skin') }}">Мій скін</a></li>
                    @else
                        <li><a href="{{ route('login') }}">Увійти</a></li>
                        <li><a href="{{ route('register') }}">Створити акаунт</a></li>
                    @endauth
                    <li><a href="{{ route('guide') }}#account">Навіщо акаунт</a></li>
                </ul>
            </div>

            <div>
                <p class="footer-title">Спільнота</p>
                <ul class="footer-links">
                    @if ($discord)
                        <li><a href="{{ $discord }}" rel="noopener" target="_blank"><x-brand-icon name="discord" /> Discord</a></li>
                    @endif
                    @if ($telegram)
                        <li><a href="{{ $telegram }}" rel="noopener" target="_blank"><x-brand-icon name="telegram" /> Telegram</a></li>
                    @endif
                    @if ($bot)
                        <li><a href="https://t.me/{{ $bot }}" rel="noopener" target="_blank"><x-icon name="shield-check" /> Бот входу</a></li>
                    @endif
                    @unless ($discord || $telegram || $bot)
                        <li class="faint">Посилання на спільноти зʼявляться перед стартом сезону.</li>
                    @endunless
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© {{ date('Y') }} PALERMOCRAFT · Сезон I «Ехо Безодні»</p>
            <p>Не є офіційним продуктом Minecraft. Не схвалено Mojang чи Microsoft і не повʼязано з ними.</p>
        </div>
    </div>
</footer>
