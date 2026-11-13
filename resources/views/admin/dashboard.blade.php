@extends('admin.layout')

@section('title', 'Адмін-панель')

@section('admin')
    <div class="admin__head">
        <h1>Огляд</h1>
        <span class="badge badge--gold">{{ ['moderator' => 'Модератор', 'admin' => 'Адміністратор'][auth()->user()->role] ?? '' }}</span>
    </div>

    <div class="grid stats-grid">
        @foreach ([
            ['Акаунтів', $stats['users'], route('admin.users.index'), false],
            ['Привʼязали нік', $stats['linked'], route('admin.users.index'), false],
            ['Заблоковано', $stats['banned'], route('admin.users.index', ['filter' => 'banned']), false],
            ['Скінів на перевірці', $stats['pending_skins'], route('admin.skins.index'), $stats['pending_skins'] > 0],
            ['Новин', $stats['news'], auth()->user()->isAdmin() ? route('admin.news.index') : null, false],
        ] as [$label, $value, $href, $alert])
            @if ($href)
                <a href="{{ $href }}" @class(['card', 'card--hover', 'stat', 'stat--alert' => $alert])>
            @else
                <div @class(['card', 'stat'])>
            @endif
                <span class="stat__label">{{ $label }}</span>
                <span class="stat__value">{{ $value }}</span>
            @if ($href) </a> @else </div> @endif
        @endforeach
        <div class="card stat">
            <span class="stat__label">Сервер {{ $server['address'] }}</span>
            @if ($server['online'])
                <span class="stat__value"><span class="dot dot--online" style="display: inline-block; vertical-align: middle"></span> {{ $server['players'] }}/{{ $server['max'] }}</span>
                <span class="table__sub">{{ $server['version'] }}</span>
            @else
                <span class="stat__value" style="color: var(--text-3)">офлайн</span>
            @endif
        </div>
    </div>

    <section class="card">
        <div class="card__head">
            <h2 class="panel-title mb-0"><x-icon name="users" /> Нові акаунти</h2>
            <a class="btn btn--ghost btn--sm" href="{{ route('admin.users.index') }}">Усі гравці <x-icon name="arrow-right" /></a>
        </div>
        <div class="table-wrap">
            <table class="table">
                <tbody>
                    @forelse ($recentUsers as $u)
                        <tr>
                            <td><a href="{{ route('admin.users.show', $u) }}"><strong>{{ $u->name }}</strong></a> <span class="table__sub">{{ $u->minecraft_name ? 'Нік: '.$u->minecraft_name : 'нік не привʼязано' }}</span></td>
                            <td class="nowrap" style="text-align: right">{{ $u->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td class="faint">Ще нікого.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
