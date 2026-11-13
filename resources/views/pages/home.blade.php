@extends('layouts.app')

@section('description', __('PALERMOCRAFT — сюжетний SMP у світі темного фентезі. Сезон I «Ехо Безодні»: поселення з жителями, підземелля з особистим лутом і Повелителі-боси. Minecraft Java :version, будь-який лаунчер, без модів.', ['version' => config('palermo.game_version')]))

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
                <p class="kicker kicker--left">PALERMOCRAFT · {{ __('Сезон :number', ['number' => $season['number']]) }}</p>
                <h1 class="display hero__title" id="hero-title">
                    <span class="text-gold">{{ $season['title'] }}</span>
                    <em>{{ $season['tagline'] }}</em>
                </h1>
                <p class="lead hero__lead">{{ $season['pitch'] }}</p>
                <div class="hero__actions">
                    <a class="btn btn--primary btn--lg" href="{{ route('guide') }}#join">
                        <x-icon name="play" /> {{ __('Почати грати') }}
                    </a>
                    <a class="btn btn--outline btn--lg" href="{{ route('world') }}">
                        <x-icon name="book-open" /> {{ __('Передісторія') }}
                    </a>
                </div>
                <x-ip-card />
            </div>

            <div class="hero__visual">
                @include('partials.hero-visual')
            </div>
        </div>
    </section>

    {{-- Коротко --}}
    <section class="facts-band" aria-label="{{ __('Коротко про сервер') }}">
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
            <x-section-head :kicker="__('Світ сезону')" :title="__('Три краї — три Повелителі')" title-id="world-title"
                            :lead="__('Пустеля, літні рівнини й зимові гори: кожен край памʼятає свого владику. Решту історії розкриє сам сезон.')" />

            <div class="grid grid--3">
                @foreach ($season['domains'] as $key => $domain)
                    <article class="domain domain--{{ $key }}" data-reveal style="--delay: {{ $loop->index * 0.08 }}s">
                        @include('partials.sigil', ['name' => $key])
                        <p class="domain__region">{{ $domain['region'] }}</p>
                        <h3 class="domain__name">{{ $domain['name'] }}</h3>
                        <p class="domain__lord">{{ $domain['lord'] }} — {{ $domain['lord_title'] }}</p>
                        <p class="domain__text">{{ $domain['text'] }}</p>
                    </article>
                @endforeach
            </div>

            <p class="text-center mt-lg">
                <a class="btn btn--outline" href="{{ route('world') }}">{{ __('Передісторія світу') }} <x-icon name="arrow-right" /></a>
            </p>
        </div>
    </section>

    {{-- Можливості --}}
    <section class="section section--band" aria-labelledby="features-title">
        <div class="container">
            <x-section-head :kicker="__('Можливості')" :title="__('Що на тебе чекає')" title-id="features-title"
                            :lead="__('Сервер тримається на власних плагінах: усе працює на звичайному клієнті, а важке лягає на сервер, а не на твій ПК.')" />

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
            <x-section-head :kicker="__('Перший вхід')" :title="__('У гру — за кілька хвилин')" title-id="start-title"
                            :lead="__('Без реєстрації, паролів і модів. Акаунт на сайті знадобиться лише для скіна.')" />

            <ol class="steps" style="--steps: 4">
                <li class="step" data-reveal>
                    <span class="step__num">1</span>
                    <h3>{{ __('Запусти Minecraft') }}</h3>
                    <p>{{ __('Java Edition :version у будь-якому лаунчері — офіційному чи піратському.', ['version' => config('palermo.game_version')]) }}</p>
                </li>
                <li class="step" data-reveal style="--delay: .06s">
                    <span class="step__num">2</span>
                    <h3>{{ __('Додай сервер') }}</h3>
                    @if ($status['announced'])
                        <p>{{ __('«Мережева гра» → «Додати сервер» →') }} <code translate="no">{{ $status['address'] }}</code></p>
                    @else
                        <p>{{ __('«Мережева гра» → «Додати сервер». Адресу оголосимо перед стартом сезону.') }}</p>
                    @endif
                </li>
                <li class="step" data-reveal style="--delay: .12s">
                    <span class="step__num">3</span>
                    <h3>{{ __('Підтверди вхід') }}</h3>
                    <p>{{ __('Під час першого входу надішли код боту в Telegram чи Discord — далі він впускатиме тебе однією кнопкою.') }}</p>
                </li>
                <li class="step" data-reveal style="--delay: .18s">
                    <span class="step__num">4</span>
                    <h3>{{ __('Обери шлях') }}</h3>
                    <p>{{ __('Засновуй поселення, спускайся в підземелля з друзями або вирушай назустріч Повелителям.') }}</p>
                </li>
            </ol>

            <p class="text-center mt-lg">
                <a class="btn btn--outline" href="{{ route('guide') }}">{{ __('Детальний путівник') }} <x-icon name="arrow-right" /></a>
            </p>
        </div>
    </section>

    {{-- Хто зараз у грі --}}
    @if ($status['online'] && count($status['list']))
        <section class="section section--tight section--flush-top" aria-labelledby="online-title">
            <div class="container">
                <div class="card cluster cluster--between">
                    <div>
                        <p class="kicker kicker--left">{{ __('Зараз у грі') }}</p>
                        <h2 class="h3 mb-0" id="online-title">
                            {{ trans_choice(':count Мандрівник у Безодні|:count Мандрівники у Безодні|:count Мандрівників у Безодні', $status['players'], ['count' => $status['players']]) }}
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
                        <p class="kicker kicker--left">{{ __('Хроніка') }}</p>
                        <h2 class="mb-0" id="news-title">{{ __('Новини') }}</h2>
                    </div>
                    <a class="btn btn--ghost" href="{{ route('news.index') }}">{{ __('Усі новини') }} <x-icon name="arrow-right" /></a>
                </div>
                <div class="news-grid">
                    @foreach ($news as $item)
                        <a class="card card--hover news-card" href="{{ route('news.show', $item->slug) }}" data-reveal style="--delay: {{ $loop->index * 0.06 }}s">
                            <span class="news-card__date"><x-icon name="calendar" /> {{ $item->published_at->translatedFormat('j F Y') }}</span>
                            <h3 class="news-card__title" lang="uk">{{ $item->title }}</h3>
                            <p class="news-card__excerpt" lang="uk">{{ \App\Support\Text::excerpt($item->body, 170) }}</p>
                            <span class="news-card__more">{{ __('Читати') }} <x-icon name="arrow-right" /></span>
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
                        <p class="kicker kicker--left">{{ __('Безодня чекає') }}</p>
                        <h2 id="cta-title">{{ __('Стань новим Мандрівником') }}</h2>
                        @if ($status['announced'])
                            <p class="lead">{{ __('Скопіюй адресу, заходь у гру й обирай свій шлях. Акаунт на сайті — для скіна, який бачать усі, і входу через Telegram чи Discord.') }}</p>
                        @else
                            <p class="lead">{{ __('Адресу сервера оголосимо перед стартом сезону. А поки створи акаунт: завантаж скін, який побачать усі, і привʼяжи Telegram чи Discord для входу.') }}</p>
                        @endif
                        <div class="cluster">
                            @auth
                                <a class="btn btn--primary" href="{{ route('profile.index') }}"><x-icon name="user-round" /> {{ __('Мій профіль') }}</a>
                            @else
                                <a class="btn btn--primary" href="{{ route('register') }}"><x-icon name="user-round" /> {{ __('Створити акаунт') }}</a>
                            @endauth
                            <a class="btn btn--ghost" href="{{ route('rules') }}">{{ __('Правила сервера') }}</a>
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
