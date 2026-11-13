@if ($paginator->hasPages())
    <nav class="pagination" aria-label="Сторінки">
        @if ($paginator->onFirstPage())
            <span class="is-disabled" aria-disabled="true"><x-icon name="arrow-left" label="Попередня сторінка" /></span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"><x-icon name="arrow-left" label="Попередня сторінка" /></a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="is-disabled" aria-disabled="true">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="is-current" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"><x-icon name="arrow-right" label="Наступна сторінка" /></a>
        @else
            <span class="is-disabled" aria-disabled="true"><x-icon name="arrow-right" label="Наступна сторінка" /></span>
        @endif
    </nav>
@endif
