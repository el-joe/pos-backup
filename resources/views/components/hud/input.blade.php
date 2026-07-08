@props([
    'type' => 'text',
    'iconStart' => null,
    'iconEnd' => null,
    'size' => 'md',
    'invalid' => false,
])
@if(defaultLayout() === 'tenant-tailwind-gemini')
    <x-tenant-tailwind-gemini.input :type="$type" :iconStart="$iconStart" :iconEnd="$iconEnd" :size="$size" :invalid="$invalid" {{ $attributes }} />
@else
        @php
            $sizeClass = $size === 'sm' ? 'form-control-sm' : ($size === 'lg' ? 'form-control-lg' : '');
            $classes = 'form-control ' . $sizeClass . ($invalid ? ' is-invalid' : '');
        @endphp
    @if($iconStart || $iconEnd)
        <div class="input-group {{ $sizeClass ? 'input-group-' . str_replace('form-control-', '', $sizeClass) : '' }}">
        @if($iconStart)<span class="input-group-text"><i class="{{ $iconStart }}"></i></span>@endif
            <input type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
            @if($iconEnd)<span class="input-group-text"><i class="{{ $iconEnd }}"></i></span>@endif
        </div>
    @else
            <input type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @endif
@endif
