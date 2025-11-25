@switch(defaultLayout())
    @case('hud')
        <x-table5-component :rows="$rows" :columns="$columns" :headers="$headers" />
        @break
    @default
        <x-table-component :rows="$rows" :columns="$columns" :headers="$headers" />
@endswitch
