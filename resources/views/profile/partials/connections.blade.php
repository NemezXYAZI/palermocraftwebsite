{{-- Гра, скін і соцмережі. Прив'язки Discord/Telegram підхоплюють 2FA-боти palermo-sync. --}}
<div class="bg-gray-900 border border-palermo-green/30 rounded-2xl p-6 xl:col-span-2">
    <h4 class="font-bold text-palermo-green mb-4">Акаунт у грі</h4>

    @if ($user->minecraft_name)
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <img src="https://mc-heads.net/avatar/{{ $user->minecraft_name }}/48" alt="" class="w-12 h-12 rounded-lg" onerror="this.style.display='none'">
                <div>
                    <p class="font-bold text-lg">{{ $user->minecraft_name }}</p>
                    <p class="text-xs text-palermo-green">Привʼязано: {{ $user->minecraft_linked_at?->format('d.m.Y H:i') }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('profile.skin') }}" class="bg-palermo-green text-black font-bold px-4 py-2 rounded-xl hover:bg-green-500 transition-colors text-sm">Мій скін</a>
                <form method="POST" action="{{ route('profile.minecraft.unlink') }}">
                    @csrf
                    <button type="submit" class="bg-red-900/50 text-red-300 border border-red-800 px-4 py-2 rounded-xl hover:bg-red-900 transition-colors text-sm font-bold">Відвʼязати</button>
                </form>
            </div>
        </div>
    @else
        <p class="text-sm text-gray-400 mb-4">
            Привʼяжіть свій нік, щоб скін із сайту зʼявився в грі, а прив'язки Discord/Telegram працювали для входу.
            Працює з будь-якого лаунчера: офіційного й піратського.
        </p>
        @if (session('link_code'))
            <div class="bg-palermo-dark border border-palermo-green/40 rounded-xl p-4 mb-4">
                <p class="text-sm text-gray-400 mb-2">Зайдіть на сервер і напишіть у чат (код дійсний {{ config('palermo.link_code_ttl_minutes') }} хв):</p>
                <p class="font-mono text-2xl text-palermo-green select-all">/sitelink {{ session('link_code') }}</p>
            </div>
        @endif
        <form method="POST" action="{{ route('profile.minecraft.code') }}">
            @csrf
            <button type="submit" class="bg-palermo-green text-black font-bold px-5 py-3 rounded-xl hover:bg-green-500 transition-colors">
                {{ session('link_code') ? 'Отримати новий код' : 'Отримати код привʼязки' }}
            </button>
        </form>
    @endif
</div>

<div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
    <h4 class="font-bold text-palermo-green mb-4">Discord</h4>
    @if ($user->discord_id)
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="font-bold">{{ $user->discord_username }}</p>
                <p class="text-xs text-palermo-green">Привʼязано: {{ $user->discord_linked_at?->format('d.m.Y H:i') }}</p>
            </div>
            <form method="POST" action="{{ route('profile.discord.unlink') }}">
                @csrf
                <button type="submit" class="bg-red-900/50 text-red-300 border border-red-800 px-4 py-2 rounded-xl hover:bg-red-900 transition-colors text-sm font-bold">Відвʼязати</button>
            </form>
        </div>
    @else
        <p class="text-sm text-gray-400 mb-4">Після привʼязки бот надсилатиме вам кнопку «Впустити» при вході в гру, а на сайт можна буде входити через Discord.</p>
        @if (config('services.discord.client_id'))
            <a href="{{ route('auth.discord.redirect') }}" class="inline-block bg-[#5865F2] hover:bg-[#4752C4] text-white font-bold px-5 py-3 rounded-xl transition-colors">Привʼязати Discord</a>
        @else
            <p class="text-xs text-gray-500">Discord ще не налаштовано адміністратором.</p>
        @endif
    @endif
</div>

<div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
    <h4 class="font-bold text-palermo-green mb-4">Telegram</h4>
    @if ($user->telegram_id)
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="font-bold">{{ $user->telegram_username }}</p>
                <p class="text-xs text-palermo-green">Привʼязано: {{ $user->telegram_linked_at?->format('d.m.Y H:i') }}</p>
            </div>
            <form method="POST" action="{{ route('profile.telegram.unlink') }}">
                @csrf
                <button type="submit" class="bg-red-900/50 text-red-300 border border-red-800 px-4 py-2 rounded-xl hover:bg-red-900 transition-colors text-sm font-bold">Відвʼязати</button>
            </form>
        </div>
    @else
        <p class="text-sm text-gray-400 mb-4">Підтвердження входу в гру через Telegram-бота.</p>
        @if (config('services.telegram.bot_username'))
            <script async src="https://telegram.org/js/telegram-widget.js?22"
                    data-telegram-login="{{ config('services.telegram.bot_username') }}"
                    data-size="large"
                    data-auth-url="{{ route('auth.telegram.callback') }}"
                    data-request-access="write"></script>
        @else
            <p class="text-xs text-gray-500">Telegram ще не налаштовано адміністратором.</p>
        @endif
    @endif
</div>
