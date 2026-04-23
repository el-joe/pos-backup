@props(['align' => 'start'])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    <x-tenant-tailwind-gemini.btn-group :align="$align" {{ $attributes }}>{{ $slot }}</x-tenant-tailwind-gemini.btn-group>
@else
    @php
        $alignClass = $align === 'end' ? 'justify-content-end' : ($align === 'center' ? 'justify-content-center' : '');
    @endphp
<div {{ $attributes->merge(['class' => 'd-flex flex-wrap align-items-center gap-2 ' . $alignClass]) }}>
    {{ $slot }}
</div>
@endif
