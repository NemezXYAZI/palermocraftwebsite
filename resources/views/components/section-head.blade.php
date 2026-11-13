@props(['kicker' => null, 'title', 'lead' => null, 'align' => 'center', 'level' => 'h2', 'titleId' => null])
<header {{ $attributes->class(['section-head', 'section-head--left' => $align === 'left']) }}>
    @if ($kicker)
        <p @class(['kicker', 'kicker--left' => $align === 'left'])>{{ $kicker }}</p>
    @endif
    <{{ $level }} @if ($titleId) id="{{ $titleId }}" @endif>{!! $title !!}</{{ $level }}>
    @if ($lead)
        <p class="lead">{{ $lead }}</p>
    @endif
    {{ $slot }}
</header>
