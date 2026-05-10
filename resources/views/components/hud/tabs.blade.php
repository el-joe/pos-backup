@props([
    'tabs' => [],
    'active' => null,
    'align' => 'start',
    'tabBinding' => 'activeTab',
])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    <x-tenant-tailwind-gemini.tabs :tabs="$tabs" :active="$active" :align="$align" :tabBinding="$tabBinding" {{ $attributes }} />
@else
    @php
        $alignClass = $align === 'center' ? 'justify-content-center' : ($align === 'end' ? 'justify-content-end' : '');
    @endphp
<ul {{ $attributes->merge(['class' => 'nav nav-tabs ' . $alignClass]) }}>
    @foreach($tabs as $key => $tab)
        @php
            $label = is_array($tab) ? ($tab['label'] ?? $key) : $tab;
            $icon = is_array($tab) ? ($tab['icon'] ?? null) : null;
            $href = is_array($tab) ? ($tab['href'] ?? null) : null;
            $isActive = (string) $active === (string) $key;
        @endphp
        <li class="nav-item">
            @if($href)
                <a href="{{ $href }}" class="nav-link {{ $isActive ? 'active' : '' }}">
                    @if($icon)<i class="{{ $icon }} me-1"></i>@endif {{ $label }}
                </a>
            @else
                <button type="button" class="nav-link {{ $isActive ? 'active' : '' }}" wire:click="$set('{{ $tabBinding }}', '{{ $key }}')">
                    @if($icon)<i class="{{ $icon }} me-1"></i>@endif {{ $label }}
                </button>
            @endif
        </li>
    @endforeach
</ul>
@endif
