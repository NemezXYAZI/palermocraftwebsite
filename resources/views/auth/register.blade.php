@extends('layouts.app')

@section('title', __('Створити акаунт'))
@section('noindex', true)

@section('content')
    <div class="auth">
        @include('auth.partials.aside', [
            'heading' => __('Новий Мандрівник'),
            'text' => __('Акаунт дає скін для всіх лаунчерів, привʼязку ніку та вхід у гру однією кнопкою в Telegram чи Discord.'),
        ])

        <div class="auth__main">
            <div class="auth__card">
                <h1>{{ __('Створити акаунт') }}</h1>
                <p class="muted">{{ __('Уже є акаунт?') }} <a href="{{ route('login') }}">{{ __('Увійди') }}</a>.</p>

                @include('partials.flash')

                <form method="POST" action="{{ route('register.store') }}" class="form">
                    @csrf
                    <div class="field">
                        <label class="label" for="name">{{ __('Імʼя на сайті') }}</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required maxlength="32" autocomplete="username"
                               @class(['input', 'is-invalid' => $errors->has('name')]) placeholder="{{ __('Мандрівник') }}">
                        <span class="help">{{ __('Нік у грі привʼяжеш окремо в профілі.') }}</span>
                    </div>
                    <div class="field">
                        <label class="label" for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                               @class(['input', 'is-invalid' => $errors->has('email')]) placeholder="you@example.com">
                    </div>
                    <div class="grid grid--2">
                        <div class="field">
                            <label class="label" for="password">{{ __('Пароль') }}</label>
                            <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password"
                                   @class(['input', 'is-invalid' => $errors->has('password')])>
                        </div>
                        <div class="field">
                            <label class="label" for="password_confirmation">{{ __('Ще раз') }}</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password" class="input">
                        </div>
                    </div>
                    <span class="help">{{ __('Щонайменше 8 символів.') }}</span>
                    <button type="submit" class="btn btn--primary btn--lg btn--block"><x-icon name="sparkles" /> {{ __('Створити акаунт') }}</button>
                    <p class="help text-center mb-0">{{ __('Реєструючись, ти погоджуєшся з') }} <a href="{{ route('rules') }}">{{ __('правилами сервера') }}</a>.</p>
                </form>
            </div>
        </div>
    </div>
@endsection
