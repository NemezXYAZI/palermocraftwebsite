@extends('layouts.minimal')

@section('title', 'Сторінку не знайдено')

@section('content')
    <img class="error-page__emblem" src="{{ asset('images/logo.svg') }}" alt="" width="120" height="120">
    <p class="error-page__code text-gold">404</p>
    <h1>Цю сторінку поглинула Безодня</h1>
    <p class="lead">Можливо, посилання застаріло або в адресі помилка. Повертайся на знайомі стежки.</p>
    <div class="cluster" style="justify-content: center">
        <a class="btn btn--primary" href="{{ url('/') }}">На головну</a>
        <a class="btn btn--ghost" href="{{ url('/guide') }}">Путівник</a>
    </div>
@endsection
