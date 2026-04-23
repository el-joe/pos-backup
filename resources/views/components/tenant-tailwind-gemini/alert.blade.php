@props([
    'tone' => 'info',
    'icon' => null,
    'title' => null,
    'dismissible' => false,
])

@php
    $tones = [
        'info'    => ['bg' => 'bg-sky-50 border-sky-200 text-sky-800 dark:bg-sky-500/10 dark:border-sky-500/30 dark:text-sky-200', 'icon' => 'fa fa-info-circle'],
        'success' => ['bg' => 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-500/10 dark:border-emerald-500/30 dark:text-emerald-200', 'icon' => 'fa fa-check-circle'],
        'warning' => ['bg' => 'bg-amber-50 border-amber-200 text-amber-800 dark:bg-amber-500/10 dark:border-amber-500/30 dark:text-amber-200', 'icon' => 'fa fa-exclamation-triangle'],
        'danger'  => ['bg' => 'bg-rose-50 border-rose-200 text-rose-800 dark:bg-rose-500/10 dark:border-rose-500/30 dark:text-rose-200', 'icon' => 'fa fa-times-circle'],
        'neutral' => ['bg' => 'bg-slate-50 border-slate-200 text-slate-800 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200', 'icon' => 'fa fa-bell'],
    ];
    $t = $tones[$tone] ?? $tones['info'];
    $resolvedIcon = $icon ?? $t['icon'];
@endphp

<div x-data="{ show: true }" x-show="show" {{ $attributes->merge(['class' => 'flex items-start gap-3 rounded-xl border px-4 py-3 text-sm ' . $t['bg']]) }}>
    @if($resolvedIcon)
        <i class="{{ $resolvedIcon }} mt-0.5"></i>
    @endif
    <div class="flex-1">
        @if($title)
            <p class="font-semibold">{{ $title }}</p>
        @endif
        <div>{{ $slot }}</div>
    </div>
    @if($dismissible)
        <button type="button" class="text-current opacity-70 hover:opacity-100" @click="show = false">
            <i class="fa fa-times"></i>
        </button>
    @endif
</div>
