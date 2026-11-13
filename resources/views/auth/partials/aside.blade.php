<aside class="auth__aside" aria-hidden="true">
    @include('partials.hero-visual', ['class' => 'auth__emblem'])
    <div class="auth__aside-copy">
        <p class="kicker kicker--left">{{ __('PALERMOCRAFT · Сезон I') }}</p>
        <h2>{{ $heading }}</h2>
        <p class="muted mb-0">{{ $text }}</p>
    </div>
</aside>
