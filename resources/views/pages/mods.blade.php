@extends('layouts.app')

@section('title', __('Моди'))
@section('description', __('Необовʼязкові моди для PALERMOCRAFT на Minecraft Java :version (Fabric): більше FPS на слабкому ПК, шейдери, живі анімації, атмосферний звук і наші власні моди. Що робить кожен і як встановити.', ['version' => config('palermo.game_version')]))

@php
    $version = config('palermo.game_version');
    $loadLabels = ['light' => __('Легкий'), 'medium' => __('Середній'), 'heavy' => __('Важкий')];
    $typePaths = ['mod' => 'mod', 'shader' => 'shader', 'resourcepack' => 'resourcepack'];
    $modrinth = function (array $mod) use ($version, $typePaths): string {
        $type = $mod['type'] ?? 'mod';
        $query = $type === 'mod' ? ['g' => $version, 'l' => 'fabric'] : ['g' => $version];

        return 'https://modrinth.com/'.$typePaths[$type].'/'.$mod['slug'].'/versions?'.http_build_query($query);
    };
    $fileSize = function (string $file): ?string {
        $path = public_path('downloads/mods/'.$file);
        if (! is_file($path)) {
            return null;
        }

        return __(':size КБ', ['size' => max(1, (int) round(filesize($path) / 1024))]);
    };
@endphp

