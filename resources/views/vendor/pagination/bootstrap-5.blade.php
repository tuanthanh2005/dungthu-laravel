@if ($paginator->hasPages())
@php
    $current   = $paginator->currentPage();
    $last      = $paginator->lastPage();

    // Build the set of page numbers to always show:
    // always: 1, last, current, current-1, current+1
    $window = collect(range(max(1, $current - 1), min($last, $current + 1)));
    $always = $window->merge([1, $last])->unique()->sort()->values();

    // Build final list with '...' gaps
    $pages = collect();
    $prev  = null;
    foreach ($always as $p) {
        if ($prev !== null && $p - $prev > 1) {
            $pages->push('...');
        }
        $pages->push($p);
        $prev = $p;
    }

    // Get URL map from $elements
    $urlMap = [];
    foreach ($elements as $element) {
        if (is_array($element)) {
            foreach ($element as $page => $url) {
                $urlMap[$page] = $url;
            }
        }
    }
@endphp
    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">&lsaquo;</a>
                </li>
            @endif

            {{-- Smart page list --}}
            @foreach ($pages as $p)
                @if ($p === '...')
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">&hellip;</span></li>
                @elseif ($p == $current)
                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $p }}</span></li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $urlMap[$p] ?? $paginator->url($p) }}">{{ $p }}</a>
                    </li>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
