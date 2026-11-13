@extends('layouts.app')

@section('title', __('Передісторія світу'))
@section('description', __('Передісторія сезону «Ехо Безодні»: троє Повелителів — Азраель, Еліор і Веларіс, сила Пустоти, подвиг Першого Мандрівника та три краї, де гравці засновуватимуть поселення.'))

@section('content')
    <section class="page-hero page-hero--center">
        <div class="container container--narrow">
            <p class="kicker">{{ __('Сезон :number', ['number' => $season['number']]) }} · {{ __('Передісторія') }}</p>
            <h1 class="display"><span class="text-gold">{{ $season['title'] }}</span></h1>
            <p class="lead mt-lg">{{ __('Що сталося задовго до появи нових Мандрівників. Сюжет самого сезону ще попереду — він розкриватиметься разом із сервером.') }}</p>
            <div class="ornament mt-lg"><i></i></div>
        </div>
    </section>

    {{-- Передісторія --}}
    <section class="section section--flush-top" aria-labelledby="lore-title">
        <div class="container container--text">
            <h2 class="sr-only" id="lore-title">{{ __('Передісторія') }}</h2>
            @foreach ($season['chapters'] as $chapter)
                <article class="lore-chapter" data-reveal>
                    <div class="lore-chapter__num" aria-hidden="true">{{ sprintf('%02d', $loop->iteration) }}</div>
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

    {{-- Землі Повелителів --}}
    <section class="section section--band" aria-labelledby="lands-title">
        <div class="container">
            <x-section-head :kicker="__('Землі Повелителів')" :title="__('Три краї')" title-id="lands-title"
                            :lead="__('Тут Мандрівники засновуватимуть поселення. Кожен край має власну архітектуру, і креслення споруд зроблені в його стилі.')" />

            @foreach ($season['domains'] as $key => $domain)
                <div class="split domain-detail domain--{{ $key }} {{ $loop->odd ? '' : 'domain-detail--flip' }}" id="domain-{{ $key }}">
                    <div class="domain-detail__art" data-reveal>
                        @include('partials.sigil', ['name' => $key, 'class' => 'domain-detail__sigil'])
                    </div>
                    <div data-reveal style="--delay: .08s">
                        <p class="domain__region">{{ $domain['region'] }}</p>
                        <h3 class="h2">{{ $domain['name'] }}</h3>
                        <p class="domain__lord">{{ $domain['lord'] }} — {{ $domain['lord_title'] }}</p>
                        <div class="prose">
                            <p>{{ $domain['text'] }}</p>
                            <p>{{ __('Відтоді :lord — тінь власних руїн: напівживий вартовий, що досі стереже свій край.', ['lord' => $domain['lord']]) }}</p>
                        </div>
                        <div class="domain-detail__facts">
                            <div>
                                <span class="label">{{ __('Архітектура поселень') }}</span>
                                <p class="faint mb-0">{{ $domain['style'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="callout text-center" data-reveal>
                <p class="kicker">{{ __('Твоя черга') }}</p>
                <h2>{{ __('Далі історію пишуть Мандрівники') }}</h2>
                <p class="lead" style="max-width: 38em; margin-inline: auto">{{ __('Основний сюжет сезону ще попереду: нові глави, мешканці й таємниці зʼявлятимуться разом із сервером. Стеж за новинами.') }}</p>
                <div class="cluster" style="justify-content: center; margin-top: 24px">
                    <a class="btn btn--primary btn--lg" href="{{ route('guide') }}#join"><x-icon name="play" /> {{ __('Почати грати') }}</a>
                    <a class="btn btn--ghost btn--lg" href="{{ route('news.index') }}">{{ __('Новини сезону') }}</a>
                </div>
            </div>
        </div>
    </section>
@endsection
