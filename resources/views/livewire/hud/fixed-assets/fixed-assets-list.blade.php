<div class="col-12">
    <x-hud.filter-card :title="__('general.pages.fixed_assets.filters')" icon="fa-filter" collapse-id="fixedAssetFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}" wire:click="$toggle('collapseFilters')" data-bs-target="#fixedAssetFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.fixed_assets.show_hide') }}
            </button>
        </x-slot:actions>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.fixed_assets.search') }}</label>
                <input type="text" class="form-control" placeholder="{{ __('general.pages.fixed_assets.search_placeholder') }}" wire:model.blur="filters.search">
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.fixed_assets.branch') }}</label>
                <select class="form-select select2" name="filters.branch_id">
                    <option value="">{{ __('general.layout.all_branches') }}</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ ($filters['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.fixed_assets.status') }}</label>
                <select class="form-select select2" name="filters.status">
                    <option value="">{{ __('general.pages.fixed_assets.all_statuses') }}</option>
                    <option value="active" {{ ($filters['status']??'') == 'active' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_active') }}</option>
                    <option value="under_construction" {{ ($filters['status']??'') == 'under_construction' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_under_construction') }}</option>
                    <option value="disposed" {{ ($filters['status']??'') == 'disposed' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_disposed') }}</option>
                    <option value="sold" {{ ($filters['status']??'') == 'sold' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_sold') }}</option>
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-secondary btn-sm" wire:click="resetFilters"><i class="fa fa-undo me-1"></i> {{ __('general.pages.fixed_assets.reset') }}</button>
            </div>
        </div>
    </x-hud.filter-card>

    <x-hud.table-card :title="__('general.pages.fixed_assets.fixed_assets')" icon="fa-building">
        <x-slot:actions>
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-outline-success" wire:click="$set('export', 'excel')"><i class="fa fa-file-excel me-1"></i> {{ __('general.pages.fixed_assets.export') }}</button>
                <a class="btn btn-primary btn-sm" href="{{ route('admin.fixed-assets.create') }}"><i class="fa fa-plus"></i> {{ __('general.pages.fixed_assets.new_fixed_asset') }}</a>
            </div>
        </x-slot:actions>
        <x-slot:head>
            <tr>
                <th>#</th>
                <th>{{ __('general.pages.fixed_assets.code') }}</th>
                <th>{{ __('general.pages.fixed_assets.name') }}</th>
                <th>{{ __('general.pages.fixed_assets.branch') }}</th>
                <th>{{ __('general.pages.fixed_assets.status') }}</th>
                <th>{{ __('general.pages.fixed_assets.payment_status') }}</th>
                <th>{{ __('general.pages.fixed_assets.payments') }}</th>
                <th>{{ __('general.pages.fixed_assets.due_amount') }}</th>
                <th>{{ __('general.pages.fixed_assets.cost') }}</th>
                <th>{{ __('general.pages.fixed_assets.net_book_value') }}</th>
                <th class="text-nowrap text-center">{{ __('general.pages.fixed_assets.action') }}</th>
            </tr>
        </x-slot:head>
        @forelse ($assets as $asset)
            <tr>
                <td>{{ $asset->id }}</td>
                <td>{{ $asset->code }}</td>
                <td>{{ $asset->name }}</td>
                <td>{{ $asset->branch?->name ?? '—' }}</td>
                <td>
                    <span class="badge bg-{{ $asset->status === 'active' ? 'success' : ($asset->status === 'under_construction' ? 'warning' : ($asset->status === 'sold' ? 'info' : 'secondary')) }}">
                        @if($asset->status === 'active')
                            {{ __('general.pages.fixed_assets.status_active') }}
                        @elseif($asset->status === 'under_construction')
                            {{ __('general.pages.fixed_assets.status_under_construction') }}
                        @elseif($asset->status === 'sold')
                            {{ __('general.pages.fixed_assets.status_sold') }}
                        @else
                            {{ __('general.pages.fixed_assets.status_disposed') }}
                        @endif
                    </span>
                </td>
                <td>
                    @php
                        $paymentState = $asset->payment_state;
                        $paymentBadge = match($paymentState) {
                            'paid' => 'success',
                            'partial_paid' => 'warning',
                            'check' => 'info',
                            default => 'danger',
                        };
                        $paymentLabel = match($paymentState) {
                            'paid' => __('general.pages.fixed_assets.payment_paid'),
                            'partial_paid' => __('general.pages.fixed_assets.payment_partial'),
                            'check' => __('general.pages.fixed_assets.payment_check'),
                            default => __('general.pages.fixed_assets.payment_unpaid'),
                        };
                    @endphp
                    <span class="badge bg-{{ $paymentBadge }}">{{ $paymentLabel }}</span>
                </td>
                <td>
                    @php($latestPayment = $asset->orderPayments->sortByDesc('id')->first())
                    <div class="small fw-semibold">{{ $asset->orderPayments->count() }} {{ __('general.pages.fixed_assets.payments_count') }}</div>
                    @if($latestPayment)
                        <div class="text-muted small">
                            {{ $latestPayment->account ? (($latestPayment->account->paymentMethod?->name ? $latestPayment->account->paymentMethod?->name.' - ' : '').$latestPayment->account->name) : __('general.messages.n_a') }}
                        </div>
                    @endif
                </td>
                <td><span class="badge bg-{{ ($asset->due_amount ?? 0) > 0 ? 'danger' : 'success' }}">{{ currencyFormat($asset->due_amount ?? 0, true) }}</span></td>
                <td>{{ currencyFormat($asset->cost ?? 0, true) }}</td>
                <td>{{ currencyFormat($asset->net_book_value ?? 0, true) }}</td>
                <td class="text-center">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.fixed-assets.details', $asset->id) }}">{{ __('general.pages.fixed_assets.details') }}</a>
                    @adminCan('fixed_assets.update')
                        @if(($asset->due_amount ?? 0) > 0)
                            <button class="btn btn-sm btn-success ms-1" wire:click="setCurrent({{ $asset->id }})" data-bs-toggle="modal" data-bs-target="#paymentModal"><i class="fa fa-credit-card"></i></button>
                        @endif
                    @endadminCan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="11" class="text-center">{{ __('general.pages.fixed_assets.no_records') }}</td>
            </tr>
        @endforelse
        <x-slot:footer>
            {{ $assets->links('pagination::default5') }}
        </x-slot:footer>
    </x-hud.table-card>
    <!-- Payment Modal (same UX pattern as sales list) -->
    <div wire:ignore.self class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="paymentModalLabel">{{ __('general.pages.fixed_assets.payment_modal_title') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="paymentAmount" class="form-label fw-bold">{{ __('general.pages.fixed_assets.amount') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">{{ currency()->symbol }}</span>
                                <input type="number" class="form-control" id="paymentAmount" wire:model="payment.amount" placeholder="{{ __('general.pages.fixed_assets.amount') }}">
                                <span class="input-group-text">
                                    {{ __('general.pages.fixed_assets.due_amount') }}:
                                    <strong class="text-danger ms-1">
                                        {{ number_format($current->due_amount ?? 0, 2) }}
                                    </strong>
                                </span>
                            </div>
                            @error('payment.amount')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="paymentMethod" class="form-label fw-bold">{{ __('general.pages.fixed_assets.payment_account') }}</label>
                            <select class="form-select" id="paymentMethod" wire:model="payment.account_id">
                                <option value="">{{ __('general.pages.fixed_assets.select_payment_account') }}</option>
                                @foreach (collect($paymentAccounts ?? []) as $paymentAcc)
                                    <option value="{{ data_get($paymentAcc, 'id') }}">
                                        {{ data_get($paymentAcc, 'paymentMethod.name') }} - {{ data_get($paymentAcc, 'name') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment.account_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="paymentNote" class="form-label fw-bold">{{ __('general.pages.fixed_assets.note') }}</label>
                        <textarea class="form-control" id="paymentNote" wire:model="payment.note" rows="3" placeholder="{{ __('general.pages.fixed_assets.note_optional') }}"></textarea>
                        @error('payment.note')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="button" class="btn btn-success w-100 mt-3" wire:click="savePayment">
                        <i class="fa fa-check"></i> {{ __('general.pages.fixed_assets.save_payment') }}
                    </button>

                    <hr class="my-4">

                    <h5 class="text-primary fw-bold mb-3">{{ __('general.pages.fixed_assets.recent_payments') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped align-middle">
                            <thead class="table-secondary">
                                <tr>
                                    <th>{{ __('general.pages.fixed_assets.date') }}</th>
                                    <th>{{ __('general.pages.fixed_assets.amount') }}</th>
                                    <th>{{ __('general.pages.fixed_assets.method') }}</th>
                                    <th>{{ __('general.pages.fixed_assets.note') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($current?->orderPayments ?? collect())->take(10) as $p)
                                    <tr>
                                        <td>{{ dateTimeFormat($p->created_at,true,false) }}</td>
                                        <td><span class="badge bg-success">{{ currencyFormat($p->amount, true) }}</span></td>
                                        <td>{{ $p->account ? (($p->account->paymentMethod?->name ? $p->account->paymentMethod?->name.' - ' : '').$p->account->name) : __('general.messages.n_a') }}</td>
                                        <td>{{ $p->note }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">{{ __('general.pages.fixed_assets.no_payments') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> {{ __('general.pages.fixed_assets.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>


@push('scripts')
@include('layouts.hud.partials.select2-script')
@endpush
