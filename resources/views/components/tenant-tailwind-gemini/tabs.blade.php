@props([
    'tabs' => [],
    'active' => null,
    'align' => 'start',
])

@php
    $alignClass = $align === 'center' ? 'justify-center' : ($align === 'end' ? 'justify-end' : 'justify-start');
@endphp

<div {{ $attributes->merge(['class' => 'border-b border-slate-200 dark:border-slate-800']) }}>
    <nav class="flex flex-wrap items-center gap-1 {{ $alignClass }}">
        @foreach($tabs as $key => $tab)
            @php
                $label = is_array($tab) ? ($tab['label'] ?? $key) : $tab;
                $icon = is_array($tab) ? ($tab['icon'] ?? null) : null;
                $href = is_array($tab) ? ($tab['href'] ?? null) : null;
                $isActive = (string) $active === (string) $key;
                $base = 'inline-flex items-center gap-2 border-b-2 px-4 py-2.5 text-sm font-medium transition-colors';
                $classes = $isActive
                    ? 'border-brand-500 text-brand-600 dark:text-brand-300'
                    : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200';
            @endphp
            @if($href)
                <a href="{{ $href }}" class="{{ $base }} {{ $classes }}">
                    @if($icon)<i class="{{ $icon }}"></i>@endif
                    <span>{{ $label }}</span>
                </a>
            @else
                <button type="button" class="{{ $base }} {{ $classes }}" wire:click="$set('{{ $tabBinding ?? 'activeTab' }}', '{{ $key }}')">
                    @if($icon)<i class="{{ $icon }}"></i>@endif
                    <span>{{ $label }}</span>
                </button>
            @endif
        @endforeach
    </nav>
</div>
