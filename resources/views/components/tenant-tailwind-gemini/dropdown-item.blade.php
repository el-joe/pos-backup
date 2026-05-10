@props([
    'icon' => null,
    'href' => null,
    'type' => 'button',
    'tone' => 'default',
    'divider' => false,
])

@if($divider)
    <div class="my-1 h-px bg-slate-100 dark:bg-slate-800"></div>
@else
    @php
        $tones = [
            'default' => 'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800',
            'danger'  => 'text-rose-600 hover:bg-rose-50 dark:text-rose-300 dark:hover:bg-rose-500/10',
            'success' => 'text-emerald-700 hover:bg-emerald-50 dark:text-emerald-300 dark:hover:bg-emerald-500/10',
        ];
        $classes = 'flex w-full items-center gap-2 rounded-lg px-3 py-2 text-start text-sm transition-colors ' . ($tones[$tone] ?? $tones['default']);
    @endphp

    @if($href)
        <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
            @if($icon)<i class="{{ $icon }} w-4 text-center"></i>@endif
            <span>{{ $slot }}</span>
        </a>
    @else
        <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
            @if($icon)<i class="{{ $icon }} w-4 text-center"></i>@endif
            <span>{{ $slot }}</span>
        </button>
    @endif
@endif
