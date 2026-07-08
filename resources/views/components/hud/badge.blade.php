@props([
    'tone' => 'secondary',
    'icon' => null,
    'size' => 'sm',
    'soft' => true,
])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    @php
        $map = ['primary'=>'brand','success'=>'emerald','danger'=>'rose','warning'=>'amber','info'=>'sky','secondary'=>'slate','dark'=>'slate','light'=>'slate'];
        $geminiTone = $map[$tone] ?? 'slate';
    @endphp
    <x-tenant-tailwind-gemini.badge :tone="$geminiTone" :icon="$icon" :size="$size" {{ $attributes }}>{{ $slot }}</x-tenant-tailwind-gemini.badge>
@else
    @php
        $classes = $soft
            ? 'badge rounded-pill bg-' . $tone . ' bg-opacity-10 text-' . $tone . ' border border-' . $tone . ' border-opacity-25'
            : 'badge rounded-pill bg-' . $tone;
    @endphp
<span {{ $attributes->merge(['class' => $classes . ' d-inline-flex align-items-center gap-1']) }}>
    @if($icon)<i class="{{ $icon }}"></i>@endif
    {{ $slot }}
</span>
@endif
