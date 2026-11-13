@extends('layouts.app')

@section('title', 'Підтримка проєкту')
@section('description', 'PALERMOCRAFT безкоштовний і без переваг за гроші. Як підтримати сервер уже зараз і якою буде підтримка в майбутньому.')

@section('content')
    <section class="page-hero page-hero--center">
        <div class="container container--narrow">
            <p class="kicker">Підтримка</p>
            <h1>Чесна гра — понад усе</h1>
            <p class="lead">PALERMOCRAFT безкоштовний. Броня, ресурси, ранги поселень і Очі Безодні здобуваються лише в грі — і так буде завжди.</p>
        </div>
    </section>

    <section class="section section--tight section--flush-top">
        <div class="container">
            <div class="grid grid--3">
                <article class="card feature accent-life" data-reveal>
                    <span class="icon-tile"><x-icon name="shield-check" /></span>
                    <h3 class="card__title">Без pay-to-win</h3>
                    <p class="card__text">Жодних привілеїв, наборів ресурсів чи «донатних» мечів. Перевагу дають лише час, вміння й команда.</p>
                </article>
                <article class="card feature accent-violet" data-reveal style="--delay: .06s">
                    <span class="icon-tile"><x-icon name="sparkles" /></span>
                    <h3 class="card__title">Лише косметика</h3>
                    <p class="card__text">Коли ми відкриємо підтримку, вона даватиме тільки вигляд: ефекти, оформлення, дрібні прикраси — і ніколи силу.</p>
                </article>
                <article class="card feature accent-gold" data-reveal style="--delay: .12s">
                    <span class="icon-tile"><x-icon name="server" /></span>
                    <h3 class="card__title">На сервер і розвиток</h3>
                    <p class="card__text">Кожна гривня піде на хостинг, нові глави сюжету та інструменти для гравців.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section section--band" aria-labelledby="help-title">
        <div class="container">
            <x-section-head kicker="Уже зараз" title="Як допомогти без грошей" title-id="help-title"
                            lead="Найбільше серверу потрібні живі Мандрівники." />
            <div class="grid grid--4">
                <div class="card card--flat" data-reveal>
                    <span class="icon-tile icon-tile--sm accent-gold"><x-icon name="users" /></span>
                    <h3 class="card__title">Клич друзів</h3>
                    <p class="card__text">Разом легше відбудувати поселення й пройти бос-данж.</p>
                </div>
                <div class="card card--flat" data-reveal style="--delay: .05s">
                    <span class="icon-tile icon-tile--sm accent-sun"><x-icon name="triangle-alert" /></span>
                    <h3 class="card__title">Повідомляй про баги</h3>
                    <p class="card__text">Знайшов помилку чи дюп — напиши адміністрації, а не використовуй його.</p>
                </div>
                <div class="card card--flat" data-reveal style="--delay: .1s">
                    <span class="icon-tile icon-tile--sm accent-life"><x-icon name="hammer" /></span>
                    <h3 class="card__title">Будуй і ділись</h3>
                    <p class="card__text">Скріншоти поселень і пригод потраплять у новини сезону.</p>
                </div>
                <div class="card card--flat" data-reveal style="--delay: .15s">
                    <span class="icon-tile icon-tile--sm accent-frost"><x-icon name="message-circle" /></span>
                    <h3 class="card__title">Будь поруч</h3>
                    <p class="card__text">Ідеї, відгуки й допомога новачкам роблять спільноту живою.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container container--narrow text-center">
            <div class="ornament"><i></i></div>
            <p class="lead mt-lg">Про відкриття підтримки ми напишемо в новинах. А поки — побачимося в Безодні.</p>
            <div class="cluster" style="justify-content: center; margin-top: 22px">
                <a class="btn btn--primary" href="{{ route('guide') }}#join"><x-icon name="play" /> Почати грати</a>
                <a class="btn btn--ghost" href="{{ route('news.index') }}">Новини</a>
            </div>
        </div>
    </section>
@endsection
