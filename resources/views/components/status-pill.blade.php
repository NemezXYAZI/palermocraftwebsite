@inject('serverStatus', 'App\Services\ServerStatus')
@php($s = $serverStatus->get())
<a href="{{ route('guide') }}#join" {{ $attributes->merge(['class' => 'status-pill']) }}
   title="{{ $s['online'] ? __('Сервер онлайн: :address', ['address' => $s['address']]) : ($s['announced'] ? __('Сервер зараз недоступний') : __('Сезон ще не стартував')) }}">
    <span class="dot {{ $s['online'] ? 'dot--online' : '' }}"></span>
    <span class="status-pill__label">
        @if ($s['online'])
            {{ __(':count онлайн', ['count' => $s['players']]) }}
        @elseif ($s['announced'])
            {{ __('Офлайн') }}
        @else
            {{ __('Скоро старт') }}
        @endif
    </span>
</a>
