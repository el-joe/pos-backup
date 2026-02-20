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
</div>

@push('scripts')
@include('layouts.hud.partials.select2-script')
@endpush
