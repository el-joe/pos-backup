@props([
    'label' => null,
    'description' => null,
])

<label {{ $attributes->merge(['class' => 'inline-flex cursor-pointer items-center gap-3']) }}>
    <span class="relative inline-flex">
        <input type="checkbox" class="peer sr-only"
            {{ $attributes->only(['wire:model', 'wire:model.live', 'wire:model.blur', 'wire:model.lazy', 'name', 'id', 'checked', 'disabled', 'required']) }}>
        <span class="h-6 w-11 rounded-full bg-slate-200 transition-colors peer-checked:bg-brand-500 dark:bg-slate-700 dark:peer-checked:bg-brand-500"></span>
        <span class="absolute start-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5 rtl:peer-checked:-translate-x-5"></span>
    </span>
    @if($label || $description)
        <span>
            @if($label)<span class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ $label }}</span>@endif
            @if($description)<span class="block text-xs text-slate-500 dark:text-slate-400">{{ $description }}</span>@endif
        </span>
    @endif
    {{ $slot }}
</label>
