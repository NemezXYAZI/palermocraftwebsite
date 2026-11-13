@extends('layouts.minimal')

@section('title', 'Доступ закрито')

@section('content')
    @include('partials.emblem', ['class' => 'error-page__emblem', 'label' => ''])
    <p class="error-page__code text-gold">403</p>
    <h1>Ця брама зачинена</h1>
    <p class="lead">У тебе немає прав на цю сторінку. Якщо вважаєш, що це помилка, — напиши адміністрації.</p>
    <div class="cluster" style="justify-content: center">
        <a class="btn btn--primary" href="{{ url('/') }}">На головну</a>
        <a class="btn btn--ghost" href="{{ url('/guide') }}">Путівник</a>
    </div>
@endsection
