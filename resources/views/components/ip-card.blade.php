@inject('serverStatus', 'App\Services\ServerStatus')
@php($s = $serverStatus->get())
<div {{ $attributes->merge(['class' => 'ip-card']) }}>
    <span class="ip-card__label">Адреса сервера</span>
    @if ($s['announced'])
        <div class="ip-card__row">
            <span class="ip-card__address" translate="no">{{ $s['address'] }}</span>
            <button type="button" class="btn btn--primary" data-copy="{{ $s['address'] }}" data-copied="Скопійовано!">
                <x-icon name="copy" />
                <span data-copy-label>Копіювати</span>
            </button>
        </div>
    @else
        <div class="ip-card__row">
            <span class="ip-card__soon">Оголосимо перед стартом сезону</span>
        </div>
    @endif
    <div class="ip-card__meta">
        <span>
            @if ($s['online'])
                <span class="dot dot--online"></span>
                Онлайн: {{ $s['players'] }}{{ $s['max'] ? ' з '.$s['max'] : '' }}
            @elseif ($s['announced'])
                <span class="dot"></span>
                Сервер зараз офлайн
            @else
                <x-icon name="clock" />
                Скоро
            @endif
        </span>
        <span><x-icon name="gamepad-2" /> Java Edition {{ config('palermo.game_version') }}</span>
        <span><x-icon name="sparkles" /> Без модів</span>
    </div>
</div>
