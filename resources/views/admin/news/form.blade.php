@extends('admin.layout')

@section('title', ($item->exists ? 'Редагування новини' : 'Нова новина').' — адмін-панель')

@section('admin')
    <a class="back-link" href="{{ route('admin.news.index') }}"><x-icon name="arrow-left" /> Новини</a>
    <div class="admin__head" style="margin-top: 12px">
        <h1>{{ $item->exists ? 'Редагування новини' : 'Нова новина' }}</h1>
    </div>

    <form method="POST" action="{{ $item->exists ? route('admin.news.update', $item) : route('admin.news.store') }}" class="card form">
        @csrf
        @if ($item->exists)
            @method('PUT')
        @endif
        <div class="field">
            <label class="label" for="title">Заголовок</label>
            <input id="title" name="title" required maxlength="160" value="{{ old('title', $item->title) }}" @class(['input', 'is-invalid' => $errors->has('title')])>
        </div>
        <div class="field">
            <label class="label" for="slug">Адреса (необовʼязково, латиницею)</label>
            <input id="slug" name="slug" maxlength="160" value="{{ old('slug', $item->slug) }}" placeholder="створиться із заголовка" @class(['input', 'input--mono', 'is-invalid' => $errors->has('slug')])>
        </div>
        <div class="field">
            <label class="label" for="body">Текст</label>
            <textarea id="body" name="body" required rows="16" @class(['textarea', 'is-invalid' => $errors->has('body')])>{{ old('body', $item->body) }}</textarea>
            <span class="help">Порожній рядок — новий абзац. HTML не підтримується.</span>
        </div>
        <label class="check">
            <input type="checkbox" name="publish" value="1" @checked(old('publish', $item->published_at !== null))>
            Опублікувати на сайті
        </label>
        <div class="cluster">
            <button class="btn btn--primary"><x-icon name="check" /> Зберегти</button>
            <a class="btn btn--ghost" href="{{ route('admin.news.index') }}">Скасувати</a>
        </div>
    </form>
@endsection
