@extends('layouts.app')

@section('title', 'Новини - PalermoCraft')

@section('content')
    <main class="flex-grow py-10 px-4 w-full">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-palermo-green mb-8 text-center">Новини</h2>
            @forelse ($news as $item)
                <article class="bg-palermo-card border border-gray-800 rounded-2xl p-6 mb-6">
                    <p class="text-xs text-gray-500 mb-2">{{ $item->published_at->format('d.m.Y') }}</p>
                    <h3 class="text-xl font-bold mb-3"><a href="{{ route('news.show', $item->slug) }}" class="hover:text-palermo-green">{{ $item->title }}</a></h3>
                    <p class="text-gray-300">{{ \Illuminate\Support\Str::limit(strip_tags($item->body), 280) }}</p>
                </article>
            @empty
                <p class="text-center text-gray-500">Новин поки немає.</p>
            @endforelse
            {{ $news->links() }}
        </div>
    </main>
@endsection
