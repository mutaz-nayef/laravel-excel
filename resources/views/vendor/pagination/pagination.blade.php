@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="pagination-nav">

    <ul class="pagination-list">
        {{-- Info --}}
        <div class="pagination-info">
            {{ __('Showing') }}
            @if ($paginator->firstItem())
            <span>{{ $paginator->firstItem() }}</span> {{ __('to') }}
            <span>{{ $paginator->lastItem() }}</span>
            @else
            {{ $paginator->count() }}
            @endif
            {{ __('of') }} <span>{{ $paginator->total() }}</span> {{ __('results') }}
        </div>

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
        <li><span class="pagination-link disabled">‹</span></li>
        @else
        <li>
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link" data-ajax="true">‹</a>
        </li>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
        @if (is_string($element))
        <li><span class="pagination-link disabled">{{ $element }}</span></li>
        @endif

        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <li><span class="pagination-link active">{{ $page }}</span></li>
        @else
        <li>
            <a href="{{ $url }}" class="pagination-link" data-ajax="true">{{ $page }}</a>
        </li>
        @endif
        @endforeach
        @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
        <li>
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link" data-ajax="true">›</a>
        </li>
        @else
        <li><span class="pagination-link disabled">›</span></li>
        @endif
    </ul>

    {{-- Mobile View --}}
    <div class="mobile-pagination">
        @if ($paginator->onFirstPage())
        <button class="pagination-link prim disabled">{{ __('Previous') }}</button>
        @else
        <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link" data-ajax="true">{{ __('Previous') }}</a>
        @endif

        @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link" data-ajax="true">{{ __('Next') }}</a>
        @else
        <span class="pagination-link disabled">{{ __('Next') }}</span>
        @endif
    </div>

</nav>
@endif