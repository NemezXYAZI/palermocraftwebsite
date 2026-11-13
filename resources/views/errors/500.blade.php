@extends('layouts.minimal')

@section('title', __('Помилка сервера'))

@section('content')
    <img class="error-page__emblem" src="{{ asset('images/logo.svg') }}" alt="" width="120" height="120">
    <p class="error-page__code text-gold">500</p>
    <h1>{{ __('Щось зламалося в глибинах') }}</h1>
    <p class="lead">{{ __('Ми вже знаємо про проблему. Спробуй оновити сторінку трохи згодом.') }}</p>
    <div class="cluster" style="justify-content: center">
        <a class="btn btn--primary" href="{{ url('/') }}">{{ __('На головну') }}</a>
        <a class="btn btn--ghost" href="{{ url('/guide') }}">{{ __('Путівник') }}</a>
    </div>
@endsection
