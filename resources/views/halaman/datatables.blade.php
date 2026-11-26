<nav class="flex overflow-hidden rounded border border-gray-200 text-xs" aria-label="Pagination">

    @if ($paginator->onFirstPage())
        <span class="px-3 py-1.5 text-gray-400">Previous</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
            class="px-3 py-1.5 text-blue-600 hover:bg-gray-50">Previous</a>
    @endif

    @foreach ($elements as $element )
        @if (is_string($element))
            <span class="border-s border-gray-200 px-3 py-1.5 text-gray-400">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span aria-current="page"
                        class="border-s border-blue-600 bg-blue-600 px-3 py-1.5 font-medium text-white">{{ $page }}</span>
                @else
                    <a href="{{ $url }}"
                        class="border-s border-gray-200 px-3 py-1.5 text-blue-600 hover:bg-gray-50">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
            class="border-s border-gray-200 px-3 py-1.5 text-blue-600 hover: bg-gray-50">Next</a>
    @else
            <span class="border-s border-gray-200 px-3 py-1.5 text-gray-400">Next</span>
    @endif
</nav>