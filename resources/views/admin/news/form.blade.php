@extends('admin.layout')

@section('title', ($item->exists ? 'Редагування' : 'Нова новина').' - Адмін-панель')

@section('admin')
    <a href="{{ route('admin.news.index') }}" class="text-sm text-gray-400 hover:text-palermo-green">← Новини</a>
    <h2 class="text-2xl font-bold mt-2 mb-6">{{ $item->exists ? 'Редагування новини' : 'Нова новина' }}</h2>
    <form method="POST" action="{{ $item->exists ? route('admin.news.update', $item) : route('admin.news.store') }}" class="bg-palermo-card border border-gray-800 rounded-2xl p-6 space-y-4">
        @csrf
        @if ($item->exists) @method('PUT') @endif
        <div>
            <label class="block text-sm text-gray-400 mb-2">Заголовок</label>
            <input name="title" required maxlength="160" value="{{ old('title', $item->title) }}" class="w-full bg-gray-900 border border-gray-700 px-4 py-2 rounded-xl">
        </div>
        <div>
            <label class="block text-sm text-gray-400 mb-2">Адреса (необовʼязково, латиницею)</label>
            <input name="slug" maxlength="160" value="{{ old('slug', $item->slug) }}" placeholder="створиться із заголовка" class="w-full bg-gray-900 border border-gray-700 px-4 py-2 rounded-xl">
        </div>
        <div>
            <label class="block text-sm text-gray-400 mb-2">Текст (порожній рядок = новий абзац)</label>
            <textarea name="body" required rows="14" class="w-full bg-gray-900 border border-gray-700 px-4 py-2 rounded-xl">{{ old('body', $item->body) }}</textarea>
        </div>
        <label class="flex items-center gap-2"><input type="checkbox" name="publish" value="1" @checked(old('publish', $item->published_at !== null))> Опублікувати</label>
        <button class="bg-palermo-green text-black font-bold px-6 py-3 rounded-xl">Зберегти</button>
    </form>
@endsection
