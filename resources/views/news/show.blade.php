@extends('layouts.app')

@section('title', $item->title)
@section('description', \App\Support\Text::excerpt($item->body, 160))

@section('content')
    <article class="section section--tight">
        <div class="container">
            <div class="article">
                <a class="back-link" href="{{ route('news.index') }}"><x-icon name="arrow-left" /> {{ __('Усі новини') }}</a>
                <h1 class="article__title" lang="uk">{{ $item->title }}</h1>
                <div class="article__meta">
                    <span><x-icon name="calendar" /> <time datetime="{{ $item->published_at->toDateString() }}">{{ $item->published_at->translatedFormat('j F Y') }}</time></span>
                    @if ($item->author)
                        <span><x-icon name="feather" /> {{ $item->author->name }}</span>
                    @endif
                </div>
                <div class="ornament mt-lg" style="margin-left: 0"><i></i></div>
                {{-- Текст новини: без HTML, абзаци з порожніх рядків --}}
                @unless (app()->isLocale('uk'))
                    <p class="muted mt-lg mb-0">{{ __('Новини виходять українською.') }}</p>
                @endunless
                <div class="prose prose--dropcap mt-lg" lang="uk">
                    @foreach (preg_split("/\R{2,}/", trim($item->body)) as $paragraph)
                        <p>{!! nl2br(e($paragraph)) !!}</p>
                    @endforeach
                </div>
                <div class="card__foot cluster cluster--between">
                    <a class="back-link" href="{{ route('news.index') }}"><x-icon name="arrow-left" /> {{ __('До новин') }}</a>
                    <button type="button" class="btn btn--ghost btn--sm" data-copy="{{ url()->current() }}" data-copied="{{ __('Посилання скопійовано') }}">
                        <x-icon name="link" /> <span data-copy-label>{{ __('Скопіювати посилання') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </article>
@endsection
