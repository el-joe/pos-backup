@props([
    'icon' => null,
    'href' => null,
    'type' => 'button',
    'tone' => 'default',
    'divider' => false,
])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    <x-tenant-tailwind-gemini.dropdown-item :icon="$icon" :href="$href" :type="$type" :tone="$tone" :divider="$divider" {{ $attributes }}>
        {{ $slot }}
    </x-tenant-tailwind-gemini.dropdown-item>
@elseif($divider)
    <li><hr class="dropdown-divider"></li>
@else
    @php
        $classes = 'dropdown-item d-flex align-items-center gap-2' . ($tone === 'danger' ? ' text-danger' : ($tone === 'success' ? ' text-success' : ''));
    @endphp
<li>
    @if($href)
        <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
            @if($icon)<i class="{{ $icon }}"></i>@endif
            <span>{{ $slot }}</span>
        </a>
    @else
        <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
            @if($icon)<i class="{{ $icon }}"></i>@endif
            <span>{{ $slot }}</span>
        </button>
    @endif
</li>
@endif
