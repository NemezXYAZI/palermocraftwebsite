@extends('layouts.app')

@section('title', 'Путівник')
@section('description', 'Як зайти на PALERMOCRAFT: Minecraft Java '.config('palermo.game_version').' з будь-якого лаунчера, вхід без паролів через Telegram чи Discord, акаунт і скін на сайті, поселення, підземелля, команди та відповіді на часті питання.')

@php
    $address = config('palermo.server_address');
    $version = config('palermo.game_version');
    $bot = config('services.telegram.bot_username');
    $buildings = [
        ['icon' => 'landmark', 'name' => 'Ратуша', 'text' => 'Серце поселення: її рівень дорівнює рангу міста.'],
        ['icon' => 'warehouse', 'name' => 'Центральний Склад', 'text' => 'Сюди звозять усе добуте; на ньому ж ресурси на покращення.'],
        ['icon' => 'house', 'name' => 'Житло', 'text' => 'Дає місце новим жителям.'],
        ['icon' => 'trees', 'name' => 'Лісопилка', 'text' => 'Лісоруби заготовляють колоди, дошки й саджанці.'],
        ['icon' => 'wheat', 'name' => 'Ферма', 'text' => 'Пшениця, морква, картопля й хліб для жителів.'],
        ['icon' => 'pickaxe', 'name' => 'Шахта', 'text' => 'Кругляк, вугілля, залізо, мідь і рідкісні мінерали.'],
        ['icon' => 'swords', 'name' => 'Казарма', 'text' => 'Варта для захисту поселення й війн.'],
    ];
@endphp

