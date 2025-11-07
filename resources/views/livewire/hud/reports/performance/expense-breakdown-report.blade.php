<div class="container-fluid">
    <!-- Filter Options Card -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0 bg-dark text-light">
            <div class="card-header d-flex align-items-center">
                <i class="fa fa-filter me-2"></i>
                <strong>Filter Options</strong>
            </div>
            <div class="card-body">
                <form class="row g-3">
                    <div class="col-md-3">
                        <label for="from_date" class="form-label">From</label>
                        <input type="date" id="from_date" wire:model.lazy="from_date" class="form-control form-control-sm bg-dark text-light border-secondary">
                    </div>
                    <div class="col-md-3">
                        <label for="to_date" class="form-label">To</label>
                        <input type="date" id="to_date" wire:model.lazy="to_date" class="form-control form-control-sm bg-dark text-light border-secondary">
                    </div>
                </form>
            </div>
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </div>
    </div>

    <!-- Expense Breakdown Report Card -->
    <div class="col-12">
        <div class="card shadow-sm border-0 bg-dark text-light">
            <div class="card-header d-flex align-items-center">
                <i class="fa fa-list-alt me-2"></i>
                <h5 class="card-title mb-0">Expense Breakdown Report</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover align-middle mb-0">
                        <thead class="table-secondary text-dark">
                            <tr>
                                <th>Category</th>
                                <th class="text-end">Total Debit</th>
                                <th class="text-end">Total Credit</th>
                                <th class="text-end">Net</th>
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
                                    <td class="text-end">{{ number_format($row->total_debit, 2) }}</td>
                                    <td class="text-end">{{ number_format($row->total_credit, 2) }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-{{ $net > 0 ? 'danger' : ($net < 0 ? 'success' : 'secondary') }}">
                                            {{ number_format($net, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        No expense data found for the selected period.
                                    </td>
                                </tr>
                            @endforelse
                            @if(count($report))
                                <tr class="fw-semibold table-success text-dark">
                                    <td>Total</td>
                                    <td class="text-end">{{ number_format($sum_debit, 2) }}</td>
                                    <td class="text-end">{{ number_format($sum_credit, 2) }}</td>
                                    <td class="text-end">{{ number_format($sum_net, 2) }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </div>
    </div>
</div>
