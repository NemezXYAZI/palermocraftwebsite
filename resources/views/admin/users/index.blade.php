@extends('admin.layout')

@section('title', 'Гравці - Адмін-панель')

@section('admin')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold">Гравці</h2>
        <form method="GET" class="flex gap-2">
            <input type="text" name="q" value="{{ $q }}" placeholder="Логін, email, нік, Discord, Telegram"
                   class="bg-gray-900 border border-gray-700 px-4 py-2 rounded-xl w-72 focus:outline-none focus:border-palermo-green">
            <select name="filter" class="bg-gray-900 border border-gray-700 px-3 py-2 rounded-xl">
                <option value="">Усі</option>
                <option value="banned" @selected(request('filter') === 'banned')>Заблоковані</option>
                <option value="staff" @selected(request('filter') === 'staff')>Команда</option>
            </select>
            <button class="bg-palermo-green text-black font-bold px-4 rounded-xl">Знайти</button>
        </form>
    </div>

    <div class="bg-palermo-card border border-gray-800 rounded-2xl overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-gray-400 text-left border-b border-gray-800">
                <tr>
                    <th class="p-3">Логін</th><th class="p-3">Нік у грі</th><th class="p-3">Роль</th>
                    <th class="p-3">Discord</th><th class="p-3">Telegram</th><th class="p-3">Статус</th><th class="p-3">Реєстрація</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $u)
                    <tr class="border-b border-gray-800/60 hover:bg-gray-900/60">
                        <td class="p-3"><a href="{{ route('admin.users.show', $u) }}" class="font-bold hover:text-palermo-green">{{ $u->name }}</a><br><span class="text-xs text-gray-500">{{ $u->email }}</span></td>
                        <td class="p-3">{{ $u->minecraft_name ?? '—' }}</td>
                        <td class="p-3">{{ $u->role }}</td>
                        <td class="p-3">{{ $u->discord_username ?? '—' }}</td>
                        <td class="p-3">{{ $u->telegram_username ?? '—' }}</td>
                        <td class="p-3">@if($u->isBanned())<span class="text-red-400">бан</span>@else<span class="text-palermo-green">активний</span>@endif</td>
                        <td class="p-3 text-gray-500">{{ $u->created_at->format('d.m.Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-6 text-center text-gray-500">Нікого не знайдено.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
@endsection
