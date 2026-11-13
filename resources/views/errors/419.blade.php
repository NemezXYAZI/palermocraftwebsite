@extends('layouts.minimal')

@section('title', __('Сесія застаріла'))

@section('content')
    <img class="error-page__emblem" src="{{ asset('images/logo.svg') }}" alt="" width="120" height="120">
    <p class="error-page__code text-gold">419</p>
    <h1>{{ __('Сесія застаріла') }}</h1>
    <p class="lead">{{ __('Сторінка була відкрита занадто довго. Онови її та спробуй ще раз.') }}</p>
    <div class="cluster" style="justify-content: center">
        <a class="btn btn--primary" href="{{ url()->previous() }}">{{ __('Повернутися назад') }}</a>
    </div>
@endsection
