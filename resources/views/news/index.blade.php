@extends('layouts.app')

@section('title', 'Новини')
@section('description', 'Новини сервера PALERMOCRAFT: оновлення, події сезону «Ехо Безодні» та оголошення адміністрації.')

@section('content')
    <section class="page-hero">
        <div class="container">
            <p class="kicker kicker--left">Хроніка сезону</p>
            <h1>Новини</h1>
            <p class="lead">Оновлення сервера, події сезону й оголошення адміністрації.</p>
        </div>
    </section>

    <section class="section section--tight section--flush-top">
        <div class="container">
            @if ($news->isEmpty())
                <div class="empty">
                    <x-icon name="newspaper" />
                    <h3>Новин поки немає</h3>
                    <p class="mb-0">Перші записи зʼявляться перед стартом сезону.</p>
                </div>
            @else
                @php($featured = $news->onFirstPage() ? $news->first() : null)
                @if ($featured)
                    <a class="card card--hover news-card news-card--featured" href="{{ route('news.show', $featured->slug) }}">
                        <span class="news-card__date"><x-icon name="calendar" /> {{ $featured->published_at->translatedFormat('j F Y') }}</span>
                        <h2 class="news-card__title">{{ $featured->title }}</h2>
                        <p class="news-card__excerpt lead">{{ \App\Support\Text::excerpt($featured->body, 320) }}</p>
                        <span class="news-card__more">Читати повністю <x-icon name="arrow-right" /></span>
                    </a>
                @endif

                @php($rest = $featured ? $news->getCollection()->slice(1) : $news->getCollection())
                @if ($rest->isNotEmpty())
                    <div class="news-grid mt-lg">
                        @foreach ($rest as $item)
                            <a class="card card--hover news-card" href="{{ route('news.show', $item->slug) }}" data-reveal style="--delay: {{ ($loop->index % 3) * 0.05 }}s">
                                <span class="news-card__date"><x-icon name="calendar" /> {{ $item->published_at->translatedFormat('j F Y') }}</span>
                                <h2 class="news-card__title">{{ $item->title }}</h2>
                                <p class="news-card__excerpt">{{ \App\Support\Text::excerpt($item->body, 170) }}</p>
                                <span class="news-card__more">Читати <x-icon name="arrow-right" /></span>
                            </a>
                        @endforeach
                    </div>
                @endif

                {{ $news->links() }}
            @endif
        </div>
    </section>
@endsection
