<div class="table-responsive">
    <table class="table table-striped table-hover align-middle table-bordered mb-0">
        <thead class="table-primary">
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
                                <span class="badge bg-{{ $value ? 'success' : 'danger' }}">
                                    {{ $value ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                        @elseif($column['type'] == 'date')
                            <td>{{ carbon($value)->format('l, d M Y') }}</td>

                        @elseif($column['type'] == 'datetime')
                            <td>{{ carbon($value)->format('l, d M Y h:i A') }}</td>

                        @elseif($column['type'] == 'badge')
                            @php
                                $badgeClass = $column['class'] ?? 'bg-secondary';
                                if (is_callable($badgeClass)) {
                                    $badgeClass = $badgeClass($row);
                                }

                                $iconClass = $column['icon'] ?? null;
                                if (is_callable($iconClass)) {
                                    $iconClass = $iconClass($row);
                                }

                                $icon = $iconClass ? '<i class="fa ' . $iconClass . ' me-1"></i>' : '';

                                if (isset($column['value'])) {
                                    $value = is_callable($column['value']) ? $column['value']($row) : $column['value'];
                                } else {
                                    $value = '-----';
                                }
                            @endphp
                            <td class="text-center">{!! $icon !!}<span class="badge {{ $badgeClass }}">{{ $value }}</span></td>

                        @elseif($column['type'] == 'actions')
                            <td class="text-nowrap">
                                @foreach ($column['actions'] ?? [] as $action)
                                    @php
                                        $params = collect($action['params'] ?? [])->map(fn($p)=> $row[$p])->implode('\', \'');
                                        $params = count($action['params'] ?? []) > 0 ? "'$params'" : '';

                                        $shouldHide = isset($action['hide']) && is_callable($action['hide']) ? $action['hide']($row) : false;
                                        $route = isset($action['route']) ? (is_callable($action['route']) ? $action['route']($row) : $action['route']) : '#';
                                    @endphp

                                    @if(!$shouldHide)
                                        <a href="{{ $route }}"
                                           @if($action['wire:click']??false)
                                               wire:click="{{ ($action['wire:click'])($row) }}"
                                           @endif
                                           class="btn btn-sm {{ $action['class'] ?? 'btn-primary' }} me-1"
                                           @if(isset($action['disabled']) && is_callable($action['disabled']) && $action['disabled']($row)) disabled @endif
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

            {{-- Totals Row --}}
            @if(isset($totals))
                <tr class="fw-semibold table-light">
                    @if(isset($totals['total']))
                        <td colspan="{{ $totals['total']['colspan'] ?? count($columns) }}" class="{{ $totals['total']['class'] ?? '' }}">
                            {{ $totals['total']['label'] ?? 'Totals' }}
                        </td>
                    @endif

                    @foreach ($columns as $key => $column)
                        @if(isset($totals[$key]))
                            @php
                                $value = is_callable($totals[$key]) ? $totals[$key]($rows) : $totals[$key];
                            @endphp
                            <td>{{ is_numeric($value) ? number_format($value, 2) : $value }}</td>
                        @elseif(($loop->iteration) > (isset($totals['total']['colspan']) ? ($totals['total']['colspan'] + count($totals) - 1) : 0))
                            <td></td>
                        @endif
                    @endforeach
                </tr>
            @endif
        </tbody>
    </table>

    {{-- Pagination Centered --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $rows->links() }}
    </div>
</div>

@push('styles')
<style>
    td {
        font-size: 0.9rem !important;
        vertical-align: middle !important;
    }
</style>
@endpush
