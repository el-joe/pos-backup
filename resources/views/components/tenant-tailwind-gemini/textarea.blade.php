@props([
    'rows' => 3,
    'size' => 'md',
    'invalid' => false,
])

@php
    $sizes = [
        'sm' => 'px-2.5 py-1.5 text-xs',
        'md' => 'px-3 py-2 text-sm',
        'lg' => 'px-4 py-2.5 text-base',
    ];
    $base = 'w-full rounded-xl border bg-white text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-1 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 disabled:opacity-60';
    $border = $invalid
        ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500 dark:border-rose-700'
        : 'border-slate-200 focus:border-brand-500 focus:ring-brand-500 dark:border-slate-700';
    $classes = trim($base . ' ' . $border . ' ' . ($sizes[$size] ?? $sizes['md']));
@endphp

<textarea rows="{{ $rows }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</textarea>
