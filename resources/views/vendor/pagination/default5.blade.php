@if ($paginator->hasPages())
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center mb-0">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}"
                       @if (method_exists($paginator, 'getPageName'))
                           wire:click.prevent="gotoPage({{ $paginator->currentPage() - 1 }}, '{{ $paginator->getPageName() }}')"
                       @endif
                       rel="prev"
                       aria-label="@lang('pagination.previous')">
                        &lsaquo;
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}"
                                   @if (method_exists($paginator, 'getPageName'))
                                       wire:click.prevent="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                   @endif>
                                   {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}"
                       @if (method_exists($paginator, 'getPageName'))
                           wire:click.prevent="gotoPage({{ $paginator->currentPage() + 1 }}, '{{ $paginator->getPageName() }}')"
                       @endif
                       rel="next"
                       aria-label="@lang('pagination.next')">
                        &rsaquo;
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
<ul class="pagination mb-0">
  <li class="page-item disabled"><a class="page-link">Previous</a></li>
  <li class="page-item"><a class="page-link" href="#">1</a></li>
  <li class="page-item active"><a class="page-link" href="#">2</a></li>
  <li class="page-item"><a class="page-link" href="#">3</a></li>
  <li class="page-item"><a class="page-link" href="#">Next</a></li>
</ul>
