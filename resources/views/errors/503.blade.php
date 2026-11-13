@extends('layouts.minimal')

@section('title', __('Оновлення'))

@push('head')
    <meta http-equiv="refresh" content="20">
@endpush

@section('content')
    <img class="error-page__emblem" src="{{ asset('images/logo.svg') }}" alt="" width="120" height="120">
    <p class="error-page__code text-gold">503</p>
    <h1>{{ __('Сайт оновлюється') }}</h1>
    <p class="lead">{{ __('Ми ставимо нову версію — це займе хвилину-дві. Сторінка оновиться сама.') }}</p>
    <div class="cluster" style="justify-content: center">
        <a class="btn btn--primary" href="{{ url()->current() }}">{{ __('Оновити') }}</a>
    </div>
@endsection
