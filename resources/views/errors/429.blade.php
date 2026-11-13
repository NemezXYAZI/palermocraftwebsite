@extends('layouts.minimal')

@section('title', __('Забагато запитів'))

@section('content')
    <img class="error-page__emblem" src="{{ asset('images/logo.svg') }}" alt="" width="120" height="120">
    <p class="error-page__code text-gold">429</p>
    <h1>{{ __('Повільніше, Мандрівнику') }}</h1>
    <p class="lead">{{ __('Забагато спроб за короткий час. Зачекай хвилинку й спробуй знову.') }}</p>
    <div class="cluster" style="justify-content: center">
        <a class="btn btn--primary" href="{{ url('/') }}">{{ __('На головну') }}</a>
    </div>
@endsection
