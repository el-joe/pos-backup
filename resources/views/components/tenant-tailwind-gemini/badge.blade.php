@props([
    'tone' => 'slate',
    'icon' => null,
    'size' => 'sm',
])
@php
    $tones = [
        'brand' => 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-300',
        'emerald' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300',
        'rose' => 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300',
        'amber' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300',
        'sky' => 'bg-sky-50 text-sky-700 dark:bg-sky-500/10 dark:text-sky-300',
        'violet' => 'bg-violet-50 text-violet-700 dark:bg-violet-500/10 dark:text-violet-300',
        'blue' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300',
        'slate' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
    ];
    $sizes = [
        'xs' => 'px-1.5 py-0.5 text-[0.65rem]',
        'sm' => 'px-2.5 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-sm',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full font-medium ' . ($tones[$tone] ?? $tones['slate']) . ' ' . ($sizes[$size] ?? $sizes['sm'])]) }}>
    @if($icon)<i class="{{ $icon }}"></i>@endif
    {{ $slot }}
</span>
