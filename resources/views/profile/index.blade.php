@extends('layouts.app')

@section('title', 'Профіль')
@section('noindex', true)

@php
    $skin = $user->skin;
    $avatar = $skin ? route('skins.preview', $skin).'?v='.$skin->hash : null;
    $displayName = $user->minecraft_name ?: $user->name;
    $roleLabels = ['player' => 'Гравець', 'moderator' => 'Модератор', 'admin' => 'Адміністратор'];
    $steps = [
        ['done' => filled($user->minecraft_name), 'title' => 'Привʼяжи нік у грі', 'text' => 'Отримай код і напиши в грі /sitelink КОД.', 'href' => '#game'],
        ['done' => filled($user->telegram_id) || filled($user->discord_id), 'title' => 'Підключи Telegram або Discord', 'text' => 'Бот підтверджуватиме вхід у гру однією кнопкою.', 'href' => '#connections'],
        ['done' => (bool) $skin, 'title' => 'Завантаж скін', 'text' => 'Його побачать усі гравці після перевірки.', 'href' => route('profile.skin')],
    ];
    $done = collect($steps)->where('done', true)->count();
    $discordEnabled = filled(config('services.discord.client_id'));
    $telegramBot = config('services.telegram.bot_username');
    $skinStatus = match ($skin?->status) {
        'approved' => ['badge--success', 'Схвалено'],
        'pending' => ['badge--warn', 'На перевірці'],
        'rejected' => ['badge--danger', 'Відхилено'],
        default => ['', 'Не завантажено'],
    };
@endphp

