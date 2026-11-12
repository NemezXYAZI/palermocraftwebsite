@extends('layouts.app')

@section('content')
    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8 w-full">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-6">
            <aside class="lg:w-56 bg-palermo-card border border-gray-800 rounded-2xl p-3 h-fit flex lg:flex-col gap-1 overflow-x-auto">
                <p class="hidden lg:block px-3 py-2 text-xs uppercase tracking-wider text-yellow-300">Адмін-панель</p>
                @php($nav = [
                    ['admin.dashboard', 'Огляд', 'admin.dashboard'],
                    ['admin.users.index', 'Гравці', 'admin.users.*'],
                    ['admin.skins.index', 'Скіни', 'admin.skins.*'],
                ])
                @foreach ($nav as [$route, $label, $pattern])
                    <a href="{{ route($route) }}" class="px-3 py-2 rounded-xl whitespace-nowrap {{ request()->routeIs($pattern) ? 'bg-gray-800 text-palermo-green font-bold' : 'text-gray-300 hover:bg-gray-800' }}">{{ $label }}</a>
                @endforeach
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.news.index') }}" class="px-3 py-2 rounded-xl whitespace-nowrap {{ request()->routeIs('admin.news.*') ? 'bg-gray-800 text-palermo-green font-bold' : 'text-gray-300 hover:bg-gray-800' }}">Новини</a>
                @endif
                <a href="{{ route('profile.index') }}" class="px-3 py-2 rounded-xl whitespace-nowrap text-gray-500 hover:bg-gray-800 lg:mt-4">← На сайт</a>
            </aside>
            <section class="flex-1 min-w-0">
                @include('partials.flash')
                @yield('admin')
            </section>
        </div>
    </main>
@endsection
