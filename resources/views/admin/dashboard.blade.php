@extends('admin.layout')

@section('title', 'Адмін-панель - PalermoCraft')

@section('admin')
    <h2 class="text-2xl font-bold mb-6">Огляд</h2>
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        @foreach ([
            ['Акаунтів', $stats['users'], route('admin.users.index')],
            ['Привʼязали нік', $stats['linked'], route('admin.users.index')],
            ['Заблоковано', $stats['banned'], route('admin.users.index', ['filter' => 'banned'])],
            ['Скінів на перевірці', $stats['pending_skins'], route('admin.skins.index')],
            ['Новин', $stats['news'], auth()->user()->isAdmin() ? route('admin.news.index') : null],
        ] as [$label, $value, $href])
            <a @if($href) href="{{ $href }}" @endif class="bg-palermo-card border border-gray-800 rounded-2xl p-5 hover:border-palermo-green/50 transition-colors">
                <p class="text-gray-400 text-sm">{{ $label }}</p>
                <p class="text-3xl font-bold {{ $label === 'Скінів на перевірці' && $value > 0 ? 'text-yellow-300' : 'text-palermo-green' }}">{{ $value }}</p>
            </a>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-palermo-card border border-gray-800 rounded-2xl p-6">
            <h3 class="font-bold mb-4">Сервер {{ $server['address'] }}</h3>
            @if ($server['online'])
                <p class="text-palermo-green font-bold">● Онлайн — {{ $server['players'] }}/{{ $server['max'] }} гравців</p>
                <p class="text-xs text-gray-500 mt-1">{{ $server['version'] }}</p>
            @else
                <p class="text-red-400 font-bold">● Офлайн або недоступний</p>
            @endif
        </div>
        <div class="bg-palermo-card border border-gray-800 rounded-2xl p-6">
            <h3 class="font-bold mb-4">Нові акаунти</h3>
            <ul class="space-y-2 text-sm">
                @foreach ($recentUsers as $u)
                    <li class="flex justify-between">
                        <a href="{{ route('admin.users.show', $u) }}" class="hover:text-palermo-green">{{ $u->name }} @if($u->minecraft_name)<span class="text-gray-500">({{ $u->minecraft_name }})</span>@endif</a>
                        <span class="text-gray-500">{{ $u->created_at->format('d.m H:i') }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
