@props([
    'size' => 'md',
    'label' => null,
])

@php
    $sizes = [
        'xs' => 'h-3 w-3 border',
        'sm' => 'h-4 w-4 border',
        'md' => 'h-6 w-6 border-2',
        'lg' => 'h-10 w-10 border-2',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 text-slate-500 dark:text-slate-400']) }}>
    <span class="{{ $sizes[$size] ?? $sizes['md'] }} inline-block animate-spin rounded-full border-slate-300 border-t-brand-500 dark:border-slate-700 dark:border-t-brand-300"></span>
    @if($label)<span class="text-sm">{{ $label }}</span>@endif
</span>
