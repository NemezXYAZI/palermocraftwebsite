@extends('layouts.minimal')

@section('title', __('Доступ закрито'))

@section('content')
    <img class="error-page__emblem" src="{{ asset('images/logo.svg') }}" alt="" width="120" height="120">
    <p class="error-page__code text-gold">403</p>
    <h1>{{ __('Ця брама зачинена') }}</h1>
    <p class="lead">{{ __('У тебе немає прав на цю сторінку. Якщо вважаєш, що це помилка, — напиши адміністрації.') }}</p>
    <div class="cluster" style="justify-content: center">
        <a class="btn btn--primary" href="{{ url('/') }}">{{ __('На головну') }}</a>
        <a class="btn btn--ghost" href="{{ url('/guide') }}">{{ __('Путівник') }}</a>
    </div>
@endsection
