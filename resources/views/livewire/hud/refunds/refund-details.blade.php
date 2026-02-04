<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">{{ __('general.pages.refunds.refund_details') }} #{{ $refund->id }}</h5>
                <div class="text-muted small">{{ $refund->created_at?->format('Y-m-d H:i') }}</div>
            </div>

            <div class="d-flex gap-2">
                <a class="btn btn-outline-secondary" href="{{ route('admin.refunds.list', ['order_type' => $refund->order_type === \App\Models\Tenant\Sale::class ? 'sale' : 'purchase']) }}">
                    <i class="fa fa-arrow-left me-1"></i> {{ __('general.pages.refunds.back') }}
                </a>

                @if($order)
                    <a class="btn btn-outline-primary" target="_blank"
                       href="{{ $refund->order_type === \App\Models\Tenant\Sale::class ? route('admin.sales.details', $refund->order_id) : route('admin.purchases.details', $refund->order_id) }}">
                        <i class="fa fa-external-link me-1"></i> {{ __('general.pages.refunds.view_order') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="border rounded p-3 h-100">
                        <div class="text-muted small">{{ __('general.pages.refunds.order_type') }}</div>
                        <div class="fw-semibold">{{ class_basename($refund->order_type) }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3 h-100">
                        <div class="text-muted small">{{ __('general.pages.refunds.order_id') }}</div>
                        <div class="fw-semibold">#{{ $refund->order_id }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3 h-100">
                        <div class="text-muted small">{{ __('general.pages.refunds.items_count') }}</div>
                        <div class="fw-semibold">{{ $refund->items?->count() ?? 0 }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3 h-100">
                        <div class="text-muted small">{{ __('general.pages.refunds.total') }}</div>
                        <div class="fw-semibold">{{ isset($refund->total) ? currencyFormat($refund->total, true) : '—' }}</div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="border rounded p-3">
                        <div class="text-muted small">{{ __('general.pages.refunds.reason') }}</div>
                        <div class="fw-semibold">{{ $refund->reason ?: '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>


    <div class="card shadow-sm mb-3">
        <div class="card-header">
            <h5 class="mb-0">{{ __('general.pages.refunds.refund_items') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('general.pages.refunds.product') }}</th>
                            <th>{{ __('general.pages.refunds.unit') }}</th>
                            <th>{{ __('general.pages.refunds.qty') }}</th>
                            <th>{{ __('general.pages.refunds.refundable') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($refund->items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->product?->name ?? '—' }}</td>
                                <td>{{ $item->unit?->name ?? '—' }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>
                                    @if($item->refundable)
                                        {{ class_basename($item->refundable_type) }} #{{ $item->refundable_id }}
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">{{ __('general.pages.refunds.no_items') }}</td>
                            </tr>
                        @endforelse
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


    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">{{ __('general.pages.refunds.transactions') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('general.pages.refunds.date') }}</th>
                            <th>{{ __('general.pages.refunds.type') }}</th>
                            <th>{{ __('general.pages.refunds.description') }}</th>
                            <th>{{ __('general.pages.refunds.amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->id }}</td>
                                <td>{{ $transaction->date ?? $transaction->created_at?->format('Y-m-d') }}</td>
                                <td>{{ $transaction->type?->label() ?? (string)$transaction->type }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td>{{ currencyFormat($transaction->amount ?? 0, true) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="p-0">
                                    <div class="p-3 bg-body-tertiary">
                                        <div class="fw-semibold mb-2">{{ __('general.pages.refunds.transaction_lines') }}</div>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered mb-0 align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>{{ __('general.pages.refunds.line_type') }}</th>
                                                        <th>{{ __('general.pages.refunds.account') }}</th>
                                                        <th class="text-end">{{ __('general.pages.refunds.amount') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($transaction->lines as $line)
                                                        <tr>
                                                            <td>{{ ucfirst($line->type) }}</td>
                                                            <td>{{ $line->account?->name ?? '—' }}</td>
                                                            <td class="text-end">{{ currencyFormat($line->amount ?? 0, true) }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="text-center text-muted py-2">—</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">{{ __('general.pages.refunds.no_transactions') }}</td>
                            </tr>
                        @endforelse
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
