@props(['align' => 'start'])

@php
    $alignClass = $align === 'end' ? 'text-right' : ($align === 'center' ? 'text-center' : '');
@endphp

<td {{ $attributes->merge(['class' => 'px-5 py-4 ' . $alignClass]) }}>
    {{ $slot }}
</td>
