@props(['rows' => 3, 'size' => 'md', 'invalid' => false])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    <x-tenant-tailwind-gemini.textarea :rows="$rows" :size="$size" :invalid="$invalid" {{ $attributes }}>{{ $slot }}</x-tenant-tailwind-gemini.textarea>
@else
    @php
        $sizeClass = $size === 'sm' ? 'form-control-sm' : ($size === 'lg' ? 'form-control-lg' : '');
        $classes = 'form-control ' . $sizeClass . ($invalid ? ' is-invalid' : '');
    @endphp
    <textarea rows="{{ $rows }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</textarea>
@endif