@section('content')
    <section class="section section--tight">
        <div class="container">
            @include('partials.flash')

            <div class="card profile-hero">
                <x-head :name="$displayName" :src="$avatar" size="92" />
                <div class="profile-hero__info">
                    <p class="kicker kicker--left">Профіль Мандрівника</p>
                    <h1 class="profile-hero__name">{{ $user->name }}</h1>
                    <div class="cluster cluster--sm">
                        <span class="badge badge--gold">{{ $roleLabels[$user->role] ?? 'Гравець' }}</span>
                        @if ($user->minecraft_name)
                            <span class="badge badge--success"><x-icon name="gamepad-2" /> {{ $user->minecraft_name }}</span>
                        @else
                            <span class="badge badge--warn"><x-icon name="unlink" /> Нік не привʼязано</span>
                        @endif
                        @if ($user->telegram_id)
                            <span class="badge"><x-brand-icon name="telegram" /> Telegram</span>
                        @endif
                        @if ($user->discord_id)
                            <span class="badge"><x-brand-icon name="discord" /> Discord</span>
                        @endif
                        <span class="badge">З нами з {{ $user->created_at->translatedFormat('F Y') }}</span>
                    </div>
                </div>
                <div class="cluster">
                    <a class="btn btn--outline" href="{{ route('profile.skin') }}"><x-icon name="shirt" /> Мій скін</a>
                    @if ($user->isStaff())
                        <a class="btn btn--ghost" href="{{ route('admin.dashboard') }}"><x-icon name="shield" /> Адмін-панель</a>
                    @endif
                </div>
            </div>

            <div class="profile-layout mt-lg">
                <div class="stack stack--lg">
                    {{-- Ігровий акаунт --}}
                    <section class="card" id="game" aria-labelledby="game-title">
                        <h2 class="panel-title" id="game-title"><x-icon name="gamepad-2" /> Акаунт у грі</h2>
                        @if ($user->minecraft_name)
                            <div class="connection mt-lg">
                                <x-head :name="$user->minecraft_name" :src="$avatar" size="44" />
                                <div class="connection__info">
                                    <strong>{{ $user->minecraft_name }}</strong>
                                    <span>Привʼязано {{ $user->minecraft_linked_at?->translatedFormat('j F Y') }}. Скін і вхід працюють для цього ніку.</span>
                                </div>
                                <form method="POST" action="{{ route('profile.minecraft.unlink') }}">
                                    @csrf
                                    <button type="submit" class="btn btn--danger btn--sm" data-confirm="Точно відвʼязати?"><x-icon name="unlink" /> Відвʼязати</button>
                                </form>
                            </div>
                        @else
                            <p class="muted">Привʼяжи нік, щоб скін із сайту зʼявився в грі, а Telegram і Discord працювали для входу. Підходить будь-який лаунчер — офіційний чи піратський.</p>
                            @if (session('link_code'))
                                <p class="label">Зайди на сервер і напиши в чат — код діє {{ config('palermo.link_code_ttl_minutes') }} хв:</p>
                                <div class="code-box">
                                    <code translate="no">/sitelink {{ session('link_code') }}</code>
                                    <button type="button" class="btn btn--outline btn--sm" data-copy="/sitelink {{ session('link_code') }}">
                                        <x-icon name="copy" /> <span data-copy-label>Копіювати</span>
                                    </button>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('profile.minecraft.code') }}" class="mt-lg">
                                @csrf
                                <button type="submit" class="btn {{ session('link_code') ? 'btn--ghost' : 'btn--primary' }}">
                                    <x-icon name="key-round" /> {{ session('link_code') ? 'Отримати новий код' : 'Отримати код привʼязки' }}
                                </button>
                            </form>
                        @endif
                    </section>

                    {{-- Месенджери --}}
                    <section class="card" id="connections" aria-labelledby="connections-title">
                        <h2 class="panel-title" id="connections-title"><x-icon name="shield-check" /> Вхід без пароля</h2>
                        <p class="muted">Бот надсилатиме кнопку «Впустити» при кожному вході в гру, а на сайт можна буде заходити одним кліком.</p>

                        <div class="stack">
                            <div class="connection card card--flat">
                                <span class="connection__logo connection__logo--discord"><x-brand-icon name="discord" /></span>
                                <div class="connection__info">
                                    <strong>Discord</strong>
                                    @if ($user->discord_id)
                                        <span>{{ $user->discord_username }} · привʼязано {{ $user->discord_linked_at?->translatedFormat('j F Y') }}</span>
                                    @else
                                        <span>{{ $discordEnabled ? 'Не привʼязано' : 'Ще не налаштовано адміністрацією' }}</span>
                                    @endif
                                </div>
                                @if ($user->discord_id)
                                    <form method="POST" action="{{ route('profile.discord.unlink') }}">
                                        @csrf
                                        <button type="submit" class="btn btn--danger btn--sm" data-confirm="Точно відвʼязати?">Відвʼязати</button>
                                    </form>
                                @elseif ($discordEnabled)
                                    <a href="{{ route('auth.discord.redirect') }}" class="btn btn--discord btn--sm"><x-brand-icon name="discord" /> Привʼязати</a>
                                @endif
                            </div>

                            <div class="connection card card--flat">
                                <span class="connection__logo connection__logo--telegram"><x-brand-icon name="telegram" /></span>
                                <div class="connection__info">
                                    <strong>Telegram</strong>
                                    @if ($user->telegram_id)
                                        <span>{{ $user->telegram_username ? '@'.$user->telegram_username : 'Акаунт Telegram' }} · привʼязано {{ $user->telegram_linked_at?->translatedFormat('j F Y') }}</span>
                                    @else
                                        <span>{{ $telegramBot ? 'Не привʼязано' : 'Ще не налаштовано адміністрацією' }}</span>
                                    @endif
                                </div>
                                @if ($user->telegram_id)
                                    <form method="POST" action="{{ route('profile.telegram.unlink') }}">
                                        @csrf
                                        <button type="submit" class="btn btn--danger btn--sm" data-confirm="Точно відвʼязати?">Відвʼязати</button>
                                    </form>
                                @elseif ($telegramBot)
                                    <div class="telegram-widget">
                                        <script async src="https://telegram.org/js/telegram-widget.js?22"
                                                data-telegram-login="{{ $telegramBot }}"
                                                data-size="medium"
                                                data-radius="8"
                                                data-auth-url="{{ route('auth.telegram.callback') }}"
                                                data-request-access="write"></script>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    {{-- Налаштування --}}
                    <section class="card" id="settings" aria-labelledby="settings-title">
                        <h2 class="panel-title" id="settings-title"><x-icon name="settings" /> Налаштування акаунта</h2>
                        <div class="grid grid--2 mt-lg settings-grid">
                            <form method="POST" action="{{ route('profile.username.update') }}" class="form">
                                @csrf
                                <h3 class="h4 mb-0">Імʼя на сайті</h3>
                                <div class="field">
                                    <label class="label" for="name">Нове імʼя</label>
                                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required minlength="3" maxlength="32" autocomplete="username"
                                           @class(['input', 'is-invalid' => $errors->has('name')])>
                                </div>
                                <div><button type="submit" class="btn btn--outline">Зберегти</button></div>
                            </form>

                            <form method="POST" action="{{ route('profile.password.update') }}" class="form">
                                @csrf
                                <h3 class="h4 mb-0">Пароль</h3>
                                <div class="field">
                                    <label class="label" for="current_password">Поточний пароль</label>
                                    <input id="current_password" type="password" name="current_password" required autocomplete="current-password"
                                           @class(['input', 'is-invalid' => $errors->has('current_password')])>
                                </div>
                                <div class="field">
                                    <label class="label" for="password">Новий пароль</label>
                                    <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password"
                                           @class(['input', 'is-invalid' => $errors->has('password')])>
                                </div>
                                <div class="field">
                                    <label class="label" for="password_confirmation">Ще раз</label>
                                    <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password" class="input">
                                </div>
                                <div><button type="submit" class="btn btn--outline">Змінити пароль</button></div>
                            </form>
                        </div>
                    </section>
                </div>

                <aside class="stack">
                    <section class="card" aria-labelledby="steps-title">
                        <h2 class="panel-title" id="steps-title"><x-icon name="footprints" /> Перші кроки</h2>
                        <div class="progress mt-lg" role="progressbar" aria-valuemin="0" aria-valuemax="3" aria-valuenow="{{ $done }}" aria-label="Виконано кроків">
                            <div class="progress__bar" style="width: {{ round($done / 3 * 100) }}%"></div>
                        </div>
                        <p class="faint" style="margin: 10px 0 16px">{{ $done === 3 ? 'Усе готово — побачимося в грі!' : $done.' з 3 виконано' }}</p>
                        <ul class="checklist">
                            @foreach ($steps as $step)
                                <li @class(['is-done' => $step['done']])>
                                    <span class="checklist__mark"><x-icon :name="$step['done'] ? 'check' : 'arrow-right'" /></span>
                                    <a href="{{ $step['href'] }}" class="checklist__link">
                                        <strong>{{ $step['title'] }}</strong>
                                        <span>{{ $step['text'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </section>

                    <section class="card" aria-labelledby="skin-title">
                        <div class="card__head">
                            <h2 class="panel-title mb-0" id="skin-title"><x-icon name="shirt" /> Скін</h2>
                            <span class="badge {{ $skinStatus[0] }}">{{ $skinStatus[1] }}</span>
                        </div>
                        <div class="cluster">
                            <x-head :name="$displayName" :src="$avatar" size="56" />
                            <p class="muted mb-0" style="flex: 1 1 160px">
                                @if (! $skin)
                                    Поки що в грі стандартний вигляд.
                                @elseif ($skin->status === 'rejected')
                                    {{ $skin->rejection_reason ?: 'Модератор відхилив скін.' }}
                                @elseif ($skin->status === 'pending')
                                    Модератор перевірить його найближчим часом.
                                @else
                                    Модель: {{ $skin->model === 'slim' ? 'Alex (тонкі руки)' : 'Steve (класична)' }}.
                                @endif
                            </p>
                        </div>
                        <a class="btn btn--outline btn--block mt-lg" href="{{ route('profile.skin') }}">{{ $skin ? 'Змінити скін' : 'Завантажити скін' }}</a>
                    </section>

                    <section class="card card--flat" aria-label="Дані акаунта">
                        <dl class="kv">
                            <dt>Email</dt>
                            <dd>{{ $user->email }}</dd>
                            <dt>Реєстрація</dt>
                            <dd>{{ $user->created_at->translatedFormat('j F Y') }}</dd>
                        </dl>
                    </section>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn--ghost btn--block"><x-icon name="log-out" /> Вийти з акаунта</button>
                    </form>
                </aside>
            </div>
        </div>
    </section>
@endsection
