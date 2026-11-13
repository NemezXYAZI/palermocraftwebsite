@extends('layouts.app')

@section('description', 'PALERMOCRAFT — український сюжетний SMP у світі темного фентезі. Сезон I «Ехо Безодні»: поселення з жителями, підземелля з особистим лутом, Повелителі-боси й дванадцять Очей Безодні. Minecraft Java '.config('palermo.game_version').', будь-який лаунчер, без модів.')

@php
    $emberColors = ['var(--gold-1)', 'var(--violet)', 'var(--gold-2)'];
@endphp

@section('content')
    {{-- Головний екран --}}
    <section class="hero" aria-labelledby="hero-title">
        <div class="embers" aria-hidden="true">
            @for ($i = 1; $i <= 18; $i++)
                <span style="--x: {{ ($i * 37) % 97 }}%; --d: {{ 11 + ($i * 7) % 9 }}s; --delay: -{{ round(fmod($i * 1.9, 13), 1) }}s; --size: {{ 2 + $i % 3 }}px; --drift: {{ ($i % 2 ? 1 : -1) * (12 + ($i % 5) * 7) }}px; --c: {{ $emberColors[$i % 3] }}; --o: {{ 0.45 + ($i % 4) * 0.12 }}"></span>
            @endfor
        </div>

        <div class="container hero__grid">
            <div class="hero__copy">
                <p class="kicker kicker--left">PALERMOCRAFT · Сезон {{ $season['number'] }}</p>
                <h1 class="display hero__title" id="hero-title">
                    <span class="text-gold">{{ $season['title'] }}</span>
                    <em>{{ $season['tagline'] }}</em>
                </h1>
                <p class="lead hero__lead">{{ $season['pitch'] }}</p>
                <div class="hero__actions">
                    <a class="btn btn--primary btn--lg" href="{{ route('guide') }}#join">
                        <x-icon name="play" /> Почати грати
                    </a>
                    <a class="btn btn--outline btn--lg" href="{{ route('world') }}">
                        <x-icon name="book-open" /> Історія світу
                    </a>
                </div>
                <x-ip-card />
            </div>

            <div class="hero__visual">
                @include('partials.emblem')
            </div>
        </div>
    </section>

    {{-- Коротко --}}
    <section class="facts-band" aria-label="Коротко про сервер">
        <div class="container facts">
            @foreach ($season['facts'] as $fact)
                <div class="fact">
                    <x-icon :name="$fact['icon']" />
                    <div>
                        <span class="fact__title">{{ $fact['title'] }}</span>
                        <span class="fact__text">{{ $fact['text'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Світ --}}
    <section class="section" aria-labelledby="world-title">
        <div class="container">
            <x-section-head kicker="Світ сезону" title="Три домени, три Серця, одна Безодня" title-id="world-title"
                            lead="Кожен Повелитель упав по-своєму. Досліджуй їхні землі, відбудовуй села й шукай Очі, що відчиняють шлях до Каеля." />

            <div class="grid grid--3">
                @foreach ($season['domains'] as $key => $domain)
                    <article class="domain domain--{{ $key }}" data-reveal style="--delay: {{ $loop->index * 0.08 }}s">
                        @include('partials.sigil', ['name' => $key])
                        <p class="domain__region">{{ $domain['region'] }}</p>
                        <h3 class="domain__name">{{ $domain['name'] }}</h3>
                        <p class="domain__lord">{{ $domain['lord'] }} — {{ $domain['lord_title'] }}</p>
                        <p class="domain__text">{{ $domain['text'] }}</p>
                        <div class="domain__meta">
                            <span class="domain__heart"><x-icon name="gem" /> {{ $domain['heart'] }}</span>
                            <ul class="eye-list" aria-label="Очі домену">
                                @foreach ($domain['eyes'] as $eye)
                                    <li class="eye-chip"><x-icon name="eye" /> {{ $eye }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="grid grid--2 mt-lg">
                <article class="card card--accent accent-abyss path-card" data-reveal>
                    @include('partials.sigil', ['name' => 'library', 'class' => 'domain__sigil'])
                    <div>
                        <p class="domain__region">{{ $season['library']['region'] }}</p>
                        <h3 class="domain__name">{{ $season['library']['name'] }}</h3>
                        <p class="card__text">{{ $season['library']['text'] }}</p>
                    </div>
                </article>
                <article class="card card--accent accent-abyss path-card" data-reveal style="--delay: .08s">
                    @include('partials.sigil', ['name' => 'void', 'class' => 'domain__sigil'])
                    <div>
                        <p class="domain__region">{{ $season['kael']['region'] }}</p>
                        <h3 class="domain__name">{{ $season['kael']['name'] }} — {{ $season['kael']['title'] }}</h3>
                        <p class="card__text">{{ $season['kael']['text'] }}</p>
                    </div>
                </article>
            </div>

            <p class="text-center mt-lg">
                <a class="btn btn--outline" href="{{ route('world') }}">Уся історія світу <x-icon name="arrow-right" /></a>
            </p>
        </div>
    </section>

    {{-- Можливості --}}
    <section class="section section--band" aria-labelledby="features-title">
        <div class="container">
            <x-section-head kicker="Можливості" title="Що на тебе чекає" title-id="features-title"
                            lead="Сервер тримається на власних плагінах: усе працює на звичайному клієнті, а важке лягає на сервер, а не на твій ПК." />

            <div class="grid grid--4">
                @foreach ($season['features'] as $feature)
                    <article class="card card--hover feature accent-{{ $feature['accent'] }}" data-reveal style="--delay: {{ ($loop->index % 4) * 0.06 }}s">
                        <span class="icon-tile"><x-icon :name="$feature['icon']" /></span>
                        <h3 class="card__title">{{ $feature['title'] }}</h3>
                        <p class="card__text">{{ $feature['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Як почати --}}
    <section class="section" id="start" aria-labelledby="start-title">
        <div class="container">
            <x-section-head kicker="Перший вхід" title="У гру — за кілька хвилин" title-id="start-title"
                            lead="Без реєстрації, паролів і модів. Акаунт на сайті знадобиться лише для скіна." />

            <ol class="steps" style="--steps: 4">
                <li class="step" data-reveal>
                    <span class="step__num">1</span>
                    <h3>Запусти Minecraft</h3>
                    <p>Java Edition {{ config('palermo.game_version') }} у будь-якому лаунчері — офіційному чи піратському.</p>
                </li>
                <li class="step" data-reveal style="--delay: .06s">
                    <span class="step__num">2</span>
                    <h3>Додай сервер</h3>
                    <p>«Мережева гра» → «Додати сервер» → <code translate="no">{{ config('palermo.server_address') }}</code></p>
                </li>
                <li class="step" data-reveal style="--delay: .12s">
                    <span class="step__num">3</span>
                    <h3>Підтверди вхід</h3>
                    <p>Під час першого входу надішли код боту в Telegram чи Discord — далі він впускатиме тебе однією кнопкою.</p>
                </li>
                <li class="step" data-reveal style="--delay: .18s">
                    <span class="step__num">4</span>
                    <h3>Обери шлях</h3>
                    <p>Засновуй поселення, шукай Очі Безодні або спускайся в підземелля з друзями.</p>
                </li>
            </ol>

            <p class="text-center mt-lg">
                <a class="btn btn--outline" href="{{ route('guide') }}">Детальний путівник <x-icon name="arrow-right" /></a>
            </p>
        </div>
    </section>

    {{-- Хто зараз у грі --}}
    @if ($status['online'] && count($status['list']))
        <section class="section section--tight section--flush-top" aria-labelledby="online-title">
            <div class="container">
                <div class="card cluster cluster--between">
                    <div>
                        <p class="kicker kicker--left">Зараз у грі</p>
                        <h2 class="h3 mb-0" id="online-title">
                            {{ $status['players'] }} {{ \App\Support\Text::plural($status['players'], 'Мандрівник', 'Мандрівники', 'Мандрівників') }} у Безодні
                        </h2>
                    </div>
                    <ul class="online">
                        @foreach ($status['list'] as $name)
                            <li><x-head :name="$name" :src="$heads[$name] ?? null" size="28" /> {{ $name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>
    @endif

    {{-- Новини --}}
    @if ($news->isNotEmpty())
        <section class="section section--band" aria-labelledby="news-title">
            <div class="container">
                <div class="cluster cluster--between" style="margin-bottom: clamp(24px, 4vw, 40px)">
                    <div>
                        <p class="kicker kicker--left">Хроніка</p>
                        <h2 class="mb-0" id="news-title">Новини</h2>
                    </div>
                    <a class="btn btn--ghost" href="{{ route('news.index') }}">Усі новини <x-icon name="arrow-right" /></a>
                </div>
                <div class="news-grid">
                    @foreach ($news as $item)
                        <a class="card card--hover news-card" href="{{ route('news.show', $item->slug) }}" data-reveal style="--delay: {{ $loop->index * 0.06 }}s">
                            <span class="news-card__date"><x-icon name="calendar" /> {{ $item->published_at->translatedFormat('j F Y') }}</span>
                            <h3 class="news-card__title">{{ $item->title }}</h3>
                            <p class="news-card__excerpt">{{ \App\Support\Text::excerpt($item->body, 170) }}</p>
                            <span class="news-card__more">Читати <x-icon name="arrow-right" /></span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Заклик --}}
    <section class="section" aria-labelledby="cta-title">
        <div class="container">
            <div class="callout" data-reveal>
                <div class="callout__grid">
                    <div>
                        <p class="kicker kicker--left">Безодня чекає</p>
                        <h2 id="cta-title">Стань новим Мандрівником</h2>
                        <p class="lead">Скопіюй адресу, заходь у гру й обирай свій шлях. Акаунт на сайті — для скіна, який бачать усі, і входу через Telegram чи Discord.</p>
                        <div class="cluster">
                            @auth
                                <a class="btn btn--primary" href="{{ route('profile.index') }}"><x-icon name="user-round" /> Мій профіль</a>
                            @else
                                <a class="btn btn--primary" href="{{ route('register') }}"><x-icon name="user-round" /> Створити акаунт</a>
                            @endauth
                            <a class="btn btn--ghost" href="{{ route('rules') }}">Правила сервера</a>
                        </div>
                    </div>
                    <div>
                        <x-ip-card />
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
