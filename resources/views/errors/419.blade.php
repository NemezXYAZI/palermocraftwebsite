@extends('layouts.minimal')

@section('title', 'Сесія застаріла')

@section('content')
    @include('partials.emblem', ['class' => 'error-page__emblem', 'label' => ''])
    <p class="error-page__code text-gold">419</p>
    <h1>Сесія застаріла</h1>
    <p class="lead">Сторінка була відкрита занадто довго. Онови її та спробуй ще раз.</p>
    <div class="cluster" style="justify-content: center">
        <a class="btn btn--primary" href="{{ url()->previous() }}">Повернутися назад</a>
    </div>
@endsection
