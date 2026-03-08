@props([
    'title' => null,
    'icon' => 'fa fa-filter',
    'expanded' => false,
])

<div x-data="{ expanded: {{ $expanded ? 'true' : 'false' }} }" {{ $attributes->merge(['class' => 'overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900']) }}>
    <button type="button" @click="expanded = !expanded" class="flex w-full items-center justify-between gap-3 px-5 py-4 text-start">
        <div class="flex items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-300">
                <i class="{{ $icon }}"></i>
            </span>
            <div>
                @if($title)
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ $title }}</h3>
                @endif
                @if(isset($subtitle))
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $subtitle }}</p>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-3">
            @if(isset($actions))
                {{ $actions }}
            @endif
            <i class="fa fa-chevron-down text-xs text-slate-400 transition-transform" :class="expanded ? 'rotate-180' : ''"></i>
        </div>
    </button>

    <div x-show="expanded" x-collapse x-cloak class="border-t border-slate-100 px-5 py-5 dark:border-slate-800">
        {{ $slot }}
    </div>
</div>
