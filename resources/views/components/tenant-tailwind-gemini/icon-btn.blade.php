@props([
    'icon' => 'fa fa-ellipsis-h',
    'tone' => 'slate',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
    'title' => null,
])

@php
    $tones = [
        'brand'   => 'text-brand-600 hover:bg-brand-50 dark:text-brand-400 dark:hover:bg-brand-500/10',
        'emerald' => 'text-emerald-600 hover:bg-emerald-50 dark:text-emerald-400 dark:hover:bg-emerald-500/10',
        'rose'    => 'text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10',
        'amber'   => 'text-amber-600 hover:bg-amber-50 dark:text-amber-400 dark:hover:bg-amber-500/10',
        'sky'     => 'text-sky-600 hover:bg-sky-50 dark:text-sky-400 dark:hover:bg-sky-500/10',
        'violet'  => 'text-violet-600 hover:bg-violet-50 dark:text-violet-400 dark:hover:bg-violet-500/10',
        'blue'    => 'text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-500/10',
        'slate'   => 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800',
    ];
    $sizes = [
        'xs' => 'h-7 w-7 text-xs',
        'sm' => 'h-8 w-8 text-sm',
        'md' => 'h-9 w-9 text-sm',
        'lg' => 'h-10 w-10 text-base',
    ];
    $classes = 'inline-flex items-center justify-center rounded-lg transition-colors '
        . ($tones[$tone] ?? $tones['slate']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" @if($title) title="{{ $title }}" @endif {{ $attributes->merge(['class' => $classes]) }}>
        <i class="{{ $icon }}"></i>
    </a>
@else
    <button type="{{ $type }}" @if($title) title="{{ $title }}" @endif {{ $attributes->merge(['class' => $classes]) }}>
        <i class="{{ $icon }}"></i>
    </button>
@endif
