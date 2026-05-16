@props([
'variant' => 'primary',
'size' => 'md',
'icon' => null,
'iconEnd' => null,
'type' => 'button',
'href' => null,
'loading' => false,
'block' => false,
])
@php
$variants = [
'primary' => 'bg-brand-500 text-white hover:bg-brand-600 focus:ring-brand-500',
'secondary' => 'bg-slate-100 text-slate-700 hover:bg-slate-200 focus:ring-slate-400 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700',
'success' => 'bg-emerald-500 text-white hover:bg-emerald-600 focus:ring-emerald-500',
'danger' => 'bg-rose-500 text-white hover:bg-rose-600 focus:ring-rose-500',
'warning' => 'bg-amber-500 text-white hover:bg-amber-600 focus:ring-amber-500',
'info' => 'bg-sky-500 text-white hover:bg-sky-600 focus:ring-sky-500',
'ghost' => 'bg-transparent text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800',
'outline' => 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:!bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800',
'link' => 'bg-transparent text-brand-600 hover:underline dark:text-brand-300 px-0 py-0',
];
$sizes = [
'xs' => 'px-2 py-1 text-xs gap-1',
'sm' => 'px-3 py-1.5 text-sm gap-1.5',
'md' => 'px-4 py-2 text-sm gap-2',
'lg' => 'px-5 py-2.5 text-base gap-2',
];
$base = 'inline-flex items-center justify-center rounded-xl font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-1 dark:focus:ring-offset-slate-900 disabled:opacity-60 disabled:cursor-not-allowed';
$classes = trim(($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']) . ' ' . $base . ($block ? ' w-full' : ''));
@endphp
@if($href)
<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if($loading)
    <i class="fa fa-spinner fa-spin"></i>
    @elseif($icon)
    <i class="{{ $icon }}"></i>
    @endif
    <span>{{ $slot }}</span>
    @if($iconEnd)<i class="{{ $iconEnd }}"></i>@endif
</a>
@else
<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} @if($loading) disabled @endif>
    @if($loading)
    <i class="fa fa-spinner fa-spin"></i>
    @elseif($icon)
    <i class="{{ $icon }}"></i>
    @endif
    <span>{{ $slot }}</span>
    @if($iconEnd)<i class="{{ $iconEnd }}"></i>@endif
</button>
@endif