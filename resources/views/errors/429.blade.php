@extends('layouts.minimal')

@section('title', 'Забагато запитів')

@section('content')
    @include('partials.emblem', ['class' => 'error-page__emblem', 'label' => ''])
    <p class="error-page__code text-gold">429</p>
    <h1>Повільніше, Мандрівнику</h1>
    <p class="lead">Забагато спроб за короткий час. Зачекай хвилинку й спробуй знову.</p>
    <div class="cluster" style="justify-content: center">
        <a class="btn btn--primary" href="{{ url('/') }}">На головну</a>
    </div>
@endsection
