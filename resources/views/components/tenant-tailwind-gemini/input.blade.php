@props([
'type' => 'text',
'iconStart' => null,
'iconEnd' => null,
'size' => 'md',
'invalid' => false,
])

@php
$sizes = [
'sm' => 'px-2.5 py-1.5 text-xs',
'md' => 'px-3 py-2 text-sm',
'lg' => 'px-4 py-2.5 text-base',
];
$base = 'w-full rounded-xl border bg-white text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-1 dark:!bg-slate-900 dark:text-white dark:placeholder:text-slate-500 disabled:opacity-60 disabled:cursor-not-allowed';
$border = $invalid
? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500 dark:border-rose-700'
: 'border-slate-200 focus:border-brand-500 focus:ring-brand-500 dark:border-slate-700';
$padStart = $iconStart ? 'ps-9' : '';
$padEnd = $iconEnd ? 'pe-9' : '';
$classes = trim($base . ' ' . $border . ' ' . ($sizes[$size] ?? $sizes['md']) . ' ' . $padStart . ' ' . $padEnd);
@endphp

@if($iconStart || $iconEnd)
<div class="relative">
    @if($iconStart)
    <span class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3 text-slate-400">
        <i class="{{ $iconStart }}"></i>
    </span>
    @endif
    <input type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if($iconEnd)
    <span class="pointer-events-none absolute inset-y-0 end-0 flex items-center pe-3 text-slate-400">
        <i class="{{ $iconEnd }}"></i>
    </span>
    @endif
</div>
@else
<input type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
@endif