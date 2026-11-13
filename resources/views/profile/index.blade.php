@extends('layouts.app')

@section('title', __('Профіль'))
@section('noindex', true)

@php
    $skin = $user->skin;
    $avatar = $skin ? route('skins.preview', $skin).'?v='.$skin->hash : null;
    $displayName = $user->minecraft_name ?: $user->name;
    $roleLabels = ['player' => __('Гравець'), 'moderator' => __('Модератор'), 'admin' => __('Адміністратор')];
    $steps = [
        ['done' => filled($user->minecraft_name), 'title' => __('Привʼяжи нік у грі'), 'text' => __('Отримай код і напиши в грі /sitelink КОД.'), 'href' => '#game'],
        ['done' => filled($user->telegram_id) || filled($user->discord_id), 'title' => __('Підключи Telegram або Discord'), 'text' => __('Бот підтверджуватиме вхід у гру однією кнопкою.'), 'href' => '#connections'],
        ['done' => (bool) $skin, 'title' => __('Завантаж скін'), 'text' => __('Його побачать усі гравці після перевірки.'), 'href' => route('profile.skin')],
    ];
    $done = collect($steps)->where('done', true)->count();
    $discordEnabled = filled(config('services.discord.client_id'));
    $telegramBot = config('services.telegram.bot_username');
    $skinStatus = match ($skin?->status) {
        'approved' => ['badge--success', __('Схвалено')],
        'pending' => ['badge--warn', __('На перевірці')],
        'rejected' => ['badge--danger', __('Відхилено')],
        default => ['', __('Не завантажено')],
    };
@endphp

