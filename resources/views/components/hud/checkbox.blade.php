@props(['label' => null, 'value' => null, 'inline' => false])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    <x-tenant-tailwind-gemini.checkbox :label="$label" :value="$value" :inline="$inline" {{ $attributes }}>{{ $slot }}</x-tenant-tailwind-gemini.checkbox>
@else
<div {{ $attributes->only(['class']) }} class="form-check {{ $inline ? 'form-check-inline' : '' }}">
    <input type="checkbox" class="form-check-input"
        @if($value !== null) value="{{ $value }}" @endif
        {{ $attributes->only(['wire:model', 'wire:model.live', 'wire:model.blur', 'wire:model.lazy', 'name', 'id', 'checked', 'disabled', 'required']) }}>
    @if($label)<label class="form-check-label">{{ $label }}</label>@endif
    {{ $slot }}
</div>
@endif
