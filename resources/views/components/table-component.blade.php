<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped custom-table color-table primary-table">
        <thead>
            <tr>
                @foreach ($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    @foreach ($columns as $key => $column)
                        @php $value = $row[$key] ?? null; @endphp
                        @if($column['type'] == 'text' || $column['type'] == 'number')
                            <td>{{ $value }}</td>
                        @elseif($column['type'] == 'decimal')
                            <td>{{ number_format($value, 2) }}</td>
                        @elseif($column['type'] == 'boolean')
                            <td>
                                <span class="badge badge-{{ $value ? 'success' : 'danger' }}">
                                    {{ $value ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        @elseif($column['type'] == 'date')
                            <td>{{ carbon($value)->format('l ,d M Y') }}</td>
                        @elseif($column['type'] == 'datetime')
                            <td>{{ carbon($value)->format('l ,d M Y H:i A') }}</td>
                        @elseif($column['type'] == 'badge')
                            @php
                                // if($key != 'debit') {
                                //     dd($key,$column,$value,$row);
                                // }
                                $badgeClass = $column['class'] ?? 'badge-secondary';
                                if (is_callable($badgeClass)) {
                                    $badgeClass = $badgeClass($row);
                                }

                                $iconClass = $column['icon'] ?? null;
                                if (is_callable($iconClass)) {
                                    $iconClass = $iconClass($row);
                                }
                                $icon = '';
                                if ($iconClass) {
                                    $icon = '<i class="fa ' . $iconClass . '"></i> ';
                                }

                                if(isset($column['value'])) {
                                    if(is_callable($column['value'])) {
                                        $value = $column['value']($row);
                                    } else {
                                        $value = $column['value'];
                                    }
                                }else{
                                    $value = '-----';
                                }
                            @endphp
                            <td style="text-align: center;">
                                {!! $icon !!}
                                <span class="badge {{ $badgeClass }}">{{ $value }}</span>
                            </td>
                        @elseif($column['type'] == 'actions')
                            <td>
                                @foreach ($column['actions'] ?? [] as $action)
                                    @php
                                        $params = collect($action['params'] ?? [])->map(fn($p)=> $row[$p])->implode('\', \'');
                                        $params = count($action['params'] ?? []) > 0 ? "'$params'" : '';

                                        // Check if action should be hidden
                                        $shouldHide = false;
                                        if (isset($action['hide']) && is_callable($action['hide'])) {
                                            $shouldHide = $action['hide']($row);
                                        }
                                    @endphp

                                    @if(!$shouldHide)
                                        @php
                                            $route = $action['route'] ?? '#';
                                            if (is_callable($route)) {
                                                $route = $route($row);
                                            }
                                        @endphp
                                        <a
                                            href="{{ $route }}"
                                            @if($action['wire:click']??false)
                                            @if((isset($action['disabled']) && is_callable($action['disabled']) && !$action['disabled']($row) || !isset($action['disabled'])))
                                            wire:click="{{ ($action['wire:click'])($row) }}"
                                            @endif
                                            @endif
                                            class="{{ $action['class'] ?? '' }} text-nowrap"
                                            @if(isset($action['disabled']) && is_callable($action['disabled']) && $action['disabled']($row)) disabled  @endif
                                            @foreach($action['attributes'] ?? [] as $attr => $val)
                                                {{ $attr }}="{{ is_callable($val) ? ($val)($row) : $val }}"
                                            @endforeach
                                        >
                                            <i class="{{ $action['icon'] ?? '' }}"></i>
                                        </a>
                                    @endif
                                @endforeach
                            </td>
                        @else
                            <td>-----</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
            @if(isset($totals))
                <tr>
                    @if(isset($totals['total']))
                        <td colspan="{{ $totals['total']['colspan'] ?? count($columns) }}" class="{{ $totals['total']['class'] ?? '' }}">
                            {{ $totals['total']['label'] ?? 'Totals' }}
                        </td>
                    @endif
                    @foreach ($columns as $key => $column)
                        @if(isset($totals[$key]))
                            @if(is_callable($totals[$key]))
                                @php $value = $totals[$key]($rows); @endphp
                            @else
                                @php $value = $totals[$key]; @endphp
                            @endif
                            <td>{{ is_numeric($value) ? number_format($value, 2) : $value }}</td>
                        @elseif(($loop->iteration) > (isset($totals['total']['colspan']) ? ($totals['total']['colspan'] + count($totals) -1) : 0))
                            <td></td>
                        @endif
                    @endforeach
                </tr>
            @endif
        </tbody>
    </table>
    {{-- center pagination --}}
    <div class="pagination-wrapper t-a-c">
        {{ $rows->links() }}
    </div>
</div>

@push('styles')
    <style>
        /* make font size smaller for every td */
        td {
            font-size: 1.4rem!important;
        }
    </style>
@endpush
