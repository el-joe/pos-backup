<div class="col-12">
    <x-hud.table-card :title="__('general.pages.reports.inventory.cogs.title')" icon="fa-shopping-cart" :render-table="false" class="mb-4">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped mb-0 table-dark align-middle">
                    <thead class="table-primary text-dark">
                        <tr>
                            <th>{{ __('general.pages.reports.inventory.cogs.branch') }}</th>
                            <th>{{ __('general.pages.reports.inventory.cogs.cogs_amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_cogs = 0;
                        @endphp
                        @forelse($report as $row)
                            @php
                                $total_cogs += $row->cogs_amount;
                            @endphp
                            <tr>
                                <td>{{ $row->branch_name }}</td>
                                <td>{{ currencyFormat($row->cogs_amount, true) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">{{ __('general.pages.reports.inventory.cogs.no_data') }}</td>
                            </tr>
                        @endforelse
                        @if(count($report))
                            <tr class="bg-success bg-opacity-25 fw-semibold">
                                <td>{{ __('general.pages.reports.common.total') }}</td>
                                <td>{{ currencyFormat($total_cogs, true) }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
        </div>
    </x-hud.table-card>
</div>