@section('content')
    <section class="section section--tight">
        <div class="container">
            @include('partials.flash')

            <div class="card profile-hero">
                <x-head :name="$displayName" :src="$avatar" size="92" />
                <div class="profile-hero__info">
                    <p class="kicker kicker--left">{{ __('Профіль Мандрівника') }}</p>
                    <h1 class="profile-hero__name">{{ $user->name }}</h1>
                    <div class="cluster cluster--sm">
                        <span class="badge badge--gold">{{ $roleLabels[$user->role] ?? __('Гравець') }}</span>
                        @if ($user->minecraft_name)
                            <span class="badge badge--success"><x-icon name="gamepad-2" /> {{ $user->minecraft_name }}</span>
                        @else
                            <span class="badge badge--warn"><x-icon name="unlink" /> {{ __('Нік не привʼязано') }}</span>
                        @endif
                        @if ($user->telegram_id)
                            <span class="badge"><x-brand-icon name="telegram" /> Telegram</span>
                        @endif
                        @if ($user->discord_id)
                            <span class="badge"><x-brand-icon name="discord" /> Discord</span>
                        @endif
                        <span class="badge">{{ __('З нами з :date', ['date' => $user->created_at->translatedFormat('F Y')]) }}</span>
                    </div>
                </div>
                <div class="cluster">
                    <a class="btn btn--outline" href="{{ route('profile.skin') }}"><x-icon name="shirt" /> {{ __('Мій скін') }}</a>
                    @if ($user->isStaff())
                        <a class="btn btn--ghost" href="{{ route('admin.dashboard') }}"><x-icon name="shield" /> {{ __('Адмін-панель') }}</a>
                    @endif
                </div>
            </div>

            <div class="profile-layout mt-lg">
                <div class="stack stack--lg">
                    {{-- Ігровий акаунт --}}
                    <section class="card" id="game" aria-labelledby="game-title">
                        <h2 class="panel-title" id="game-title"><x-icon name="gamepad-2" /> {{ __('Акаунт у грі') }}</h2>
                        @if ($user->minecraft_name)
                            <div class="connection mt-lg">
                                <x-head :name="$user->minecraft_name" :src="$avatar" size="44" />
                                <div class="connection__info">
                                    <strong>{{ $user->minecraft_name }}</strong>
                                    <span>{{ __('Привʼязано :date. Скін і вхід працюють для цього ніку.', ['date' => $user->minecraft_linked_at?->translatedFormat('j F Y')]) }}</span>
                                </div>
                                <form method="POST" action="{{ route('profile.minecraft.unlink') }}">
                                    @csrf
                                    <button type="submit" class="btn btn--danger btn--sm" data-confirm="{{ __('Точно відвʼязати?') }}"><x-icon name="unlink" /> {{ __('Відвʼязати') }}</button>
                                </form>
                            </div>
                        @else
                            <p class="muted">{{ __('Привʼяжи нік, щоб скін із сайту зʼявився в грі, а Telegram і Discord працювали для входу. Підходить будь-який лаунчер — офіційний чи піратський.') }}</p>
                            @if (session('link_code'))
                                <p class="label">{{ __('Зайди на сервер і напиши в чат — код діє :minutes хв:', ['minutes' => config('palermo.link_code_ttl_minutes')]) }}</p>
                                <div class="code-box">
                                    <code translate="no">/sitelink {{ session('link_code') }}</code>
                                    <button type="button" class="btn btn--outline btn--sm" data-copy="/sitelink {{ session('link_code') }}">
                                        <x-icon name="copy" /> <span data-copy-label>{{ __('Копіювати') }}</span>
                                    </button>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('profile.minecraft.code') }}" class="mt-lg">
                                @csrf
                                <button type="submit" class="btn {{ session('link_code') ? 'btn--ghost' : 'btn--primary' }}">
                                    <x-icon name="key-round" /> {{ session('link_code') ? __('Отримати новий код') : __('Отримати код привʼязки') }}
                                </button>
                            </form>
                        @endif
                    </section>

                    {{-- Месенджери --}}
                    <section class="card" id="connections" aria-labelledby="connections-title">
                        <h2 class="panel-title" id="connections-title"><x-icon name="shield-check" /> {{ __('Вхід без пароля') }}</h2>
                        <p class="muted">{{ __('Бот надсилатиме кнопку «Впустити» при кожному вході в гру, а на сайт можна буде заходити одним кліком.') }}</p>

                        <div class="stack">
                            <div class="connection card card--flat">
                                <span class="connection__logo connection__logo--discord"><x-brand-icon name="discord" /></span>
                                <div class="connection__info">
                                    <strong>Discord</strong>
                                    @if ($user->discord_id)
                                        <span>{{ $user->discord_username }} · {{ __('привʼязано :date', ['date' => $user->discord_linked_at?->translatedFormat('j F Y')]) }}</span>
                                    @else
                                        <span>{{ $discordEnabled ? __('Не привʼязано') : __('Ще не налаштовано адміністрацією') }}</span>
                                    @endif
                                </div>
                                @if ($user->discord_id)
                                    <form method="POST" action="{{ route('profile.discord.unlink') }}">
                                        @csrf
                                        <button type="submit" class="btn btn--danger btn--sm" data-confirm="{{ __('Точно відвʼязати?') }}">{{ __('Відвʼязати') }}</button>
                                    </form>
                                @elseif ($discordEnabled)
                                    <a href="{{ route('auth.discord.redirect') }}" class="btn btn--discord btn--sm"><x-brand-icon name="discord" /> {{ __('Привʼязати') }}</a>
                                @endif
                            </div>

                            <div class="connection card card--flat">
                                <span class="connection__logo connection__logo--telegram"><x-brand-icon name="telegram" /></span>
                                <div class="connection__info">
                                    <strong>Telegram</strong>
                                    @if ($user->telegram_id)
                                        <span>{{ $user->telegram_username ? '@'.$user->telegram_username : __('Акаунт Telegram') }} · {{ __('привʼязано :date', ['date' => $user->telegram_linked_at?->translatedFormat('j F Y')]) }}</span>
                                    @else
                                        <span>{{ $telegramBot ? __('Не привʼязано') : __('Ще не налаштовано адміністрацією') }}</span>
                                    @endif
                                </div>
                                @if ($user->telegram_id)
                                    <form method="POST" action="{{ route('profile.telegram.unlink') }}">
                                        @csrf
                                        <button type="submit" class="btn btn--danger btn--sm" data-confirm="{{ __('Точно відвʼязати?') }}">{{ __('Відвʼязати') }}</button>
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
                        <h2 class="panel-title" id="settings-title"><x-icon name="settings" /> {{ __('Налаштування акаунта') }}</h2>
                        <div class="grid grid--2 mt-lg settings-grid">
                            <form method="POST" action="{{ route('profile.username.update') }}" class="form">
                                @csrf
                                <h3 class="h4 mb-0">{{ __('Імʼя на сайті') }}</h3>
                                <div class="field">
                                    <label class="label" for="name">{{ __('Нове імʼя') }}</label>
                                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required minlength="3" maxlength="32" autocomplete="username"
                                           @class(['input', 'is-invalid' => $errors->has('name')])>
                                </div>
                                <div><button type="submit" class="btn btn--outline">{{ __('Зберегти') }}</button></div>
                            </form>

                            <form method="POST" action="{{ route('profile.password.update') }}" class="form">
                                @csrf
                                <h3 class="h4 mb-0">{{ __('Пароль') }}</h3>
                                <div class="field">
                                    <label class="label" for="current_password">{{ __('Поточний пароль') }}</label>
                                    <input id="current_password" type="password" name="current_password" required autocomplete="current-password"
                                           @class(['input', 'is-invalid' => $errors->has('current_password')])>
                                </div>
                                <div class="field">
                                    <label class="label" for="password">{{ __('Новий пароль') }}</label>
                                    <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password"
                                           @class(['input', 'is-invalid' => $errors->has('password')])>
                                </div>
                                <div class="field">
                                    <label class="label" for="password_confirmation">{{ __('Ще раз') }}</label>
                                    <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password" class="input">
                                </div>
                                <div><button type="submit" class="btn btn--outline">{{ __('Змінити пароль') }}</button></div>
                            </form>
                        </div>
                    </section>
                </div>

                <aside class="stack">
                    <section class="card" aria-labelledby="steps-title">
                        <h2 class="panel-title" id="steps-title"><x-icon name="footprints" /> {{ __('Перші кроки') }}</h2>
                        <div class="progress mt-lg" role="progressbar" aria-valuemin="0" aria-valuemax="3" aria-valuenow="{{ $done }}" aria-label="{{ __('Виконано кроків') }}">
                            <div class="progress__bar" style="width: {{ round($done / 3 * 100) }}%"></div>
                        </div>
                        <p class="faint" style="margin: 10px 0 16px">{{ $done === 3 ? __('Усе готово — побачимося в грі!') : __(':done з 3 виконано', ['done' => $done]) }}</p>
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
                            <h2 class="panel-title mb-0" id="skin-title"><x-icon name="shirt" /> {{ __('Скін') }}</h2>
                            <span class="badge {{ $skinStatus[0] }}">{{ $skinStatus[1] }}</span>
                        </div>
                        <div class="cluster">
                            <x-head :name="$displayName" :src="$avatar" size="56" />
                            <p class="muted mb-0" style="flex: 1 1 160px">
                                @if (! $skin)
                                    {{ __('Поки що в грі стандартний вигляд.') }}
                                @elseif ($skin->status === 'rejected')
                                    {{ $skin->rejection_reason ?: __('Модератор відхилив скін.') }}
                                @elseif ($skin->status === 'pending')
                                    {{ __('Модератор перевірить його найближчим часом.') }}
                                @else
                                    {{ __('Модель: :model.', ['model' => $skin->model === 'slim' ? __('Alex (тонкі руки)') : __('Steve (класична)')]) }}
                                @endif
                            </p>
                        </div>
                        <a class="btn btn--outline btn--block mt-lg" href="{{ route('profile.skin') }}">{{ $skin ? __('Змінити скін') : __('Завантажити скін') }}</a>
                    </section>

                    <section class="card card--flat" aria-label="{{ __('Дані акаунта') }}">
                        <dl class="kv">
                            <dt>Email</dt>
                            <dd>{{ $user->email }}</dd>
                            <dt>{{ __('Реєстрація') }}</dt>
                            <dd>{{ $user->created_at->translatedFormat('j F Y') }}</dd>
                        </dl>
                    </section>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn--ghost btn--block"><x-icon name="log-out" /> {{ __('Вийти з акаунта') }}</button>
                    </form>
                </aside>
            </div>
        </div>
    </section>
@endsection
