@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}"
                       @if (method_exists($paginator, 'getPageName'))
                           wire:click.prevent="gotoPage({{ $paginator->currentPage() - 1 }}, '{{ $paginator->getPageName() }}')"
                       @endif
                       rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;
                    </a>
                </li>
            @endif

            @php
                $start = max(1, $paginator->currentPage() - 3);
                $end = min($paginator->lastPage(), $paginator->currentPage() + 3);
            @endphp

            {{-- Show first page and "..." --}}
            @if ($start > 1)
                <li>
                    <a href="{{ $paginator->url(1) }}"
                       @if (method_exists($paginator, 'getPageName'))
                           wire:click.prevent="gotoPage(1, '{{ $paginator->getPageName() }}')"
                       @endif>1</a>
                </li>
                @if ($start > 2)
                    <li class="disabled"><span>...</span></li>
                @endif
            @endif

            {{-- Page links around current --}}
            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $paginator->currentPage())
                    <li class="active" aria-current="page"><span>{{ $i }}</span></li>
                @else
                    <li>
                        <a href="{{ $paginator->url($i) }}"
                           @if (method_exists($paginator, 'getPageName'))
                               wire:click.prevent="gotoPage({{ $i }}, '{{ $paginator->getPageName() }}')"
                           @endif>{{ $i }}</a>
                    </li>
                @endif
            @endfor

            {{-- Show "..." and last page --}}
            @if ($end < $paginator->lastPage())
                @if ($end < $paginator->lastPage() - 1)
                    <li class="disabled"><span>...</span></li>
                @endif
                <li>
                    <a href="{{ $paginator->url($paginator->lastPage()) }}"
                       @if (method_exists($paginator, 'getPageName'))
                           wire:click.prevent="gotoPage({{ $paginator->lastPage() }}, '{{ $paginator->getPageName() }}')"
                       @endif>{{ $paginator->lastPage() }}</a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}"
                       @if (method_exists($paginator, 'getPageName'))
                           wire:click.prevent="gotoPage({{ $paginator->currentPage() + 1 }}, '{{ $paginator->getPageName() }}')"
                       @endif
                       rel="next" aria-label="@lang('pagination.next')">&rsaquo;
                    </a>
                </li>
            @else
                <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
