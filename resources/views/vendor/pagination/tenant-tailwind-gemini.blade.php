@if ($paginator->hasPages())
    <nav aria-label="Pagination Navigation" class="flex flex-col gap-3 px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="text-sm text-slate-500 dark:text-slate-400">
            {{ __('Showing') }}
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $paginator->firstItem() }}</span>
            {{ __('to') }}
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $paginator->lastItem() }}</span>
            {{ __('of') }}
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $paginator->total() }}</span>
            {{ __('results') }}
        </div>

        <div class="inline-flex flex-wrap items-center gap-2">
            @if ($paginator->onFirstPage())
                <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl border border-slate-200 px-3 text-sm text-slate-300 dark:border-slate-700 dark:text-slate-600">&lsaquo;</span>
            @else
                <a class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl border border-slate-200 px-3 text-sm text-slate-600 transition hover:border-brand-500 hover:text-brand-600 dark:border-slate-700 dark:text-slate-300"
                   href="{{ $paginator->previousPageUrl() }}"
                   @if (method_exists($paginator, 'getPageName'))
                       wire:click.prevent="gotoPage({{ $paginator->currentPage() - 1 }}, '{{ $paginator->getPageName() }}')"
                   @endif
                   rel="prev"
                   aria-label="@lang('pagination.previous')">&lsaquo;</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl border border-slate-200 px-3 text-sm text-slate-400 dark:border-slate-700 dark:text-slate-500">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl bg-brand-600 px-3 text-sm font-semibold text-white">{{ $page }}</span>
                        @else
                            <a class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl border border-slate-200 px-3 text-sm text-slate-600 transition hover:border-brand-500 hover:text-brand-600 dark:border-slate-700 dark:text-slate-300"
                               href="{{ $url }}"
                               @if (method_exists($paginator, 'getPageName'))
                                   wire:click.prevent="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                               @endif>{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl border border-slate-200 px-3 text-sm text-slate-600 transition hover:border-brand-500 hover:text-brand-600 dark:border-slate-700 dark:text-slate-300"
                   href="{{ $paginator->nextPageUrl() }}"
                   @if (method_exists($paginator, 'getPageName'))
                       wire:click.prevent="gotoPage({{ $paginator->currentPage() + 1 }}, '{{ $paginator->getPageName() }}')"
                   @endif
                   rel="next"
                   aria-label="@lang('pagination.next')">&rsaquo;</a>
            @else
                <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl border border-slate-200 px-3 text-sm text-slate-300 dark:border-slate-700 dark:text-slate-600">&rsaquo;</span>
            @endif
        </div>
    </nav>
@endif
