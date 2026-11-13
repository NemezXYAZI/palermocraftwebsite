@php
    $discordEnabled = filled(config('services.discord.client_id'));
    $telegramBot = config('services.telegram.bot_username');
@endphp
@if ($discordEnabled || $telegramBot)
    <div class="social-login">
        @if ($discordEnabled)
            <a href="{{ route('auth.discord.redirect') }}" class="btn btn--discord btn--lg btn--block">
                <x-brand-icon name="discord" /> {{ $discordLabel ?? __('Увійти через Discord') }}
            </a>
        @endif
        @if ($telegramBot)
            <div class="telegram-widget">
                <script async src="https://telegram.org/js/telegram-widget.js?22"
                        data-telegram-login="{{ $telegramBot }}"
                        data-size="large"
                        data-radius="10"
                        data-auth-url="{{ route('auth.telegram.callback') }}"
                        data-request-access="write"></script>
            </div>
        @endif
    </div>
    <p class="help text-center mt-0">{{ $hint ?? __('Працює, якщо Discord чи Telegram уже привʼязано в профілі.') }}</p>
    <p class="divider-text">{{ __('або через email') }}</p>
@endif