@section('content')
    <section class="page-hero">
        <div class="container">
            <p class="kicker kicker--left">Путівник Мандрівника</p>
            <h1>Як почати грати</h1>
            <p class="lead">Усе, що треба знати, щоб зайти на сервер і не загубитися в перші дні: вхід, акаунт, поселення, підземелля й команди.</p>
            <nav class="chips-nav mt-lg" aria-label="Розділи путівника">
                <a href="#join">Як зайти</a>
                <a href="#auth">Вхід без паролів</a>
                <a href="#account">Акаунт і скін</a>
                <a href="#colonies">Поселення</a>
                <a href="#adventure">Пригоди</a>
                <a href="#commands">Команди</a>
                <a href="#faq">Питання</a>
            </nav>
        </div>
    </section>

    {{-- Як зайти --}}
    <section class="section section--tight" id="join" aria-labelledby="join-title">
        <div class="container">
            <div class="guide-layout">
                <div>
                    <h2 id="join-title">Як зайти на сервер</h2>
                    <div class="guide-steps">
                        <article class="guide-step">
                            <span class="step__num">1</span>
                            <div>
                                <h3>Запусти Minecraft Java {{ $version }}</h3>
                                <p class="muted">Підійде будь-який лаунчер: офіційний, TLauncher, Prism чи інший. Ліцензія не потрібна, моди теж. Клієнт лишається ванільним — тож гра піде навіть на слабкому компʼютері.</p>
                            </div>
                        </article>
                        <article class="guide-step">
                            <span class="step__num">2</span>
                            <div>
                                <h3>Додай сервер</h3>
                                <p class="muted">У головному меню: «Мережева гра» → «Додати сервер». Назва — будь-яка, адреса:</p>
                                <div class="code-box">
                                    <code translate="no">{{ $address }}</code>
                                    <button type="button" class="btn btn--outline btn--sm" data-copy="{{ $address }}"><x-icon name="copy" /> <span data-copy-label>Копіювати</span></button>
                                </div>
                            </div>
                        </article>
                        <article class="guide-step">
                            <span class="step__num">3</span>
                            <div>
                                <h3>Підтверди, що це ти</h3>
                                <p class="muted">Під час першого входу сервер покаже код. Надішли його нашому боту в Telegram або Discord командою <code>/link КОД</code> — і нік закріпиться за тобою. Як це працює далі — <a href="#auth">трохи нижче</a>.</p>
                            </div>
                        </article>
                        <article class="guide-step">
                            <span class="step__num">4</span>
                            <div>
                                <h3>Обери свій шлях</h3>
                                <p class="muted">Відкрий Кодекс Мандрівника (<code>/book</code>), познайомся зі старійшинами сіл, збери друзів у команду (<code>/team</code>) і засновуй поселення (<code>/lands</code>).</p>
                            </div>
                        </article>
                    </div>
                </div>
                <aside class="guide-aside">
                    <x-ip-card />
                    <div class="card card--flat">
                        <h3 class="panel-title"><x-icon name="monitor" /> Що потрібно</h3>
                        <ul class="check-list">
                            <li><x-icon name="check" /> Minecraft Java Edition {{ $version }}</li>
                            <li><x-icon name="check" /> Будь-який лаунчер</li>
                            <li><x-icon name="check" /> Telegram або Discord для входу</li>
                            <li><x-icon name="x" class="is-no" /> Ліцензія не обовʼязкова</li>
                            <li><x-icon name="x" class="is-no" /> Моди не потрібні</li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    {{-- Вхід без паролів --}}
    <section class="section section--band" id="auth" aria-labelledby="auth-title">
        <div class="container">
            <div class="split">
                <div>
                    <p class="kicker kicker--left">PALERMOSYNC</p>
                    <h2 id="auth-title">Вхід без паролів</h2>
                    <p class="lead">Нам не потрібен твій пароль — і зламати акаунт без доступу до твого телефона неможливо.</p>
                    <ol class="numbered">
                        <li>Ти заходиш на сервер, як завжди.</li>
                        <li>Бот одразу надсилає тобі в Telegram або Discord повідомлення про вхід.</li>
                        <li>Тиснеш «Впустити» — і ти в грі. Не ти? Тисни «Заблокувати».</li>
                    </ol>
                    <p class="muted">Привʼязати Telegram чи Discord можна і в <a href="{{ route('profile.index') }}">профілі на сайті</a> — тоді боти знатимуть тебе ще до першого входу.</p>
                    @if ($bot)
                        <a class="btn btn--telegram" href="https://t.me/{{ $bot }}" target="_blank" rel="noopener"><x-brand-icon name="telegram" /> Відкрити бота в Telegram</a>
                    @endif
                </div>
                <div class="chat-mock" aria-label="Приклад повідомлення бота">
                    <div class="chat-mock__head">
                        <span class="chat-mock__avatar">@include('partials.mark', ['class' => 'chat-mock__logo'])</span>
                        <div>
                            <strong>PALERMOSYNC</strong>
                            <span>бот</span>
                        </div>
                    </div>
                    <div class="chat-mock__bubble">
                        <p><strong>🛡️ Спроба входу на PALERMOCRAFT!</strong></p>
                        <p>👤 <b>Гравець:</b> <code>Мандрівник</code><br>🌐 <b>IP:</b> <code>xx.xx.xx.xx</code></p>
                        <p class="mb-0">Якщо це ви — тисніть «Впустити».</p>
                        <span class="chat-mock__time">щойно</span>
                    </div>
                    <div class="chat-mock__buttons">
                        <span>✅ Впустити</span>
                        <span>❌ Заблокувати</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Акаунт --}}
    <section class="section" id="account" aria-labelledby="account-title">
        <div class="container">
            <x-section-head kicker="Сайт" title="Навіщо акаунт на сайті" title-id="account-title"
                            lead="Грати можна й без нього. Акаунт потрібен, щоб змінити вигляд і керувати входом." />
            <div class="grid grid--3">
                <article class="card feature accent-violet" data-reveal>
                    <span class="icon-tile"><x-icon name="shirt" /></span>
                    <h3 class="card__title">Скін для всіх</h3>
                    <p class="card__text">Завантаж PNG — після перевірки модератором його побачать усі гравці, навіть із піратських лаунчерів і без модів.</p>
                </article>
                <article class="card feature accent-frost" data-reveal style="--delay: .06s">
                    <span class="icon-tile"><x-icon name="shield-check" /></span>
                    <h3 class="card__title">Telegram і Discord</h3>
                    <p class="card__text">Привʼяжи месенджери, щоб бот підтверджував вхід у гру, а на сайт можна було заходити одним кліком.</p>
                </article>
                <article class="card feature accent-gold" data-reveal style="--delay: .12s">
                    <span class="icon-tile"><x-icon name="link" /></span>
                    <h3 class="card__title">Твій нік</h3>
                    <p class="card__text">У профілі отримай код і напиши в грі <code>/sitelink КОД</code> — так сайт знатиме, що нік справді твій.</p>
                </article>
            </div>
            <div class="cluster mt-lg" style="justify-content: center">
                @auth
                    <a class="btn btn--primary" href="{{ route('profile.index') }}"><x-icon name="user-round" /> Відкрити профіль</a>
                @else
                    <a class="btn btn--primary" href="{{ route('register') }}"><x-icon name="user-round" /> Створити акаунт</a>
                    <a class="btn btn--ghost" href="{{ route('login') }}">У мене вже є акаунт</a>
                @endauth
            </div>
        </div>
    </section>

    {{-- Поселення --}}
    <section class="section section--band" id="colonies" aria-labelledby="colonies-title">
        <div class="container">
            <x-section-head kicker="Поселення" title="Від табору до Метрополії" title-id="colonies-title"
                            lead="Захопи землю для своєї команди, зводь споруди за кресленнями й наймай жителів. Вони добувають ресурси на Склад навіть тоді, коли тебе немає в грі." />

            <div class="buildings">
                @foreach ($buildings as $building)
                    <div class="building" data-reveal style="--delay: {{ $loop->index * 0.04 }}s">
                        <span class="icon-tile icon-tile--sm"><x-icon :name="$building['icon']" /></span>
                        <div>
                            <strong>{{ $building['name'] }}</strong>
                            <span>{{ $building['text'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <h3 class="mt-lg">Ранги поселення</h3>
            <p class="muted">Ранг відкриває нові споруди й ліміти. Щоб його підняти, потрібні ресурси, дорогоцінна плата та умови: досить жителів і споруд потрібного рівня.</p>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Ранг</th>
                            <th scope="col">Споруд</th>
                            <th scope="col">Жителів</th>
                            <th scope="col">Чанків</th>
                            <th scope="col">Відкриває</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($season['ranks'] as $rank)
                            <tr>
                                <td><span class="num">{{ $rank['level'] }}</span> <strong>{{ $rank['title'] }}</strong></td>
                                <td>до {{ $rank['buildings'] }}</td>
                                <td>до {{ $rank['workers'] }}</td>
                                <td>{{ $rank['claims'] }}</td>
                                <td>{{ $rank['unlocks'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="grid grid--3 mt-lg">
                @foreach ($season['domains'] as $key => $domain)
                    <div class="card card--flat style-card domain--{{ $key }}">
                        <p class="domain__region">Стиль · {{ $domain['region'] }}</p>
                        <p class="mb-0 muted">{{ $domain['style'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Пригоди --}}
    <section class="section" id="adventure" aria-labelledby="adventure-title">
        <div class="container">
            <x-section-head kicker="Пригоди" title="Підземелля, Повелителі й Очі" title-id="adventure-title" />
            <div class="grid grid--3">
                <article class="card feature accent-violet" data-reveal>
                    <span class="icon-tile"><x-icon name="door-open" /></span>
                    <h3 class="card__title">Підземелля</h3>
                    <p class="card__text">Скрині в руїнах особисті: кожен знаходить свій лут, тож ніхто не обнесе підземелля до тебе. Запечатані скрині чекають на ключі й загадки, а скарби в загальних данжах із часом поновлюються.</p>
                </article>
                <article class="card feature accent-sun" data-reveal style="--delay: .06s">
                    <span class="icon-tile"><x-icon name="crown" /></span>
                    <h3 class="card__title">Повелителі</h3>
                    <p class="card__text">Азраель, Еліор і Веларіс мають власні фази бою. Бос-данжі проходять групою в окремому світі: збери друзів командою <code>/party</code>.</p>
                </article>
                <article class="card feature accent-abyss" data-reveal style="--delay: .12s">
                    <span class="icon-tile"><x-icon name="eye" /></span>
                    <h3 class="card__title">Очі Безодні</h3>
                    <p class="card__text">Дванадцять артефактів — по чотири в кожному домені. Кодекс Мандрівника відстежує знайдені Очі й підказує, де шукати далі.</p>
                </article>
            </div>
        </div>
    </section>

    {{-- Команди --}}
    <section class="section section--band" id="commands" aria-labelledby="commands-title">
        <div class="container container--narrow">
            <x-section-head kicker="Шпаргалка" title="Корисні команди" title-id="commands-title" />
            <div class="table-wrap">
                <table class="table">
                    <tbody>
                        @foreach ($season['commands'] as $command)
                            <tr>
                                <td class="nowrap"><code translate="no">{{ $command['cmd'] }}</code></td>
                                <td>{{ $command['text'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- Питання --}}
    <section class="section" id="faq" aria-labelledby="faq-title">
        <div class="container container--narrow">
            <x-section-head kicker="Питання" title="Часті питання" title-id="faq-title" />
            <div class="faq">
                @foreach ($season['faq'] as $item)
                    <details>
                        <summary>{{ $item['q'] }} <x-icon name="plus" /></summary>
                        <div class="faq__answer"><p>{{ str_replace(':version', $version, $item['a']) }}</p></div>
                    </details>
                @endforeach
            </div>
            <p class="text-center muted mt-lg">Не знайшов відповіді? Почитай <a href="{{ route('rules') }}">правила</a> або спитай у спільноти.</p>
        </div>
    </section>
@endsection
