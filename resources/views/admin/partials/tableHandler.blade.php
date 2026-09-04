@php
    $layout = defaultLayout();
    $isHud = $layout === 'hud';
    $isGemini = $layout === 'tenant-tailwind-gemini';
    $tableClass = $isHud
        ? 'table table-striped table-hover align-middle table-bordered mb-0'
        : ($isGemini
            ? 'min-w-full text-left text-sm rtl:text-right'
            : 'table table-bordered table-hover table-striped custom-table color-table primary-table');
    $theadClass = $isHud
        ? 'table-primary'
        : ($isGemini ? 'bg-slate-50 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/70 dark:text-slate-400' : '');
    $badgeBaseClass = $isHud ? 'bg-' : 'badge-';
@endphp

<div class="{{ $isGemini ? 'table-responsive table-handler-gemini' : 'table-responsive' }}">
    <table class="{{ $tableClass }}">
        <thead @class([$theadClass])>
            <tr>
                @foreach ($headers as $header)
                    <th class="{{ $isGemini ? 'px-4 py-3 font-semibold' : '' }}">{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    @foreach ($columns as $key => $column)
                        @php $value = $row[$key] ?? null; @endphp

                        @if($column['type'] == 'text' || $column['type'] == 'number')
                            <td class="{{ $isGemini ? 'px-4 py-3 text-slate-700 dark:text-slate-200' : '' }}">{{ $value }}</td>

                        @elseif($column['type'] == 'decimal')
                            <td class="{{ $isGemini ? 'px-4 py-3 text-slate-700 dark:text-slate-200' : '' }}">{{ number_format($value, 2) }}</td>

                        @elseif($column['type'] == 'boolean')
                            <td class="{{ $isGemini ? 'px-4 py-3' : '' }}">
                                <span class="badge {{ $badgeBaseClass }}{{ $value ? 'success' : 'danger' }}{{ $isGemini ? ' inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold' : '' }}">
                                    {{ $value ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                        @elseif($column['type'] == 'date')
                            <td class="{{ $isGemini ? 'px-4 py-3 text-slate-700 dark:text-slate-200' : '' }}">{{ carbon($value)->format($isHud ? 'l, d M Y' : 'l ,d M Y') }}</td>

                        @elseif($column['type'] == 'datetime')
                            <td class="{{ $isGemini ? 'px-4 py-3 text-slate-700 dark:text-slate-200' : '' }}">{{ carbon($value)->format($isHud ? 'l, d M Y h:i A' : 'l ,d M Y H:i A') }}</td>

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
                            <td class="text-center {{ $isGemini ? 'px-4 py-3' : '' }}">{!! $icon !!}<span class="badge {{ $badgeClass }}{{ $isGemini ? ' inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold' : '' }}">{{ $value }}</span></td>

                        @elseif($column['type'] == 'actions')
                            <td class="text-nowrap {{ $isGemini ? 'px-4 py-3' : '' }}">
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
                            <td class="{{ $isGemini ? 'px-4 py-3 text-slate-500 dark:text-slate-400' : '' }}">-----</td>
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
                            <td class="{{ $isGemini ? 'px-4 py-3 font-semibold text-slate-900 dark:text-white' : '' }}">{{ is_numeric($value) ? number_format($value, 2) : $value }}</td>
                        @elseif(($loop->iteration) > (isset($totals['total']['colspan']) ? ($totals['total']['colspan'] + count($totals) - 1) : 0))
                            <td></td>
                        @endif
                    @endforeach
                </tr>
            @endif
        </tbody>
    </table>

    <div class="{{ $isHud ? 'd-flex justify-content-center mt-3' : ($isGemini ? 'mt-4 flex justify-center' : 'pagination-wrapper t-a-c') }}">
        {{ $isHud ? $rows->links('pagination::default5') : $rows->links() }}
    </div>
</div>

@push('styles')
    <style>
        .table-handler-gemini td {
            border-bottom: 1px solid rgb(226 232 240 / 1);
            vertical-align: middle !important;
        }

        .dark .table-handler-gemini td {
            border-bottom-color: rgb(51 65 85 / 1);
        }

        .table-handler-gemini tbody tr:hover td {
            background: rgb(248 250 252 / 1);
        }

        .dark .table-handler-gemini tbody tr:hover td {
            background: rgb(15 23 42 / 0.85);
        }

        @if(!$isGemini)
        td {
            font-size: {{ $isHud ? '0.9rem' : '1.4rem' }} !important;
            vertical-align: middle !important;
        }
        @endif
    </style>
@endpush
