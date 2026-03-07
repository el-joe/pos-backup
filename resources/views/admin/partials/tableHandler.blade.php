@php
    $isHud = defaultLayout() === 'hud';
    $tableClass = $isHud
        ? 'table table-striped table-hover align-middle table-bordered mb-0'
        : 'table table-bordered table-hover table-striped custom-table color-table primary-table';
    $theadClass = $isHud ? 'table-primary' : '';
    $badgeBaseClass = $isHud ? 'bg-' : 'badge-';
@endphp

<div class="table-responsive">
    <table class="{{ $tableClass }}">
        <thead @class([$theadClass])>
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
                                <span class="badge {{ $badgeBaseClass }}{{ $value ? 'success' : 'danger' }}">
                                    {{ $value ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                        @elseif($column['type'] == 'date')
                            <td>{{ carbon($value)->format($isHud ? 'l, d M Y' : 'l ,d M Y') }}</td>

                        @elseif($column['type'] == 'datetime')
                            <td>{{ carbon($value)->format($isHud ? 'l, d M Y h:i A' : 'l ,d M Y H:i A') }}</td>

                        @elseif($column['type'] == 'badge')
                            @php
                                $badgeClass = $column['class'] ?? ($isHud ? 'bg-secondary' : 'badge-secondary');
                                if (is_callable($badgeClass)) {
                                    $badgeClass = $badgeClass($row);
                                }

                                $iconClass = $column['icon'] ?? null;
                                if (is_callable($iconClass)) {
                                    $iconClass = $iconClass($row);
                                }

                                $icon = '';
                                if ($iconClass) {
                                    $icon = '<i class="fa ' . $iconClass . ($isHud ? ' me-1' : '') . '"></i> ';
                                }

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
                                        $shouldHide = isset($action['hide']) && is_callable($action['hide']) ? $action['hide']($row) : false;
                                        $route = isset($action['route']) ? (is_callable($action['route']) ? $action['route']($row) : $action['route']) : '#';
                                        $isDisabled = isset($action['disabled']) && is_callable($action['disabled']) && $action['disabled']($row);
                                    @endphp

                                    @if(!$shouldHide)
                                        <a href="{{ $route }}"
                                           @if(($action['wire:click'] ?? false) && !$isDisabled)
                                               wire:click="{{ ($action['wire:click'])($row) }}"
                                           @endif
                                           class="{{ $action['class'] ?? 'btn btn-primary btn-sm' }}{{ $isHud ? ' me-1' : '' }}"
                                           @if($isDisabled) disabled @endif
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
                <tr @class(['fw-semibold table-light' => $isHud])>
                    @if(isset($totals['total']))
                        <td colspan="{{ $totals['total']['colspan'] ?? count($columns) }}" class="{{ $totals['total']['class'] ?? '' }}">
                            {{ $totals['total']['label'] ?? ($isHud ? 'Total' : 'Totals') }}
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

    <div class="{{ $isHud ? 'd-flex justify-content-center mt-3' : 'pagination-wrapper t-a-c' }}">
        {{ $isHud ? $rows->links('pagination::default5') : $rows->links() }}
    </div>
</div>

@push('styles')
    <style>
        td {
            font-size: {{ $isHud ? '0.9rem' : '1.4rem' }} !important;
            vertical-align: middle !important;
        }
    </style>
@endpush
