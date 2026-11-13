@if ($paginator->hasPages())
    <nav class="pagination" aria-label="Сторінки">
        @if ($paginator->onFirstPage())
            <span class="is-disabled" aria-disabled="true">← Назад</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev">← Назад</a>
        @endif
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next">Далі →</a>
        @else
            <span class="is-disabled" aria-disabled="true">Далі →</span>
        @endif
    </nav>
@endif
