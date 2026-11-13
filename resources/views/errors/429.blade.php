@extends('layouts.minimal')

@section('title', 'Забагато запитів')

@section('content')
    <img class="error-page__emblem" src="{{ asset('images/logo.svg') }}" alt="" width="120" height="120">
    <p class="error-page__code text-gold">429</p>
    <h1>Повільніше, Мандрівнику</h1>
    <p class="lead">Забагато спроб за короткий час. Зачекай хвилинку й спробуй знову.</p>
    <div class="cluster" style="justify-content: center">
        <a class="btn btn--primary" href="{{ url('/') }}">На головну</a>
    </div>
@endsection
