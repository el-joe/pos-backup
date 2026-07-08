@props([
    'title' => null,
    'description' => null,
    'icon' => null,
    'breadcrumbs' => [],
])

<div {{ $attributes->merge(['class' => 'mb-6 flex flex-wrap items-start justify-between gap-4']) }}>
    <div class="flex items-start gap-3">
        @if($icon)
            <span class="mt-1 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-300">
                <i class="{{ $icon }} fa-lg"></i>
            </span>
        @endif
        <div>
            @if(!empty($breadcrumbs))
                <nav class="mb-1 flex flex-wrap items-center gap-1 text-xs text-slate-500 dark:text-slate-400">
                    @foreach($breadcrumbs as $i => $crumb)
                        @if($i > 0)
                            <i class="fa fa-chevron-right text-[0.6rem] opacity-60"></i>
                        @endif
                        @if(is_array($crumb) && !empty($crumb['href']))
                            <a href="{{ $crumb['href'] }}" class="hover:text-brand-600 dark:hover:text-brand-300">{{ $crumb['label'] ?? '' }}</a>
                        @else
                            <span>{{ is_array($crumb) ? ($crumb['label'] ?? '') : $crumb }}</span>
                        @endif
                    @endforeach
                </nav>
            @endif
            @if($title)
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $title }}</h1>
            @endif
            @if($description)
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $description }}</p>
            @endif
        </div>
    </div>
    @isset($actions)
        <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
    @endisset
</div>
