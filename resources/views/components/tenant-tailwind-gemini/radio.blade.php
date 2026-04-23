@props([
    'label' => null,
    'value' => null,
    'inline' => false,
])

<label {{ $attributes->merge(['class' => 'inline-flex ' . ($inline ? '' : 'mt-1 ') . 'cursor-pointer items-center gap-2']) }}>
    <input type="radio"
        @if($value !== null) value="{{ $value }}" @endif
        {{ $attributes->only(['wire:model', 'wire:model.live', 'wire:model.blur', 'wire:model.lazy', 'name', 'id', 'checked', 'disabled', 'required']) }}
        class="h-5 w-5 border-slate-300 text-brand-500 focus:ring-brand-500 dark:border-slate-600 dark:bg-slate-800">
    @if($label)
        <span class="text-sm text-slate-700 dark:text-slate-300">{{ $label }}</span>
    @endif
    {{ $slot }}
</label>
