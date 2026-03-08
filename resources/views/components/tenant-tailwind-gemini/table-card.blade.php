@props([
    'title' => null,
    'description' => null,
    'icon' => 'fa fa-table',
])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900']) }}>
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 dark:border-slate-800">
        <div class="flex items-start gap-3">
            @if($icon)
                <span class="mt-0.5 inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-300">
                    <i class="{{ $icon }}"></i>
                </span>
            @endif

            <div>
                @if($title)
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $title }}</h3>
                @endif
                @if($description)
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $description }}</p>
                @endif
            </div>
        </div>

        @if(isset($actions))
            <div class="flex flex-wrap items-center gap-2">
                {{ $actions }}
            </div>
        @endif
    </div>

    @if(isset($head))
        <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
            {{ $head }}
        </div>
    @endif

    <div class="overflow-x-auto">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="border-t border-slate-100 px-5 py-4 dark:border-slate-800">
            {{ $footer }}
        </div>
    @endif
</div>
