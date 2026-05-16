{{-- Search-input: text input with leading search icon and optional clear --}}
@props([
'placeholder' => null,
'clearAction' => null,
])

<div {{ $attributes->whereStartsWith('class') }}>
    <div class="relative">
        <span class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3 text-slate-400">
            <i class="fa fa-search"></i>
        </span>
        <input type="search"
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            {{ $attributes->except(['class', 'clearAction', 'placeholder']) }}
            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 ps-9 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:!bg-slate-900 dark:text-white dark:placeholder:text-slate-500">
        @if($clearAction)
        <button type="button" wire:click="{{ $clearAction }}" class="absolute inset-y-0 end-0 flex items-center pe-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
            <i class="fa fa-times"></i>
        </button>
        @endif
    </div>
</div>