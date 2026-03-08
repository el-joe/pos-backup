<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-4">
            <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.reports.common.filter_options')" icon="fa-filter">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                        <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
                    </div>
                </div>
            </x-tenant-tailwind-gemini.filter-card>
        </div>

        <div class="col-12">
            <x-tenant-tailwind-gemini.table-card :title="__('general.pages.reports.sales.customer.title')" icon="fa-user" :render-table="false">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover mb-0 align-middle">
                            <thead class="table-light text-dark">
                                <tr>
                                    <th>{{ __('general.pages.reports.sales.customer.customer') }}</th>
                                    <th>{{ __('general.pages.reports.sales.customer.sale_count') }}</th>
                                    <th>{{ __('general.pages.reports.sales.customer.total_spent') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_spent = 0;
                                    $total_count = 0;
                                @endphp
                                @forelse($report as $row)
                                    @php
                                        $total_spent += $row->total_spent;
                                        $total_count += $row->sale_count;
                                    @endphp
                                    <tr>
                                        <td>{{ $row->customer_name }}</td>
                                        <td>{{ $row->sale_count }}</td>
                                        <td>{{ currencyFormat($row->total_spent, true) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">
                                            {{ __('general.pages.reports.sales.customer.no_data') }}
                                        </td>
                                    </tr>
                                @endforelse
                                @if(count($report))
                                    <tr class="bg-success text-dark fw-bold">
                                        <td>{{ __('general.pages.reports.common.total') }}</td>
                                        <td>{{ $total_count }}</td>
                                        <td>{{ currencyFormat($total_spent, true) }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                </div>
            </x-tenant-tailwind-gemini.table-card>
        </div>
    </div>
</div>
@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.daterange-picker-script')
@endpush