@section('content')
    <section class="page-hero">
        <div class="container">
            <p class="kicker kicker--left">Fabric · Minecraft Java {{ $version }}</p>
            <h1>{{ __('Моди') }}</h1>
            <p class="lead">{{ __('Сервер працює на чистому клієнті — моди не обовʼязкові. Але з ними гра йде швидше, виглядає й звучить краще. Тут лише перевірені моди, які дозволяють правила.') }}</p>
            <nav class="chips-nav mt-lg" aria-label="{{ __('Розділи сторінки') }}">
                <a href="#install">{{ __('Як встановити') }}</a>
                <a href="#ours">{{ __('Наші моди') }}</a>
                @foreach ($mods['groups'] as $group)
                    <a href="#{{ $group['id'] }}">{{ $group['title'] }}</a>
                @endforeach
                <a href="#presets">{{ __('Готові набори') }}</a>
                <a href="#forbidden">{{ __('Заборонено') }}</a>
            </nav>
        </div>
    </section>

    {{-- Як встановити --}}
    <section class="section section--tight" id="install" aria-labelledby="install-title">
        <div class="container">
            <h2 id="install-title">{{ __('Як встановити') }}</h2>
            <ol class="steps steps--compact mt-lg" style="--steps: 4">
                <li class="step">
                    <span class="step__num">1</span>
                    <h3>{{ __('Встанови Fabric') }}</h3>
                    <p>{{ __('Офіційний лаунчер: запусти') }} <a href="https://fabricmc.net/use/installer/" target="_blank" rel="noopener">{{ __('інсталятор Fabric') }}</a>{{ __(', обери :version і натисни «Install». TLauncher: обери версію «Fabric :version». Prism Launcher: створи збірку :version із завантажувачем Fabric.', ['version' => $version]) }}</p>
                </li>
                <li class="step">
                    <span class="step__num">2</span>
                    <h3>{{ __('Додай Fabric API') }}</h3>
                    <p>{{ __('Бібліотека, без якої не працює більшість модів, зокрема наші.') }} <a href="https://modrinth.com/mod/fabric-api/versions?{{ http_build_query(['g' => $version, 'l' => 'fabric']) }}" target="_blank" rel="noopener">Fabric API <x-icon name="external-link" /></a></p>
                </li>
                <li class="step">
                    <span class="step__num">3</span>
                    <h3>{{ __('Поклади моди в папку mods') }}</h3>
                    <p>{{ __('Файли .jar — у .minecraft/mods (на Windows: %appdata%\.minecraft\mods). Шейдери — у shaderpacks, ресурспаки — у resourcepacks.') }}</p>
                </li>
                <li class="step">
                    <span class="step__num">4</span>
                    <h3>{{ __('Запусти профіль Fabric') }}</h3>
                    <p>{{ __('Заходь на сервер як завжди. Якщо гра не запускається — прибери останній доданий мод і перевір, чи встановлено все з «Потрібно».') }}</p>
                </li>
            </ol>
            <div class="alert alert--info mt-lg">
                <x-icon name="info" />
                <p>{{ __('Завантажуй моди лише з Modrinth або офіційних сторінок авторів і завжди версію для :version та Fabric. Посилання нижче вже відкривають потрібні версії.', ['version' => $version]) }}</p>
            </div>
        </div>
    </section>

    {{-- Наші моди --}}
    <section class="section section--band" id="ours" aria-labelledby="ours-title">
        <div class="container">
            <x-section-head align="left" :kicker="__('PALERMOCRAFT')" :title="__('Наші моди')" title-id="ours-title"
                            :lead="__('Зроблені спеціально для сервера. Без них усе працює, з ними — красивіше й зручніше.')" />
            <div class="grid grid--2">
                @foreach ($mods['ours'] as $mod)
                    @php($size = $fileSize($mod['file']))
                    <article class="card mod-card mod-card--ours" data-reveal style="--delay: {{ $loop->index * 0.06 }}s">
                        <div class="mod-card__head">
                            <span class="icon-tile accent-gold"><x-icon :name="$mod['icon']" /></span>
                            <div>
                                <h3 class="card__title mb-0">{{ $mod['name'] }}</h3>
                                <p class="mod-card__meta">{{ __('Версія :version', ['version' => $mod['version']]) }}@if ($size) · {{ $size }}@endif</p>
                            </div>
                        </div>
                        <p class="card__text">{{ $mod['text'] }}</p>
                        @if (! empty($mod['requires']))
                            <p class="mod-card__requires"><x-icon name="link" /> {{ __('Потрібно') }}: {{ implode(', ', $mod['requires']) }}</p>
                        @endif
                        @if ($size)
                            <a class="btn btn--primary" href="{{ asset('downloads/mods/'.$mod['file']) }}" download><x-icon name="download" /> {{ __('Завантажити') }}</a>
                        @else
                            <span class="badge">{{ __('Скоро') }}</span>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Групи модів --}}
    @foreach ($mods['groups'] as $group)
        <section @class(['section', 'section--band' => $loop->odd]) id="{{ $group['id'] }}" aria-labelledby="{{ $group['id'] }}-title">
            <div class="container">
                <div class="mods-group__head">
                    <span class="icon-tile accent-{{ $group['accent'] }}"><x-icon :name="$group['icon']" /></span>
                    <div>
                        <h2 class="mb-0" id="{{ $group['id'] }}-title">{{ $group['title'] }}</h2>
                        <p class="muted mb-0">{{ $group['lead'] }}</p>
                    </div>
                </div>
                <div class="mod-grid mt-lg">
                    @foreach ($group['mods'] as $mod)
                        <article class="card card--flat mod-card" data-reveal style="--delay: {{ ($loop->index % 3) * 0.04 }}s">
                            <div class="mod-card__top">
                                <h3 class="mod-card__name">{{ $mod['name'] }}</h3>
                                <span class="load load--{{ $mod['load'] }}" title="{{ __('Навантаження на ПК') }}">{{ $loadLabels[$mod['load']] }}</span>
                            </div>
                            @if (! empty($mod['essential']))
                                <p class="mod-card__flag"><x-icon name="star" /> {{ __('Радимо всім') }}</p>
                            @endif
                            <p class="card__text">{{ $mod['text'] }}</p>
                            @if (! empty($mod['requires']))
                                <p class="mod-card__requires"><x-icon name="link" /> {{ __('Потрібно') }}: {{ implode(', ', $mod['requires']) }}</p>
                            @endif
                            <a class="mod-card__link" href="{{ $modrinth($mod) }}" target="_blank" rel="noopener">
                                Modrinth <x-icon name="external-link" /><span class="sr-only"> — {{ $mod['name'] }}</span>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endforeach

    {{-- Готові набори --}}
    <section class="section" id="presets" aria-labelledby="presets-title">
        <div class="container">
            <x-section-head :kicker="__('Не знаєш, з чого почати?')" :title="__('Готові набори')" title-id="presets-title"
                            :lead="__('Обери набір під свій компʼютер. Кожен наступний — це попередній плюс ще кілька модів.')" />
            <div class="grid grid--3">
                @foreach ($mods['presets'] as $preset)
                    <article class="card preset" data-reveal style="--delay: {{ $loop->index * 0.06 }}s">
                        <span class="icon-tile"><x-icon :name="$preset['icon']" /></span>
                        <h3 class="card__title">{{ $preset['title'] }}</h3>
                        <p class="card__text">{{ $preset['text'] }}</p>
                        <ul class="preset__list">
                            @foreach ($preset['mods'] as $name)
                                <li>{{ $name }}</li>
                            @endforeach
                        </ul>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Заборонено --}}
    <section class="section section--flush-top" id="forbidden" aria-labelledby="forbidden-title">
        <div class="container container--narrow">
            <div class="card forbidden">
                <h2 class="panel-title" id="forbidden-title"><x-icon name="ban" /> {{ __('Заборонено') }}</h2>
                <ul class="check-list mt-lg">
                    @foreach ($mods['forbidden'] as $item)
                        <li><x-icon name="x" class="is-no" /> {{ $item }}</li>
                    @endforeach
                </ul>
                <p class="muted mb-0">{{ __('Сумніваєшся щодо мода — спитай адміністрацію до того, як заходити з ним на сервер.') }} <a href="{{ route('rules') }}#fair-play">{{ __('Правила чесної гри') }}</a></p>
            </div>
        </div>
    </section>
@endsection
