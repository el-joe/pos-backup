@props(['align' => 'start', 'sortable' => false, 'field' => null, 'sortField' => null, 'sortDir' => 'asc'])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    <x-tenant-tailwind-gemini.th :align="$align" :sortable="$sortable" :field="$field" :sortField="$sortField" :sortDir="$sortDir" {{ $attributes }}>{{ $slot }}</x-tenant-tailwind-gemini.th>
@else
    @php
        $alignClass = $align === 'end' ? 'text-end' : ($align === 'center' ? 'text-center' : '');
        $isSorted = $sortable && $field && $sortField === $field;
    @endphp
<th {{ $attributes->merge(['class' => $alignClass]) }}>
    @if($sortable && $field)
        <button type="button" class="btn btn-link p-0 text-reset text-decoration-none d-inline-flex align-items-center gap-1" wire:click="sortBy('{{ $field }}')">
            <span>{{ $slot }}</span>
            @if($isSorted)
                <i class="fa fa-sort-{{ $sortDir === 'asc' ? 'up' : 'down' }} small"></i>
            @else
                <i class="fa fa-sort small opacity-50"></i>
            @endif
        </button>
    @else
        {{ $slot }}
    @endif
</th>
@endif
