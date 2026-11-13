@if ($paginator->hasPages())
    <nav class="pagination" aria-label="{{ __('Сторінки') }}">
        @if ($paginator->onFirstPage())
            <span class="is-disabled" aria-disabled="true">{{ __('← Назад') }}</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev">{{ __('← Назад') }}</a>
        @endif
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next">{{ __('Далі →') }}</a>
        @else
            <span class="is-disabled" aria-disabled="true">{{ __('Далі →') }}</span>
        @endif
    </nav>
@endif
