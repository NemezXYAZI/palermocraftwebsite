@inject('serverStatus', 'App\Services\ServerStatus')
@php($s = $serverStatus->get())
<div {{ $attributes->merge(['class' => 'ip-card']) }}>
    <span class="ip-card__label">{{ __('Адреса сервера') }}</span>
    @if ($s['announced'])
        <div class="ip-card__row">
            <span class="ip-card__address" translate="no">{{ $s['address'] }}</span>
            <button type="button" class="btn btn--primary" data-copy="{{ $s['address'] }}" data-copied="{{ __('Скопійовано!') }}">
                <x-icon name="copy" />
                <span data-copy-label>{{ __('Копіювати') }}</span>
            </button>
        </div>
    @else
        <div class="ip-card__row">
            <span class="ip-card__soon">{{ __('Оголосимо перед стартом сезону') }}</span>
        </div>
    @endif
    <div class="ip-card__meta">
        <span>
            @if ($s['online'])
                <span class="dot dot--online"></span>
                {{ $s['max'] ? __('Онлайн: :players з :max', ['players' => $s['players'], 'max' => $s['max']]) : __('Онлайн: :players', ['players' => $s['players']]) }}
            @elseif ($s['announced'])
                <span class="dot"></span>
                {{ __('Сервер зараз офлайн') }}
            @else
                <x-icon name="clock" />
                {{ __('Скоро') }}
            @endif
        </span>
        <span><x-icon name="gamepad-2" /> Java Edition {{ config('palermo.game_version') }}</span>
        <span><x-icon name="sparkles" /> {{ __('Без модів') }}</span>
    </div>
</div>
