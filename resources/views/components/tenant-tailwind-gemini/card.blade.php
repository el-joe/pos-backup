@props([
    'title' => null,
    'description' => null,
    'icon' => null,
    'padded' => true,
])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900']) }}>
    @if($title || $description || $icon || isset($header) || isset($actions))
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 dark:border-slate-800">
            <div class="flex items-start gap-3">
                @if($icon)
                    <span class="mt-0.5 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-300">
                        <i class="{{ $icon }}"></i>
                    </span>
                @endif
                <div>
                    @if($title)
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ $title }}</h3>
                    @endif
                    @if($description)
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $description }}</p>
                    @endif
                    @isset($header)
                        {{ $header }}
                    @endisset
                </div>
            </div>
            @isset($actions)
                <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
            @endisset
        </div>
    @endif

    <div class="{{ $padded ? 'px-5 py-5' : '' }}">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="border-t border-slate-100 bg-slate-50/50 px-5 py-4 dark:border-slate-800 dark:bg-slate-900/60">
            {{ $footer }}
        </div>
    @endisset
</div>
