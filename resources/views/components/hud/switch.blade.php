@props(['label' => null, 'description' => null])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    <x-tenant-tailwind-gemini.switch :label="$label" :description="$description" {{ $attributes }}>{{ $slot }}</x-tenant-tailwind-gemini.switch>
@else
    <div {{ $attributes->only(['class']) }} class="form-check form-switch">
        <input type="checkbox" class="form-check-input" role="switch" {{ $attributes->only(['wire:model', 'wire:model.live', 'wire:model.blur', 'wire:model.lazy', 'name', 'id', 'checked', 'disabled', 'required']) }}>
        @if($label || $description)
            <label class="form-check-label">
                @if($label)<span class="d-block fw-semibold">{{ $label }}</span>@endif
                @if($description)<small class="text-muted">{{ $description }}</small>@endif
            </label>
        @endif
        {{ $slot }}
    </div>
@endif