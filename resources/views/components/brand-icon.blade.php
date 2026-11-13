@props(['name'])
<svg {{ $attributes->merge(['class' => 'icon']) }} viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">{!! \App\Support\Icons::brand($name) !!}</svg>
