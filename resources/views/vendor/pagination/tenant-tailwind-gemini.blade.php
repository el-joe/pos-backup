@if ($paginator->hasPages())
    <nav aria-label="Pagination Navigation" class="flex flex-col gap-3 border-t border-slate-100 px-4 py-4 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-slate-500 dark:text-slate-400">
            {{ __('Showing') }}
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $paginator->firstItem() }}</span>
            {{ __('to') }}
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $paginator->lastItem() }}</span>
            {{ __('of') }}
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $paginator->total() }}</span>
            {{ __('results') }}
        </p>

        <div class="inline-flex flex-wrap items-center gap-1.5">
            @if ($paginator->onFirstPage())
                <span aria-hidden="true" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-300 dark:border-slate-700 dark:text-slate-600">
                    <i class="fa fa-chevron-left rtl:-scale-x-100 text-xs"></i>
                </span>
            @else
                <a class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-600 transition hover:border-brand-500 hover:bg-brand-50 hover:text-brand-600 dark:border-slate-700 dark:text-slate-300 dark:hover:border-brand-500 dark:hover:bg-brand-500/10 dark:hover:text-brand-300"
                   href="{{ $paginator->previousPageUrl() }}"
                   @if (method_exists($paginator, 'getPageName'))
                       wire:click.prevent="gotoPage({{ $paginator->currentPage() - 1 }}, '{{ $paginator->getPageName() }}')"
                   @endif
                   rel="prev"
                   aria-label="@lang('pagination.previous')">
                    <i class="fa fa-chevron-left rtl:-scale-x-100 text-xs"></i>
                </a>
            @endif

            <div class="mx-1 hidden items-center gap-1.5 sm:flex">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="inline-flex h-9 min-w-9 items-center justify-center px-1 text-sm text-slate-400 dark:text-slate-500">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="inline-flex h-9 min-w-9 items-center justify-center rounded-full bg-brand-600 px-3 text-sm font-semibold text-white shadow-sm shadow-brand-600/30">{{ $page }}</span>
                            @else
                                <a class="inline-flex h-9 min-w-9 items-center justify-center rounded-full px-3 text-sm text-slate-600 transition hover:bg-slate-100 hover:text-brand-600 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-brand-300"
                                   href="{{ $url }}"
                                   @if (method_exists($paginator, 'getPageName'))
                                       wire:click.prevent="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                   @endif>{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            <span class="inline-flex h-9 min-w-9 items-center justify-center rounded-full bg-brand-600 px-3 text-sm font-semibold text-white shadow-sm shadow-brand-600/30 sm:hidden">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-600 transition hover:border-brand-500 hover:bg-brand-50 hover:text-brand-600 dark:border-slate-700 dark:text-slate-300 dark:hover:border-brand-500 dark:hover:bg-brand-500/10 dark:hover:text-brand-300"
                   href="{{ $paginator->nextPageUrl() }}"
                   @if (method_exists($paginator, 'getPageName'))
                       wire:click.prevent="gotoPage({{ $paginator->currentPage() + 1 }}, '{{ $paginator->getPageName() }}')"
                   @endif
                   rel="next"
                   aria-label="@lang('pagination.next')">
                    <i class="fa fa-chevron-right rtl:-scale-x-100 text-xs"></i>
                </a>
            @else
                <span aria-hidden="true" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-300 dark:border-slate-700 dark:text-slate-600">
                    <i class="fa fa-chevron-right rtl:-scale-x-100 text-xs"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
