@props([
    'label' => null,
    'value' => null,
    'icon' => null,
    'tone' => 'brand',
    'trend' => null,
    'trendDirection' => null,
])

@php
    $tones = [
        'brand' => 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-300',
        'emerald' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300',
        'rose' => 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-300',
        'amber' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-300',
        'sky' => 'bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-300',
        'violet' => 'bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-300',
        'slate' => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300',
    ];
    $toneClass = $tones[$tone] ?? $tones['brand'];
    $trendClass = $trendDirection === 'down'
        ? 'text-rose-600 dark:text-rose-400'
        : ($trendDirection === 'up' ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-400');
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900']) }}>
    <div class="flex items-start justify-between gap-3">
        <div>
            @if($label)
                <p class="text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ $label }}</p>
            @endif
            <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-white">{{ $value ?? $slot }}</p>
            @if($trend)
                <p class="mt-1 flex items-center gap-1 text-xs font-medium {{ $trendClass }}">
                    @if($trendDirection === 'up')
                        <i class="fa fa-arrow-up"></i>
                    @elseif($trendDirection === 'down')
                        <i class="fa fa-arrow-down"></i>
                    @endif
                    <span>{{ $trend }}</span>
                </p>
            @endif
        </div>
        @if($icon)
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl {{ $toneClass }}">
                <i class="{{ $icon }} fa-lg"></i>
            </span>
        @endif
    </div>
</div>
