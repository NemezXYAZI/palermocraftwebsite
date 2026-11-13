@extends('layouts.app')

@section('title', 'Вхід')
@section('noindex', true)

@section('content')
    <div class="auth">
        @include('auth.partials.aside', [
            'heading' => 'З поверненням, Мандрівнику',
            'text' => 'Твій профіль — це скін, який бачать усі гравці, нік у грі та вхід через Telegram чи Discord.',
        ])

        <div class="auth__main">
            <div class="auth__card">
                <h1>Вхід</h1>
                <p class="muted">Немає акаунта? <a href="{{ route('register') }}">Створи його</a> — це хвилина.</p>

                @include('partials.flash')
                @include('auth.partials.social')

                <form method="POST" action="{{ route('login.store') }}" class="form">
                    @csrf
                    <div class="field">
                        <label class="label" for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                               @class(['input', 'is-invalid' => $errors->has('email')]) placeholder="you@example.com">
                    </div>
                    <div class="field">
                        <label class="label" for="password">Пароль</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password" class="input">
                    </div>
                    <label class="check">
                        <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                        Запамʼятати мене
                    </label>
                    <button type="submit" class="btn btn--primary btn--lg btn--block"><x-icon name="log-in" /> Увійти</button>
                </form>

                <p class="auth__switch">Грати можна й без акаунта — <a href="{{ route('guide') }}#join">як зайти на сервер</a>.</p>
            </div>
        </div>
    </div>
@endsection
