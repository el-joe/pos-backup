@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconEnd' => null,
    'type' => 'button',
    'href' => null,
    'loading' => false,
    'block' => false,
    'outline' => false,
])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    @php
        $map = ['primary'=>'primary','secondary'=>'secondary','success'=>'success','danger'=>'danger','warning'=>'warning','info'=>'info','light'=>'ghost','dark'=>'secondary','link'=>'link'];
        $geminiVariant = $map[$variant] ?? 'primary';
        if ($outline && $geminiVariant === 'primary') $geminiVariant = 'outline';
    @endphp
    <x-tenant-tailwind-gemini.button
        :variant="$geminiVariant" :size="$size" :icon="$icon" :iconEnd="$iconEnd"
        :type="$type" :href="$href" :loading="$loading" :block="$block" {{ $attributes }}>
        {{ $slot }}
    </x-tenant-tailwind-gemini.button>
@else
    @php
        $sizes = ['xs' => 'btn-sm', 'sm' => 'btn-sm', 'md' => '', 'lg' => 'btn-lg'];
        $variantClass = $outline ? 'btn-outline-' . $variant : 'btn-' . $variant;
        $classes = 'btn ' . $variantClass . ' ' . ($sizes[$size] ?? '') . ' ' . ($block ? 'w-100' : '') . ' d-inline-flex align-items-center gap-2';
    @endphp
    @if($href)
        <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
            @if($loading)<i class="fa fa-spinner fa-spin"></i>
            @elseif($icon)<i class="{{ $icon }}"></i>@endif
            <span>{{ $slot }}</span>
            @if($iconEnd)<i class="{{ $iconEnd }}"></i>@endif
        </a>
    @else
        <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} @if($loading) disabled @endif>
            @if($loading)<i class="fa fa-spinner fa-spin"></i>
            @elseif($icon)<i class="{{ $icon }}"></i>@endif
            <span>{{ $slot }}</span>
            @if($iconEnd)<i class="{{ $iconEnd }}"></i>@endif
        </button>
    @endif
@endif
