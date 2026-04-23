@props(['paginator' => null, 'summary' => true])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    <x-tenant-tailwind-gemini.pagination :paginator="$paginator" :summary="$summary" {{ $attributes }} />
@elseif($paginator)
    <div {{ $attributes->merge(['class' => 'd-flex flex-wrap justify-content-between align-items-center gap-2']) }}>
        @if($summary && method_exists($paginator, 'total'))
            <small class="text-muted">
                {{ __('general.pages.pagination.showing', [
                    'from' => $paginator->firstItem() ?? 0,
                    'to' => $paginator->lastItem() ?? 0,
                    'total' => $paginator->total(),
                ]) }}
            </small>
        @else
            <span></span>
        @endif
        <div>{{ $paginator->links() }}</div>
    </div>
@endif