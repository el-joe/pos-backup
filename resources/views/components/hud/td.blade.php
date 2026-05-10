@props(['align' => 'start'])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    <x-tenant-tailwind-gemini.td :align="$align" {{ $attributes }}>{{ $slot }}</x-tenant-tailwind-gemini.td>
@else
    @php
        $alignClass = $align === 'end' ? 'text-end' : ($align === 'center' ? 'text-center' : '');
    @endphp
    <td {{ $attributes->merge(['class' => $alignClass]) }}>{{ $slot }}</td>
@endif