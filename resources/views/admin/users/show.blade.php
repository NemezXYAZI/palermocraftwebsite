@extends('admin.layout')

@section('title', $target->name.' — адмін-панель')

@php
    $targetSkin = $target->skin;
    $targetAvatar = $targetSkin ? route('skins.preview', $targetSkin).'?v='.$targetSkin->hash : null;
@endphp

@section('admin')
    <a class="back-link" href="{{ route('admin.users.index') }}"><x-icon name="arrow-left" /> Гравці</a>
    <div class="admin__head" style="margin-top: 12px">
        <div class="cluster">
            <x-head :name="$target->minecraft_name ?: $target->name" :src="$targetAvatar" size="52" />
            <h1>{{ $target->name }}</h1>
            @if ($target->isBanned())
                <span class="badge badge--danger">заблоковано</span>
            @endif
        </div>
    </div>

    <div class="grid grid--2" style="align-items: start">
        <section class="card">
            <dl class="kv">
                <dt>Email</dt><dd>{{ $target->email }}</dd>
                <dt>Нік у грі</dt><dd>{{ $target->minecraft_name ?? 'не привʼязано' }}</dd>
                <dt>UUID</dt><dd><code>{{ $target->minecraft_uuid ?? '—' }}</code></dd>
                <dt>Discord</dt><dd>{{ $target->discord_username ?? '—' }} @if ($target->discord_id)<span class="table__sub">{{ $target->discord_id }}</span>@endif</dd>
                <dt>Telegram</dt><dd>{{ $target->telegram_username ?? '—' }} @if ($target->telegram_id)<span class="table__sub">{{ $target->telegram_id }}</span>@endif</dd>
                <dt>Скін</dt><dd>{{ ['approved' => 'схвалено', 'pending' => 'на перевірці', 'rejected' => 'відхилено'][$targetSkin?->status] ?? 'немає' }}</dd>
                <dt>Реєстрація</dt><dd>{{ $target->created_at->format('d.m.Y H:i') }}</dd>
            </dl>
        </section>

        <div class="stack">
            @if (auth()->user()->isAdmin())
                <form method="POST" action="{{ route('admin.users.role', $target) }}" class="card form">
                    @csrf
                    <div class="field">
                        <label class="label" for="role">Роль</label>
                        <select id="role" name="role" class="select">
                            @foreach (\App\Models\User::ROLES as $role)
                                <option value="{{ $role }}" @selected($target->role === $role)>{{ ['player' => 'Гравець', 'moderator' => 'Модератор', 'admin' => 'Адміністратор'][$role] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><button class="btn btn--outline">Зберегти роль</button></div>
                </form>
            @endif

            @if ($target->isBanned())
                <form method="POST" action="{{ route('admin.users.unban', $target) }}" class="card card--accent accent-sun form">
                    @csrf
                    <p class="mb-0"><strong>Причина:</strong> {{ $target->ban_reason }}</p>
                    <p class="faint mb-0">Заблоковано {{ $target->banned_at->format('d.m.Y H:i') }}</p>
                    <div><button class="btn btn--success">Зняти блокування</button></div>
                </form>
            @else
                <form method="POST" action="{{ route('admin.users.ban', $target) }}" class="card form">
                    @csrf
                    <div class="field">
                        <label class="label" for="reason">Заблокувати на сайті й у грі</label>
                        <input id="reason" type="text" name="reason" required maxlength="255" placeholder="Причина — її побачить гравець" class="input">
                    </div>
                    <div><button class="btn btn--danger" data-confirm="Точно заблокувати?"><x-icon name="ban" /> Заблокувати</button></div>
                </form>
            @endif
        </div>
    </div>
@endsection
