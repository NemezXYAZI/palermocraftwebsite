@inject('serverStatus', 'App\Services\ServerStatus')
@php($s = $serverStatus->get())
<a href="{{ route('guide') }}#join" {{ $attributes->merge(['class' => 'status-pill']) }}
   title="{{ $s['online'] ? 'Сервер онлайн: '.$s['address'] : ($s['announced'] ? 'Сервер зараз недоступний' : 'Сезон ще не стартував') }}">
    <span class="dot {{ $s['online'] ? 'dot--online' : '' }}"></span>
    <span class="status-pill__label">
        @if ($s['online'])
            {{ $s['players'] }} онлайн
        @elseif ($s['announced'])
            Офлайн
        @else
            Скоро старт
        @endif
    </span>
</a>
