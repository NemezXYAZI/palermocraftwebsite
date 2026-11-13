@extends('layouts.app')

@section('noindex', true)

@php
    $pendingSkins = \App\Models\Skin::where('status', \App\Models\Skin::STATUS_PENDING)->count();
    $adminNav = [
        ['admin.dashboard', 'admin.dashboard', 'layers', 'Огляд', null],
        ['admin.users.index', 'admin.users.*', 'users', 'Гравці', null],
        ['admin.skins.index', 'admin.skins.*', 'shirt', 'Скіни', $pendingSkins ?: null],
    ];
    if (auth()->user()->isAdmin()) {
        $adminNav[] = ['admin.news.index', 'admin.news.*', 'newspaper', 'Новини', null];
    }
@endphp

@section('content')
    <div class="container admin">
        <nav class="admin__nav" aria-label="Адмін-панель">
            <p class="admin__nav-title">Адмін-панель</p>
            @foreach ($adminNav as [$route, $pattern, $icon, $label, $count])
                <a href="{{ route($route) }}" class="menu-link" @if (request()->routeIs($pattern)) aria-current="page" @endif>
                    <x-icon :name="$icon" /> {{ $label }}
                    @if ($count)
                        <span class="badge badge--warn count">{{ $count }}</span>
                    @endif
                </a>
            @endforeach
            <hr style="margin: 8px 6px">
            <a href="{{ route('profile.index') }}" class="menu-link"><x-icon name="arrow-left" /> На сайт</a>
        </nav>
        <div>
            @include('partials.flash')
            @yield('admin')
        </div>
    </div>
@endsection
