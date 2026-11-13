@extends('layouts.minimal')

@section('title', 'Помилка сервера')

@section('content')
    @include('partials.emblem', ['class' => 'error-page__emblem', 'label' => ''])
    <p class="error-page__code text-gold">500</p>
    <h1>Щось зламалося в глибинах</h1>
    <p class="lead">Ми вже знаємо про проблему. Спробуй оновити сторінку трохи згодом.</p>
    <div class="cluster" style="justify-content: center">
        <a class="btn btn--primary" href="{{ url('/') }}">На головну</a>
        <a class="btn btn--ghost" href="{{ url('/guide') }}">Путівник</a>
    </div>
@endsection
