@extends('layouts.app')

@section('title', 'Світ сезону')
@section('description', 'Лор сезону «Ехо Безодні»: троє Повелителів — Азраель, Еліор і Веларіс, Серця Пустоти, подвиг Першого Мандрівника Каеля, дванадцять Очей Безодні та Загублена Бібліотека.')

@php
    $romans = ['I', 'II', 'III', 'IV', 'V'];
    $domainIcons = ['sun' => 'sun', 'life' => 'sprout', 'frost' => 'snowflake'];
@endphp

@section('content')
    <section class="page-hero page-hero--center">
        <div class="container container--narrow">
            <p class="kicker">Сезон {{ $season['number'] }} · {{ $season['title_en'] }}</p>
            <h1 class="display"><span class="text-gold">{{ $season['title'] }}</span></h1>
            <p class="lead mt-lg">{{ $season['pitch'] }}</p>
            <div class="ornament mt-lg"><i></i></div>
        </div>
    </section>

    {{-- Хроніка --}}
    <section class="section section--flush-top" aria-labelledby="lore-title">
        <div class="container container--text">
            <h2 class="sr-only" id="lore-title">Історія світу</h2>
            @foreach ($season['chapters'] as $chapter)
                <article class="lore-chapter" data-reveal>
                    <div class="lore-chapter__num" aria-hidden="true">{{ $romans[$loop->index] }}</div>
                    <div>
                        <h3>{{ $chapter['title'] }}</h3>
                        <div class="prose {{ $loop->first ? 'prose--dropcap' : '' }}">
                            @foreach ($chapter['text'] as $paragraph)
                                <p>{{ $paragraph }}</p>
                            @endforeach
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Шлях Мандрівника --}}
    <section class="section section--band" aria-labelledby="path-title">
        <div class="container">
            <x-section-head kicker="Шлях Мандрівника" title="Від трьох доменів — до Безодні" title-id="path-title"
                            lead="Три арки сезону розвиваються паралельно. Лише коли всі три домени віддадуть свої Очі, відчиниться шлях до Каеля." />

            <div class="journey">
                <div class="journey__domains">
                    @foreach ($season['domains'] as $key => $domain)
                        <a href="#domain-{{ $key }}" class="journey__node domain--{{ $key }}">
                            @include('partials.sigil', ['name' => $key, 'class' => 'domain__sigil'])
                            <span class="journey__region">{{ $domain['region'] }}</span>
                            <strong>{{ $domain['name'] }}</strong>
                            <span class="journey__meta">4 Ока</span>
                        </a>
                    @endforeach
                </div>
                <svg class="journey__lines" viewBox="0 0 600 90" preserveAspectRatio="none" aria-hidden="true">
                    <path d="M100 0 C 100 55, 300 35, 300 90" />
                    <path d="M300 0 L 300 90" />
                    <path d="M500 0 C 500 55, 300 35, 300 90" />
                </svg>
                <div class="journey__node journey__node--wide accent-abyss">
                    @include('partials.sigil', ['name' => 'library', 'class' => 'domain__sigil'])
                    <span class="journey__region">{{ $season['library']['region'] }}</span>
                    <strong>{{ $season['library']['name'] }}</strong>
                    <span class="journey__meta">12 Очей відчиняють вівтар</span>
                </div>
                <svg class="journey__lines journey__lines--short" viewBox="0 0 600 60" preserveAspectRatio="none" aria-hidden="true">
                    <path d="M300 0 L 300 60" />
                </svg>
                <div class="journey__node journey__node--wide journey__node--final accent-abyss">
                    @include('partials.sigil', ['name' => 'void', 'class' => 'domain__sigil'])
                    <span class="journey__region">{{ $season['kael']['region'] }}</span>
                    <strong>{{ $season['kael']['name'] }} — {{ $season['kael']['title'] }}</strong>
                    <span class="journey__meta">Фінал сезону</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Домени --}}
    @foreach ($season['domains'] as $key => $domain)
        <section class="section" id="domain-{{ $key }}" aria-labelledby="domain-{{ $key }}-title">
            <div class="container">
                <div class="split domain-detail domain--{{ $key }} {{ $loop->odd ? '' : 'domain-detail--flip' }}">
                    <div class="domain-detail__art" data-reveal>
                        @include('partials.sigil', ['name' => $key, 'class' => 'domain-detail__sigil'])
                    </div>
                    <div data-reveal style="--delay: .08s">
                        <p class="domain__region">{{ $domain['region'] }} · {{ $domain['heart'] }}</p>
                        <h2 id="domain-{{ $key }}-title">{{ $domain['name'] }}</h2>
                        <p class="domain__lord">{{ $domain['lord'] }} — {{ $domain['lord_title'] }}</p>
                        <div class="prose">
                            <p>{{ $domain['text'] }}</p>
                            <p>Без Серця {{ $domain['lord'] }} став тінню власних руїн — напівживим вартовим, що досі стереже свій домен. Його землі чекають на тих, хто наважиться їх відбудувати.</p>
                        </div>
                        <div class="domain-detail__facts">
                            <div>
                                <span class="label">Очі домену</span>
                                <ul class="eye-list">
                                    @foreach ($domain['eyes'] as $eye)
                                        <li class="eye-chip"><x-icon name="eye" /> {{ $eye }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div>
                                <span class="label">Архітектура поселень</span>
                                <p class="faint mb-0">{{ $domain['style'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endforeach

    {{-- Дванадцять Очей --}}
    <section class="section section--band" aria-labelledby="eyes-title">
        <div class="container">
            <x-section-head kicker="Артефакти" title="Дванадцять Очей Безодні" title-id="eyes-title"
                            lead="По чотири в кожному домені. Знайди їх, принеси до Загубленої Бібліотеки — і вівтарі Каеля прокинуться." />
            <div class="eyes-grid">
                @foreach ($season['domains'] as $key => $domain)
                    @foreach ($domain['eyes'] as $eye)
                        <div class="eye-cell domain--{{ $key }}" data-reveal style="--delay: {{ ($loop->parent->index * 4 + $loop->index) * 0.03 }}s">
                            <svg viewBox="0 0 48 30" aria-hidden="true"><path d="M3 15 Q24 -4 45 15 Q24 34 3 15Z" fill="currentColor" fill-opacity=".1" stroke="currentColor" stroke-width="1.6"/><circle cx="24" cy="15" r="6" fill="currentColor" fill-opacity=".85"/><ellipse cx="24" cy="15" rx="1.4" ry="5" fill="#05030b"/></svg>
                            <strong>{{ $eye }}</strong>
                            <span>{{ $domain['region'] }}</span>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </section>

    {{-- Відбудова --}}
    <section class="section" aria-labelledby="rebuild-title">
        <div class="container">
            <div class="split" style="align-items: start">
                <x-section-head align="left" kicker="Спільна справа" title="Відбудуємо світ разом" title-id="rebuild-title"
                                lead="Головні руїни відроджує весь сервер: кожен внесок наближає момент, коли світ зміниться для всіх — зʼявляться нові будівлі, мешканці й торговці. Подробиці розкриються протягом сезону.">
                    <p class="mt-lg"><a class="btn btn--outline" href="{{ route('guide') }}#colonies">Як працюють поселення <x-icon name="arrow-right" /></a></p>
                </x-section-head>
                <ol class="timeline">
                    @foreach ($season['rebuild'] as $stage)
                        <li class="timeline__item accent-{{ $stage['accent'] }}" data-reveal style="--delay: {{ $loop->index * 0.06 }}s">
                            <span class="timeline__mark">{{ $stage['mark'] }}</span>
                            <div class="timeline__body">
                                <h3>{{ $stage['title'] }}</h3>
                                <p>{{ $stage['text'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    <section class="section section--flush-top">
        <div class="container">
            <div class="callout text-center" data-reveal>
                <p class="kicker">Твоя черга</p>
                <h2>Історію допишуть Мандрівники</h2>
                <p class="lead" style="max-width: 38em; margin-inline: auto">Сюжет сезону розгортатиметься разом із сервером: нові глави, мешканці й таємниці зʼявлятимуться в міру того, як гравці відроджуватимуть світ.</p>
                <div class="cluster" style="justify-content: center; margin-top: 24px">
                    <a class="btn btn--primary btn--lg" href="{{ route('guide') }}#join"><x-icon name="play" /> Почати грати</a>
                    <a class="btn btn--ghost btn--lg" href="{{ route('news.index') }}">Новини сезону</a>
                </div>
            </div>
        </div>
    </section>
@endsection
