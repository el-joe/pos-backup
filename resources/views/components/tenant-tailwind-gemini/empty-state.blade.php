@props([
    'icon' => 'fa fa-inbox',
    'title' => null,
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center gap-3 px-6 py-12 text-center']) }}>
    <span class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
        <i class="{{ $icon }} fa-lg"></i>
    </span>
    @if($title)
        <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ $title }}</h3>
    @endif
    @if($description)
        <p class="max-w-md text-sm text-slate-500 dark:text-slate-400">{{ $description }}</p>
    @endif
    @if(trim($slot))
        <div class="mt-2">{{ $slot }}</div>
    @endif
</div>
