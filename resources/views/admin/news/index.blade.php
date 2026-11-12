@extends('admin.layout')

@section('title', 'Новини - Адмін-панель')

@section('admin')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Новини</h2>
        <a href="{{ route('admin.news.create') }}" class="bg-palermo-green text-black font-bold px-4 py-2 rounded-xl">+ Нова</a>
    </div>
    <div class="bg-palermo-card border border-gray-800 rounded-2xl divide-y divide-gray-800">
        @forelse ($news as $item)
            <div class="p-4 flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <p class="font-bold truncate">{{ $item->title }}</p>
                    <p class="text-xs text-gray-500">{{ $item->published_at ? 'Опубліковано '.$item->published_at->format('d.m.Y H:i') : 'Чернетка' }} · {{ $item->author?->name }}</p>
                </div>
                <div class="flex gap-2 shrink-0">
                    <a href="{{ route('admin.news.edit', $item) }}" class="bg-gray-800 px-3 py-1 rounded-lg text-sm">Редагувати</a>
                    <form method="POST" action="{{ route('admin.news.destroy', $item) }}" onsubmit="return confirm('Видалити новину?')">
                        @csrf @method('DELETE')
                        <button class="bg-red-900/60 text-red-200 px-3 py-1 rounded-lg text-sm">Видалити</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="p-6 text-gray-500">Новин ще немає.</p>
        @endforelse
    </div>
    <div class="mt-4">{{ $news->links() }}</div>
@endsection
