@props([
    'columns' => [],
    'striped' => true,
    'hover' => true,
    'responsive' => true,
])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    <x-tenant-tailwind-gemini.table :columns="$columns" :striped="$striped" :hover="$hover" :responsive="$responsive" {{ $attributes }}>
        @isset($head)
            <x-slot:head>{{ $head }}</x-slot:head>
        @endisset
        {{ $slot }}
        @isset($foot)
            <x-slot:foot>{{ $foot }}</x-slot:foot>
        @endisset
    </x-tenant-tailwind-gemini.table>
@else
    @php
        $classes = 'table align-middle mb-0 ' . ($striped ? 'table-striped' : '') . ' ' . ($hover ? 'table-hover' : '');
    @endphp
    @if($responsive)<div class="table-responsive">@endif
    <table {{ $attributes->merge(['class' => $classes]) }}>
        @if(!empty($columns) && !isset($head))
            <thead class="table-light">
                <tr>
                    @foreach($columns as $col)
                        @php
                            $label = is_array($col) ? ($col['label'] ?? '') : $col;
                            $align = is_array($col) ? ($col['align'] ?? 'start') : 'start';
                            $alignClass = $align === 'end' ? 'text-end' : ($align === 'center' ? 'text-center' : '');
                        @endphp
                        <th class="{{ $alignClass }}">{{ $label }}</th>
                    @endforeach
                </tr>
            </thead>
        @elseif(isset($head))
            <thead class="table-light">{{ $head }}</thead>
        @endif
        <tbody>{{ $slot }}</tbody>
        @isset($foot)<tfoot class="table-light">{{ $foot }}</tfoot>@endisset
    </table>
    @if($responsive)</div>@endif
@endif
