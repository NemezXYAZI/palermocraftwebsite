@extends('admin.layout')

@section('title', 'Гравці — адмін-панель')

@section('admin')
    <div class="admin__head">
        <h1>Гравці</h1>
        <form method="GET" class="filters" role="search">
            <input type="search" name="q" value="{{ $q }}" placeholder="Імʼя, email, нік, Discord, Telegram" class="input" aria-label="Пошук гравців">
            <select name="filter" class="select" aria-label="Фільтр">
                <option value="">Усі</option>
                <option value="banned" @selected(request('filter') === 'banned')>Заблоковані</option>
                <option value="staff" @selected(request('filter') === 'staff')>Команда</option>
            </select>
            <button class="btn btn--outline"><x-icon name="search" /> Знайти</button>
        </form>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Акаунт</th>
                    <th scope="col">Нік у грі</th>
                    <th scope="col">Роль</th>
                    <th scope="col">Discord</th>
                    <th scope="col">Telegram</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Реєстрація</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $u)
                    <tr>
                        <td><a href="{{ route('admin.users.show', $u) }}"><strong>{{ $u->name }}</strong></a><span class="table__sub">{{ $u->email }}</span></td>
                        <td>{{ $u->minecraft_name ?? '—' }}</td>
                        <td>{{ ['player' => 'Гравець', 'moderator' => 'Модератор', 'admin' => 'Адміністратор'][$u->role] ?? $u->role }}</td>
                        <td>{{ $u->discord_username ?? '—' }}</td>
                        <td>{{ $u->telegram_username ?? '—' }}</td>
                        <td>
                            @if ($u->isBanned())
                                <span class="badge badge--danger">бан</span>
                            @else
                                <span class="badge badge--success">активний</span>
                            @endif
                        </td>
                        <td class="nowrap">{{ $u->created_at->format('d.m.Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center faint">Нікого не знайдено.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $users->links() }}
@endsection
