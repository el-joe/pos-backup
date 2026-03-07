@props([
    'title' => null,
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900']) }}>
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 dark:border-slate-800">
        <div>
            @if($title)
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $title }}</h3>
            @endif
            @if($description)
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $description }}</p>
            @endif
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
