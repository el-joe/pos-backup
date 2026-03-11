<div class="col-12">
    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.reports.inventory.shortage.title')" icon="fa-warning" :render-table="false" class="mb-4">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped mb-0 align-middle">
                    <thead >
                        <tr>
                            <th>{{ __('general.pages.reports.inventory.shortage.product') }}</th>
                            <th>{{ __('general.pages.reports.inventory.shortage.branch') }}</th>
                            <th>{{ __('general.pages.reports.inventory.shortage.shortage_quantity') }}</th>
                            <th>{{ __('general.pages.reports.inventory.shortage.shortage_value') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_qty = 0;
                            $total_value = 0;
                        @endphp
                        @forelse($report as $row)
                            @php
                                $total_qty += $row->shortage_qty;
                                $total_value += $row->shortage_value;
                            @endphp
                            <tr>
                                <td>{{ $row->product_name }}</td>
                                <td>{{ $row->branch_name }}</td>
                                <td>{{ $row->shortage_qty }}</td>
                                <td>{{ currencyFormat($row->shortage_value, true) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">{{ __('general.pages.reports.inventory.shortage.no_data') }}</td>
                            </tr>
                        @endforelse
                        @if(count($report))
                            <tr class="bg-emerald-50 font-semibold text-slate-900">
                                <td>{{ __('general.pages.reports.common.total') }}</td>
                                <td></td>
                                <td>{{ $total_qty }}</td>
                                <td>{{ currencyFormat($total_value, true) }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
        </div>
    </x-tenant-tailwind-gemini.table-card>
</div>
