<div class="container-fluid">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.reports.common.filter_options')" icon="fa-filter" class="mb-4">
        <div class="row g-3">
            <div class="col-sm-6">
                <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.reports.performance.expense_breakdown.title')" icon="fa-list-alt" :render-table="false">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover align-middle mb-0">
                        <thead class="table-secondary text-dark">
                            <tr>
                                <th>{{ __('general.pages.reports.common.category') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.performance.customer_outstanding.total_debit') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.performance.customer_outstanding.total_credit') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.common.amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $sum_debit = 0;
                                $sum_credit = 0;
                                $sum_net = 0;
                            @endphp
                            @forelse($report as $row)
                                @php
                                    $sum_debit += $row->total_debit;
                                    $sum_credit += $row->total_credit;
                                    $net = $row->total_debit - $row->total_credit;
                                    $sum_net += $net;
                                @endphp
                                <tr>
                                    <td>{{ $row->category_name }}</td>
                                    <td class="text-end">{{ currencyFormat($row->total_debit, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($row->total_credit, true) }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-{{ $net > 0 ? 'danger' : ($net < 0 ? 'success' : 'secondary') }}">
                                            {{ currencyFormat($net, true) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        {{ __('general.pages.reports.performance.expense_breakdown.no_data') }}
                                    </td>
                                </tr>
                            @endforelse
                            @if(count($report))
                                <tr class="fw-semibold table-success text-dark">
                                    <td>{{ __('general.pages.reports.common.total') }}</td>
                                    <td class="text-end">{{ currencyFormat($sum_debit, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($sum_credit, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($sum_net, true) }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
    </x-tenant-tailwind-gemini.table-card>
</div>
@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.daterange-picker-script')
@endpush
