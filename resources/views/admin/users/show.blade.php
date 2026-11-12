@extends('admin.layout')

@section('title', $target->name.' - Адмін-панель')

@section('admin')
    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-400 hover:text-palermo-green">← Гравці</a>
    <h2 class="text-2xl font-bold mt-2 mb-6">{{ $target->name }}
        @if ($target->isBanned()) <span class="text-sm text-red-400 align-middle">заблоковано</span> @endif
    </h2>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-palermo-card border border-gray-800 rounded-2xl p-6 space-y-2 text-sm">
            <p><span class="text-gray-400">Email:</span> {{ $target->email }}</p>
            <p><span class="text-gray-400">Нік у грі:</span> {{ $target->minecraft_name ?? 'не привʼязано' }}</p>
            <p><span class="text-gray-400">UUID:</span> <span class="font-mono text-xs">{{ $target->minecraft_uuid ?? '—' }}</span></p>
            <p><span class="text-gray-400">Discord:</span> {{ $target->discord_username ?? '—' }} @if($target->discord_id)<span class="text-xs text-gray-500">({{ $target->discord_id }})</span>@endif</p>
            <p><span class="text-gray-400">Telegram:</span> {{ $target->telegram_username ?? '—' }} @if($target->telegram_id)<span class="text-xs text-gray-500">({{ $target->telegram_id }})</span>@endif</p>
            <p><span class="text-gray-400">Баланс:</span> {{ $target->balance }}</p>
            <p><span class="text-gray-400">Скін:</span> {{ $target->skin?->status ?? 'немає' }}</p>
            <p><span class="text-gray-400">Зареєстровано:</span> {{ $target->created_at->format('d.m.Y H:i') }}</p>
        </div>

        <div class="space-y-6">
            @if (auth()->user()->isAdmin())
                <form method="POST" action="{{ route('admin.users.role', $target) }}" class="bg-palermo-card border border-gray-800 rounded-2xl p-6 flex gap-3 items-end">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-sm text-gray-400 mb-2">Роль</label>
                        <select name="role" class="w-full bg-gray-900 border border-gray-700 px-3 py-2 rounded-xl">
                            @foreach (\App\Models\User::ROLES as $role)
                                <option value="{{ $role }}" @selected($target->role === $role)>{{ ['player' => 'Гравець', 'moderator' => 'Модератор', 'admin' => 'Адміністратор'][$role] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="bg-palermo-green text-black font-bold px-4 py-2 rounded-xl">Зберегти</button>
                </form>
            @endif

            @if ($target->isBanned())
                <form method="POST" action="{{ route('admin.users.unban', $target) }}" class="bg-palermo-card border border-red-900/50 rounded-2xl p-6">
                    @csrf
                    <p class="text-sm text-red-300 mb-3">Причина: {{ $target->ban_reason }} ({{ $target->banned_at->format('d.m.Y H:i') }})</p>
                    <button class="bg-gray-800 text-white font-bold px-4 py-2 rounded-xl hover:bg-gray-700">Зняти блокування</button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.users.ban', $target) }}" class="bg-palermo-card border border-gray-800 rounded-2xl p-6 space-y-3">
                    @csrf
                    <label class="block text-sm text-gray-400">Заблокувати на сайті й у грі</label>
                    <input type="text" name="reason" required maxlength="255" placeholder="Причина (побачить гравець)"
                           class="w-full bg-gray-900 border border-gray-700 px-4 py-2 rounded-xl focus:outline-none focus:border-red-500">
                    <button class="bg-red-900/60 text-red-200 font-bold px-4 py-2 rounded-xl hover:bg-red-900">Заблокувати</button>
                </form>
            @endif
        </div>
    </div>
@endsection
