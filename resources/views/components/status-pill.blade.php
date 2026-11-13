@inject('serverStatus', 'App\Services\ServerStatus')
@php($s = $serverStatus->get())
<a href="{{ route('guide') }}#join" {{ $attributes->merge(['class' => 'status-pill']) }}
   title="{{ $s['online'] ? 'Сервер онлайн: '.$s['address'] : 'Сервер зараз недоступний' }}">
    <span class="dot {{ $s['online'] ? 'dot--online' : '' }}"></span>
    <span class="status-pill__label">
        @if ($s['online'])
            {{ $s['players'] }} онлайн
        @else
            Офлайн
        @endif
    </span>
</a>
