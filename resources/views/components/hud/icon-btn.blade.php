@props([
    'icon' => 'fa fa-ellipsis-h',
    'tone' => 'secondary',
    'size' => 'sm',
    'type' => 'button',
    'href' => null,
    'title' => null,
])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    @php
        $map = ['primary'=>'brand','success'=>'emerald','danger'=>'rose','warning'=>'amber','info'=>'sky','secondary'=>'slate','dark'=>'slate','light'=>'slate'];
        $geminiTone = $map[$tone] ?? 'slate';
    @endphp
    <x-tenant-tailwind-gemini.icon-btn :icon="$icon" :tone="$geminiTone" :size="$size" :type="$type" :href="$href" :title="$title" {{ $attributes }} />
@else
    @php
        $sizeClass = in_array($size, ['xs', 'sm']) ? 'btn-sm' : (($size === 'lg') ? 'btn-lg' : '');
        $classes = 'btn ' . $sizeClass . ' btn-outline-' . $tone;
    @endphp
    @if($href)
        <a href="{{ $href }}" @if($title) title="{{ $title }}" @endif {{ $attributes->merge(['class' => $classes]) }}>
            <i class="{{ $icon }}"></i>
        </a>
    @else
        <button type="{{ $type }}" @if($title) title="{{ $title }}" @endif {{ $attributes->merge(['class' => $classes]) }}>
            <i class="{{ $icon }}"></i>
        </button>
    @endif
@endif
