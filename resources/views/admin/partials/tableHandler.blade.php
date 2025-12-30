@switch(defaultLayout())
    @case('hud')
        @if(isset($totals))
            <x-table5-component :rows="$rows" :columns="$columns" :headers="$headers" :totals="$totals" />
        @else
            <x-table5-component :rows="$rows" :columns="$columns" :headers="$headers" />
        @endif
        @break
    @default
        @if(isset($totals))
            <x-table-component :rows="$rows" :columns="$columns" :headers="$headers" :totals="$totals" />
        @else
            <x-table-component :rows="$rows" :columns="$columns" :headers="$headers" />
        @endif
@endswitch
