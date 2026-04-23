@props([
    'align' => 'end',
    'trigger' => null,
    'icon' => 'fa fa-ellipsis-h',
    'label' => null,
    'triggerClass' => null,
])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    <x-tenant-tailwind-gemini.dropdown :align="$align" :icon="$icon" :label="$label" :triggerClass="$triggerClass" {{ $attributes }}>
        @isset($trigger)<x-slot:trigger>{{ $trigger }}</x-slot:trigger>@endisset
        {{ $slot }}
    </x-tenant-tailwind-gemini.dropdown>
@else
    @php
        $menuAlign = $align === 'end' ? 'dropdown-menu-end' : '';
        $defaultTrigger = 'btn btn-light btn-sm';
    @endphp
<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    @if(isset($trigger))
        <div data-bs-toggle="dropdown" aria-expanded="false" role="button">{{ $trigger }}</div>
    @else
        <button type="button" class="{{ $triggerClass ?? $defaultTrigger }}" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="{{ $icon }}"></i>
            @if($label) <span class="ms-1">{{ $label }}</span>@endif
        </button>
    @endif
    <ul class="dropdown-menu {{ $menuAlign }}">
        {{ $slot }}
    </ul>
</div>
@endif
