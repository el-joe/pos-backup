@props([
    'columns' => [],
    'striped' => false,
    'hover' => true,
    'responsive' => true,
])

@php
    $tableBase = 'w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400 rtl:text-right';
@endphp

@if($responsive)
    <div class="overflow-x-auto">
@endif

<table {{ $attributes->merge(['class' => $tableBase]) }}>
    @if(!empty($columns) && !isset($head))
        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-600 dark:bg-slate-800/50 dark:text-slate-300">
            <tr>
                @foreach($columns as $col)
                    @php
                        $label = is_array($col) ? ($col['label'] ?? '') : $col;
                        $align = is_array($col) ? ($col['align'] ?? 'start') : 'start';
                        $alignClass = $align === 'end' ? 'text-right' : ($align === 'center' ? 'text-center' : '');
                    @endphp
                    <th class="px-5 py-3 font-semibold {{ $alignClass }}">{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
    @elseif(isset($head))
        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-600 dark:bg-slate-800/50 dark:text-slate-300">
            {{ $head }}
        </thead>
    @endif

    <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800 {{ $hover ? '[&>tr:hover]:bg-slate-50 dark:[&>tr:hover]:bg-slate-800/50' : '' }} {{ $striped ? '[&>tr:nth-child(even)]:bg-slate-50/50 dark:[&>tr:nth-child(even)]:bg-slate-800/30' : '' }}">
        {{ $slot }}
    </tbody>

    @isset($foot)
        <tfoot class="border-t border-slate-100 bg-slate-50 text-xs text-slate-600 dark:border-slate-800 dark:bg-slate-800/30 dark:text-slate-300">
            {{ $foot }}
        </tfoot>
    @endisset
</table>

@if($responsive)
    </div>
@endif
