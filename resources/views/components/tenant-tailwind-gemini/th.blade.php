@props(['align' => 'start', 'sortable' => false, 'field' => null, 'sortField' => null, 'sortDir' => 'asc'])

@php
    $alignClass = $align === 'end' ? 'text-right' : ($align === 'center' ? 'text-center' : '');
    $isSorted = $sortable && $field && $sortField === $field;
@endphp

<th {{ $attributes->merge(['class' => 'px-5 py-3 font-semibold ' . $alignClass]) }}>
    @if($sortable && $field)
        <button type="button" class="inline-flex items-center gap-1.5 hover:text-slate-900 dark:hover:text-white"
            wire:click="sortBy('{{ $field }}')">
            <span>{{ $slot }}</span>
            @if($isSorted)
                <i class="fa fa-sort-{{ $sortDir === 'asc' ? 'up' : 'down' }} text-[0.65rem]"></i>
            @else
                <i class="fa fa-sort text-[0.65rem] opacity-40"></i>
            @endif
        </button>
    @else
        {{ $slot }}
    @endif
</th>