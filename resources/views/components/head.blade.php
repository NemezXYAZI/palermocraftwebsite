@props(['name' => '?', 'src' => null, 'size' => 40, 'round' => false])
{{-- Голова гравця, вирізана зі скіна прямо в CSS (працює і для 64×64, і для 64×32) --}}
<span {{ $attributes->class(['head', 'head--round' => $round]) }} style="--s: {{ (int) $size }}px" aria-hidden="true">
    <span class="head__fallback">{{ mb_substr($name ?: '?', 0, 1) }}</span>
    @if ($src)
        <span class="head__layer head__layer--face" style="background-image: url('{{ $src }}')"></span>
        <span class="head__layer head__layer--hat" style="background-image: url('{{ $src }}')"></span>
    @endif
</span>
