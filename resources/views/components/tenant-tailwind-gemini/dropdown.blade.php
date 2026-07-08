@props([
'align' => 'end',
'trigger' => null,
'icon' => 'fa fa-ellipsis-h',
'label' => null,
'triggerClass' => null,
])

@php
$alignClass = $align === 'start' ? 'left-0 origin-top-left' : 'right-0 origin-top-right';
$defaultTrigger = 'inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-600 transition-colors hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800';
@endphp

<div x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false" {{ $attributes->merge(['class' => 'relative inline-block']) }}>
    @if(isset($trigger))
    <div @click="open = !open">{{ $trigger }}</div>
    @else
    <button type="button" @click="open = !open" class="{{ $triggerClass ?? $defaultTrigger }}">
        <i class="{{ $icon }}"></i>
        @if($label)<span class="ms-1">{{ $label }}</span>@endif
    </button>
    @endif

    <div x-show="open" x-cloak x-transition.opacity
        class="absolute {{ $alignClass }} z-40 mt-2 min-w-[12rem] rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg dark:border-slate-700 dark:!bg-slate-900">
        {{ $slot }}
    </div>
</div>