@extends('layouts.app')

@section('title', $item->title.' - PalermoCraft')

@section('content')
    <main class="flex-grow py-10 px-4 w-full">
        <article class="max-w-3xl mx-auto bg-palermo-card border border-gray-800 rounded-2xl p-8">
            <a href="{{ route('news.index') }}" class="text-sm text-gray-400 hover:text-palermo-green">← Усі новини</a>
            <p class="text-xs text-gray-500 mt-4">{{ $item->published_at->format('d.m.Y') }}</p>
            <h1 class="text-3xl font-bold text-palermo-green mb-6">{{ $item->title }}</h1>
            {{-- Текст новини: без HTML, абзаци з переносів рядка --}}
            <div class="text-gray-200 leading-relaxed space-y-4">
                @foreach (preg_split("/\R{2,}/", $item->body) as $paragraph)
                    <p>{!! nl2br(e($paragraph)) !!}</p>
                @endforeach
            </div>
        </article>
    </main>
@endsection
