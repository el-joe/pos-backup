<div>
    <div class="col-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('general.pages.depreciation_expenses.new_asset_entry') }}</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.depreciation_expenses.branch') }}</label>
                        @if(admin()->branch_id == null)
                            <select class="form-select select2" name="data.branch_id">
                                <option value="">{{ __('general.pages.depreciation_expenses.select_branch') }}</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ ($data['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" class="form-control" value="{{ admin()->branch?->name }}" disabled>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.depreciation_expenses.fixed_asset') }}</label>
                        <select class="form-select select2" name="data.fixed_asset_id">
                            <option value="">{{ __('general.pages.depreciation_expenses.select_asset') }}</option>
                            @foreach ($assets as $asset)
                                <option value="{{ $asset->id }}" {{ ($data['fixed_asset_id']??0) == $asset->id ? 'selected' : '' }}>{{ $asset->code }} - {{ $asset->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.depreciation_expenses.category') }}</label>
                        <select class="form-select select2" name="data.expense_category_id">
                            <option value="">{{ __('general.pages.depreciation_expenses.select_category') }}</option>
                            @foreach ($expenseCategories as $cat)
                                <option value="{{ $cat->id }}" {{ ($data['expense_category_id']??0) == $cat->id ? 'selected' : '' }}>{{ $cat->display_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.depreciation_expenses.entry_type') }}</label>
                        <select class="form-select select2" name="data.fixed_asset_entry_type">
                            <option value="depreciation" {{ ($data['fixed_asset_entry_type']??'depreciation') == 'depreciation' ? 'selected' : '' }}>{{ __('general.pages.depreciation_expenses.type_depreciation') }}</option>
                            <option value="repair_expense" {{ ($data['fixed_asset_entry_type']??'') == 'repair_expense' ? 'selected' : '' }}>{{ __('general.pages.depreciation_expenses.type_repair_expense') }}</option>
                            <option value="lifespan_extension" {{ ($data['fixed_asset_entry_type']??'') == 'lifespan_extension' ? 'selected' : '' }}>{{ __('general.pages.depreciation_expenses.type_lifespan_extension') }}</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.depreciation_expenses.amount') }}</label>
                        <input type="number" step="0.01" min="0" class="form-control" wire:model="data.amount">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.depreciation_expenses.date') }}</label>
                        <input type="date" class="form-control" wire:model="data.expense_date">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.depreciation_expenses.tax_percentage') }}</label>
                        <input type="number" step="0.01" min="0" class="form-control" wire:model="data.tax_percentage">
                    </div>

                    @if(($data['fixed_asset_entry_type'] ?? 'depreciation') === 'lifespan_extension')
                        <div class="col-md-4">
                            <label class="form-label">{{ __('general.pages.fixed_assets.added_useful_life_months') }}</label>
                            <input type="number" step="1" min="0" class="form-control" wire:model="data.added_useful_life_months">
                        </div>
                    @endif

                    <div class="col-12">
                        <label class="form-label">{{ __('general.pages.depreciation_expenses.note') }}</label>
                        <textarea class="form-control" rows="2" wire:model="data.note"></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-primary" wire:click="saveExpense">
                        <i class="fa fa-save me-1"></i> {{ __('general.pages.depreciation_expenses.save') }}
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
