<div class="container-fluid">
    <div class="col-12">
        <x-tenant-tailwind-gemini.table-card :title="__('general.pages.reports.inventory.stock_movement.title')" icon="fa-exchange" :render-table="false">
            <div class="table-responsive">
                <table class="table table-dark table-hover table-striped align-middle mb-0">
                        <thead class="table-light text-dark">
                            <tr>
                                <th>{{ __('general.pages.reports.inventory.stock_movement.product') }}</th>
                                <th>{{ __('general.pages.reports.inventory.stock_movement.inflow_purchases') }}</th>
                                <th>{{ __('general.pages.reports.inventory.stock_movement.outflow_sales') }}</th>
                                <th>{{ __('general.pages.reports.inventory.stock_movement.adjustments') }}</th>
                                <th>{{ __('general.pages.reports.inventory.stock_movement.current_stock') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_inflow = 0;
                                $total_outflow = 0;
                                $total_adjustment = 0;
                                $total_current_stock = 0;
                            @endphp
                            @forelse($report as $row)
                                @php
                                    $total_inflow += $row['inflow'];
                                    $total_outflow += $row['outflow'];
                                    $total_adjustment += $row['adjustment'];
                                    $total_current_stock += $row['current_stock'];
                                @endphp
                                <tr>
                                    <td>{{ $row['product_name'] }}</td>
                                    <td>{{ $row['inflow'] }}</td>
                                    <td>{{ $row['outflow'] }}</td>
                                    <td>{{ $row['adjustment'] }}</td>
                                    <td>{{ $row['current_stock'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">{{ __('general.pages.reports.inventory.stock_movement.no_data') }}</td>
                                </tr>
                            @endforelse
                            @if(count($report))
                                <tr class="fw-semibold bg-success bg-opacity-25">
                                    <td>{{ __('general.pages.reports.common.total') }}</td>
                                    <td>{{ $total_inflow }}</td>
                                    <td>{{ $total_outflow }}</td>
                                    <td>{{ $total_adjustment }}</td>
                                    <td>{{ $total_current_stock }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
            </div>
        </x-tenant-tailwind-gemini.table-card>
    </div>
</div>
