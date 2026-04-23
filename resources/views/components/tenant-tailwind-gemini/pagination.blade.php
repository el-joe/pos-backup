@props([
    'paginator' => null,
    'summary' => true,
])

@if($paginator)
    <div {{ $attributes->merge(['class' => 'flex flex-wrap items-center justify-between gap-3']) }}>
        @if($summary && method_exists($paginator, 'total'))
            <p class="text-xs text-slate-500 dark:text-slate-400">
                {{ __('general.pages.pagination.showing', [
                    'from' => $paginator->firstItem() ?? 0,
                    'to'   => $paginator->lastItem() ?? 0,
                    'total'=> $paginator->total(),
                ]) }}
            </p>
        @else
            <span></span>
        @endif
        <div class="gemini-pagination">
            {{ $paginator->links() }}
        </div>
    </div>
@endif
