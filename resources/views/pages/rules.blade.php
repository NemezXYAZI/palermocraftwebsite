@extends('layouts.app')

@section('title', 'Правила')
@section('description', 'Правила сервера PALERMOCRAFT: спілкування, чесна гра, чуже майно, акаунт і покарання.')

@section('content')
    <section class="page-hero">
        <div class="container">
            <p class="kicker kicker--left">Кодекс сервера</p>
            <h1>Правила</h1>
            <p class="lead">{{ $rules['intro'] }}</p>
            <p class="faint mb-0"><x-icon name="clock" /> Оновлено {{ $rules['updated'] }}</p>
        </div>
    </section>

    <section class="section section--tight section--flush-top">
        <div class="container">
            <div class="with-toc">
                <nav class="toc" aria-label="Розділи правил">
                    <p class="toc__title">Розділи</p>
                    @foreach ($rules['sections'] as $section)
                        <a href="#{{ $section['id'] }}">{{ $loop->iteration }}. {{ $section['title'] }}</a>
                    @endforeach
                    <a href="#penalties">Покарання</a>
                </nav>

                <div>
                    @foreach ($rules['sections'] as $section)
                        @php($sectionNumber = $loop->iteration)
                        <section class="rules-section" id="{{ $section['id'] }}" aria-labelledby="{{ $section['id'] }}-title">
                            <div class="rules-section__head">
                                <span class="icon-tile icon-tile--sm"><x-icon :name="$section['icon']" /></span>
                                <h2 id="{{ $section['id'] }}-title">{{ $sectionNumber }}. {{ $section['title'] }}</h2>
                            </div>
                            <ol class="rule-list">
                                @foreach ($section['rules'] as $rule)
                                    <li class="rule" id="rule-{{ $sectionNumber }}-{{ $loop->iteration }}">
                                        <span class="rule__num">{{ $sectionNumber }}.{{ $loop->iteration }}</span>
                                        <div>
                                            <p class="rule__text">{{ $rule['text'] }}</p>
                                            @isset($rule['note'])
                                                <p class="rule__note">{{ $rule['note'] }}</p>
                                            @endisset
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        </section>
                    @endforeach

                    <section class="rules-section" id="penalties" aria-labelledby="penalties-title">
                        <div class="rules-section__head">
                            <span class="icon-tile icon-tile--sm accent-sun"><x-icon name="gavel" /></span>
                            <h2 id="penalties-title">Покарання</h2>
                        </div>
                        <p class="muted">Покарання залежить від порушення й того, чи воно повторюється. Адміністрація може пропустити сходинку, якщо порушення серйозне.</p>
                        <div class="penalties">
                            @foreach ($rules['penalties'] as $penalty)
                                <div class="penalty">
                                    <strong>{{ $penalty['title'] }}</strong>
                                    <span>{{ $penalty['text'] }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="alert alert--info mt-lg">
                            <x-icon name="info" />
                            <p>Блокування на сайті діє й у грі: заблокований акаунт не зможе увійти ні туди, ні туди.</p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
@endsection
