@extends('admin.layout')

@section('title', 'Новини — адмін-панель')

@section('admin')
    <div class="admin__head">
        <h1>Новини</h1>
        <a href="{{ route('admin.news.create') }}" class="btn btn--primary"><x-icon name="plus" /> Нова новина</a>
    </div>

    @if ($news->isEmpty())
        <div class="empty">
            <x-icon name="newspaper" />
            <h3>Новин ще немає</h3>
            <p class="mb-0">Перша новина зʼявиться на головній сторінці.</p>
        </div>
    @else
        <div class="table-wrap">
            <table class="table">
                <tbody>
                    @foreach ($news as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->title }}</strong>
                                <span class="table__sub">
                                    {{ $item->published_at ? 'Опубліковано '.$item->published_at->format('d.m.Y H:i') : 'Чернетка' }}
                                    @if ($item->author) · {{ $item->author->name }} @endif
                                </span>
                            </td>
                            <td class="nowrap" style="text-align: right">
                                <div class="cluster cluster--sm" style="justify-content: flex-end">
                                    @if ($item->published_at)
                                        <a class="btn btn--ghost btn--sm" href="{{ route('news.show', $item->slug) }}" target="_blank" rel="noopener"><x-icon name="external-link" /> На сайті</a>
                                    @endif
                                    <a class="btn btn--outline btn--sm" href="{{ route('admin.news.edit', $item) }}"><x-icon name="pen-line" /> Редагувати</a>
                                    <form method="POST" action="{{ route('admin.news.destroy', $item) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn--danger btn--sm" data-confirm="Точно видалити?"><x-icon name="trash-2" /></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    {{ $news->links() }}
@endsection
