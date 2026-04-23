@props([
    'align' => 'start',
])

@php
    $alignClass = $align === 'end' ? 'justify-end' : ($align === 'center' ? 'justify-center' : 'justify-start');
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-2 ' . $alignClass]) }}>
    {{ $slot }}
</div>
