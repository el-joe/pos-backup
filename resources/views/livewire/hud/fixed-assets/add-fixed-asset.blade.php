<div>
    <div class="col-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('general.pages.fixed_assets.fixed_asset_details') }}</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.fixed_assets.branch') }}</label>
                        @if(admin()->branch_id == null)
                            <select class="form-select select2" name="data.branch_id">
                                <option value="">{{ __('general.pages.fixed_assets.select_branch') }}</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $branch->id == ($data['branch_id']??0) ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" class="form-control" value="{{ admin()->branch?->name }}" disabled>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.fixed_assets.code') }}</label>
                        <input type="text" class="form-control" wire:model="data.code" disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.fixed_assets.name') }}</label>
                        <input type="text" class="form-control" wire:model="data.name">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.fixed_assets.purchase_date') }}</label>
                        <input type="date" class="form-control" wire:model="data.purchase_date">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.fixed_assets.cost') }}</label>
                        <input type="number" step="0.01" min="0" class="form-control" wire:model="data.cost">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.fixed_assets.payment_status') }}</label>
                        <select class="form-select select2" name="data.payment_status">
                            <option value="pending" {{ ($data['payment_status'] ?? 'full_paid') == 'pending' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.payment_unpaid') }}</option>
                            <option value="partial_paid" {{ ($data['payment_status'] ?? '') == 'partial_paid' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.payment_partial') }}</option>
                            <option value="full_paid" {{ ($data['payment_status'] ?? 'full_paid') == 'full_paid' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.payment_paid') }}</option>
                        </select>
                        @error('data.payment_status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    @if(in_array(($data['payment_status'] ?? 'full_paid'), ['partial_paid', 'full_paid']))
                        <div class="col-md-8">
                            <label class="form-label d-block">{{ __('general.pages.fixed_assets.payment_distribution') }}</label>
                            <div class="border rounded p-3 bg-light">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-4">
                                        <div class="small text-muted">{{ __('general.pages.fixed_assets.total_paid') }}</div>
                                        <div class="fw-semibold">{{ currencyFormat($totalPaid ?? 0, true) }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="small text-muted">{{ __('general.pages.fixed_assets.due_amount') }}</div>
                                        <div class="fw-semibold text-danger">{{ currencyFormat($remainingDue ?? 0, true) }}</div>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#fixedAssetPaymentsModal">
                                            <i class="fa fa-credit-card me-1"></i> {{ __('general.pages.fixed_assets.manage_payments') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.fixed_assets.salvage_value') }}</label>
                        <input type="number" step="0.01" min="0" class="form-control" wire:model="data.salvage_value">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.fixed_assets.useful_life_months') }}</label>
                        <input type="number" step="1" min="0" class="form-control" wire:model="data.useful_life_months">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.fixed_assets.depreciation_rate') }}</label>
                        <input type="number" step="0.0001" min="0" max="100" class="form-control" wire:model="data.depreciation_rate">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.fixed_assets.depreciation_method') }}</label>
                        <select class="form-select select2" name="data.depreciation_method">
                            <option value="straight_line" {{ ($data['depreciation_method'] ?? 'straight_line') == 'straight_line' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.method_straight_line') }}</option>
                            <option value="declining_balance" {{ ($data['depreciation_method'] ?? '') == 'declining_balance' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.method_declining_balance') }}</option>
                            <option value="double_declining_balance" {{ ($data['depreciation_method'] ?? '') == 'double_declining_balance' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.method_double_declining_balance') }}</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.fixed_assets.depreciation_start_date') }}</label>
                        <input type="date" class="form-control" wire:model="data.depreciation_start_date">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.fixed_assets.status') }}</label>
                        <select class="form-select select2" name="data.status">
                            <option value="active" {{ ($data['status']??'active') == 'active' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_active') }}</option>
                            <option value="under_construction" {{ ($data['status']??'') == 'under_construction' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_under_construction') }}</option>
                            <option value="disposed" {{ ($data['status']??'') == 'disposed' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_disposed') }}</option>
                            <option value="sold" {{ ($data['status']??'') == 'sold' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_sold') }}</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">{{ __('general.pages.fixed_assets.note') }}</label>
                        <textarea class="form-control" rows="2" wire:model="data.note"></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-primary" wire:click="saveAsset">
                        <i class="fa fa-save me-1"></i> {{ __('general.pages.fixed_assets.save') }}
                    </button>
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

    <div class="modal fade" id="fixedAssetPaymentsModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="card shadow-sm mb-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fa fa-credit-card me-2"></i> {{ __('general.pages.fixed_assets.manage_payments') }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="card-body">
                        <div class="alert alert-info mb-4 d-flex justify-content-between flex-wrap gap-2">
                            <span><strong>{{ __('general.pages.fixed_assets.cost') }}:</strong> {{ currencyFormat($cost ?? 0, true) }}</span>
                            <span><strong>{{ __('general.pages.fixed_assets.total_paid') }}:</strong> {{ currencyFormat($totalPaid ?? 0, true) }}</span>
                            <span><strong>{{ __('general.pages.fixed_assets.due_amount') }}:</strong> {{ currencyFormat($remainingDue ?? 0, true) }}</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-3">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('general.pages.fixed_assets.payment_account') }}</th>
                                        <th>{{ __('general.pages.fixed_assets.amount') }}</th>
                                        <th class="text-center" style="width: 70px;">{{ __('general.pages.fixed_assets.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($payments as $index => $payment)
                                        <tr>
                                            <td>
                                                <select class="form-select" wire:model="payments.{{ $index }}.account_id">
                                                    <option value="">{{ __('general.pages.fixed_assets.select_payment_account') }}</option>
                                                    @foreach (collect($paymentAccounts ?? [])->filter() as $account)
                                                        <option value="{{ data_get($account, 'id') }}">
                                                            {{ data_get($account, 'paymentMethod.name') }} - {{ data_get($account, 'name') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" wire:model="payments.{{ $index }}.amount" step="0.01" min="0">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" wire:click="removePayment({{ $index }})" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">{{ __('general.pages.fixed_assets.no_payment_rows') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-secondary" wire:click="addPayment">
                            <i class="fa fa-plus"></i> {{ __('general.pages.fixed_assets.add_payment') }}
                        </button>
                    </div>

                    <div class="card-footer d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('general.pages.fixed_assets.close') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@include('layouts.hud.partials.select2-script')
@endpush
