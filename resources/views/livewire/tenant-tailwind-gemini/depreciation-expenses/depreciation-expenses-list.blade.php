<div class="col-12">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.depreciation_expenses.filters')" icon="fa-filter" collapse-id="depreciationFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}" wire:click="$toggle('collapseFilters')" data-bs-target="#depreciationFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.depreciation_expenses.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.depreciation_expenses.branch') }}</label>
                <select class="form-select select2" name="filters.branch_id">
                    <option value="">{{ __('general.layout.all_branches') }}</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ ($filters['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.depreciation_expenses.fixed_asset') }}</label>
                <select class="form-select select2" name="filters.model_id">
                    <option value="">{{ __('general.pages.depreciation_expenses.all_assets') }}</option>
                    @foreach ($assets as $asset)
                        <option value="{{ $asset->id }}" {{ ($filters['model_id']??'') == $asset->id ? 'selected' : '' }}>{{ $asset->code }} - {{ $asset->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.depreciation_expenses.category') }}</label>
                <select class="form-select select2" name="filters.expense_category_id">
                    <option value="">{{ __('general.pages.depreciation_expenses.all_categories') }}</option>
                    @foreach ($expenseCategories as $cat)
                        <option value="{{ $cat->id }}" {{ ($filters['expense_category_id']??'') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.depreciation_expenses.date') }}</label>
                <input type="date" class="form-control" wire:model="filters.date" />
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo me-1"></i> {{ __('general.pages.depreciation_expenses.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.depreciation_expenses.depreciation_expenses')" icon="fa-line-chart">
        <x-slot:actions>
            <button class="btn btn-outline-success" wire:click="$set('export', 'excel')">
                <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.depreciation_expenses.export') }}
            </button>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.depreciation-expenses.create') }}">
                <i class="fa fa-plus"></i> {{ __('general.pages.depreciation_expenses.new_asset_entry') }}
            </a>
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>#</th>
                <th>{{ __('general.pages.depreciation_expenses.branch') }}</th>
                <th>{{ __('general.pages.depreciation_expenses.fixed_asset') }}</th>
                <th>{{ __('general.pages.depreciation_expenses.category') }}</th>
                <th>{{ __('general.pages.depreciation_expenses.amount') }}</th>
                <th>{{ __('general.pages.depreciation_expenses.date') }}</th>
                <th class="text-nowrap text-center">{{ __('general.pages.depreciation_expenses.action') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($expenses as $expense)
            <tr>
                <td>{{ $expense->id }}</td>
                <td>{{ $expense->branch?->name ?? '—' }}</td>
                <td>{{ $expense->model?->code ?? '' }} {{ $expense->model?->name ?? $expense->model_id }}</td>
                <td>{{ $expense->category?->display_name ?? '—' }}</td>
                <td>{{ currencyFormat($expense->amount ?? 0, true) }}</td>
                <td>{{ $expense->expense_date }}</td>
                <td class="text-center">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.depreciation-expenses.details', $expense->id) }}">
                        {{ __('general.pages.depreciation_expenses.details') }}
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">{{ __('general.pages.depreciation_expenses.no_records') }}</td>
            </tr>
        @endforelse

        <x-slot:footer>
            {{ $expenses->links('pagination::default5') }}
        </x-slot:footer>
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('scripts')
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
