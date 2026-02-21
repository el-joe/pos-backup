<div class="col-12">

    <!-- Stock Transfer Details -->
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h3 class="card-title mb-0">{{ __('general.pages.stock-transfers.stock_transfer_details') }}</h3>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="from_branch_id" class="form-label">{{ __('general.pages.stock-transfers.from_branch') }}</label>
                    @if(admin()->branch_id == null)
                    <div class="d-flex">
                        <select id="from_branch_id" name="data.from_branch_id" class="form-select select2">
                            <option value="">{{ __('general.pages.stock-transfers.select_branch') }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ ($data['from_branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-theme me-2" data-bs-toggle="modal" data-bs-target="#editBranchModal" wire:click="$dispatch('branch-set-current', {id : null})">+</button>
                    </div>
                    @else
                    <input type="text" class="form-control" value="{{ admin()->branch->name }}" disabled>
                    @endif
                </div>

                <div class="col-md-4">
                    <label for="to_branch_id" class="form-label">{{ __('general.pages.stock-transfers.to_branch') }}</label>
                    <div class="d-flex">
                        <select id="to_branch_id" name="data.to_branch_id" class="form-select select2">
                            <option value="">{{ __('general.pages.stock-transfers.select_branch') }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ ($data['to_branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-theme me-2" data-bs-toggle="modal" data-bs-target="#editBranchModal" wire:click="$dispatch('branch-set-current', {id : null})">+</button>
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="ref_no" class="form-label">{{ __('general.pages.stock-transfers.ref_no') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-product-hunt"></i></span>
                        <input type="text" class="form-control" id="ref_no" placeholder="{{ __('general.pages.stock-transfers.ref_no') }}" wire:model="data.ref_no">
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="transfer_date" class="form-label">{{ __('general.pages.stock-transfers.transfer_date') }}</label>
                    <input type="date" class="form-control" id="transfer_date" wire:model="data.transfer_date">
                </div>

                <div class="col-md-4">
                    <label for="expense_paid_branch_id" class="form-label">{{ __('general.pages.stock-transfers.expense_payer_question') }}</label>
                    <select id="expense_paid_branch_id" name="data.expense_paid_branch_id" class="form-select select2">
                        <option value="">{{ __('general.pages.stock-transfers.select_branch') }}</option>
                        @foreach ($selectedBranches as $branch)
                            <option value="{{ $branch->id }}" {{ ($data['expense_paid_branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="status" class="form-label">{{ __('general.pages.stock-transfers.status') }}</label>
                    <select id="status" name="data.status" class="form-select select2">
                        <option value="">{{ __('general.pages.stock-transfers.select_status') }}</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}" {{ ($data['status']??'') == $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
                        @endforeach
                    </select>
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

    <!-- Order Products -->
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h3 class="card-title mb-0">{{ __('general.pages.stock-transfers.order_products') }}</h3>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                        <input
                            type="text"
                            class="form-control"
                            id="product_search"
                            placeholder="{{ __('general.pages.stock-transfers.search_product_placeholder') }}"
                            onkeydown="productSearchEvent(event)"
                        >
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('general.pages.stock-transfers.product') }}</th>
                            <th>{{ __('general.pages.stock-transfers.unit') }}</th>
                            <th>{{ __('general.pages.stock-transfers.qty') }}</th>
                            <th>{{ __('general.pages.stock-transfers.unit_price') }}</th>
                            <th>{{ __('general.pages.stock-transfers.selling_price') }}</th>
                            <th>{{ __('general.pages.stock-transfers.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (($items ?? []) as $index => $product)
                            <tr>
                                <td class="fw-semibold">{{ $product['name'] }}</td>
                                <td>
                                    <select wire:model.change="items.{{ $index }}.unit_id" class="form-select">
                                        <option value="">{{ __('general.pages.stock-transfers.select_unit') }}</option>
                                        @foreach ($product['units'] as $unit)
                                            <option value="{{ $unit['id'] }}" {{ $items[$index]['unit_id'] == $unit['id'] ? 'selected' : '' }}>
                                                {{ $unit['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control" wire:model.blur="items.{{ $index }}.qty" step="any" min="1" max="{{ $product['max_stock'] ?? 0 }}" placeholder="0.00">
                                    <small class="text-muted">{{ __('general.pages.pos-page.max') }}: {{ $product['max_stock'] }}</small>
                                </td>
                                <td>{{ currencyFormat($product['unit_cost'], true) }}</td>
                                <td>{{ currencyFormat($product['sell_price'], true) }}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm" wire:click="delete({{ $index }})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
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

    <!-- Expenses -->
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h3 class="card-title mb-0">{{ __('general.pages.stock-transfers.expenses') }}</h3>
        </div>

        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>{{ __('general.pages.purchases.expense_category') }}</th>
                        <th>{{ __('general.pages.stock-transfers.description') }}</th>
                        <th>{{ __('general.pages.stock-transfers.amount') }}</th>
                        <th>{{ __('general.pages.stock-transfers.expense_date') }}</th>
                        <th>{{ __('general.pages.stock-transfers.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['expenses'] ?? [] as $index => $expense)
                        <tr>
                            <td>
                                <select
                                    id="expense_category_{{ $index }}"
                                    name="data.expenses.{{ $index }}.expense_category_id"
                                    class="form-select select2">
                                    <option value="">{{ __('general.pages.reports.purchases.select_expense_category') }}</option>
                                    @foreach ($expenseCategories as $category)
                                        <option value="{{ $category->id }}" {{ ($expense['expense_category_id']??null) == $category->id ? 'selected' : '' }}>
                                            {{ $category->{app()->getLocale() == 'ar' ? 'ar_name' : 'name'} ?? $category->name }}
                                        </option>
                                        @foreach ($category->children as $child)
                                            <option value="{{ $child->id }}" {{ ($expense['expense_category_id']??null) == $child->id ? 'selected' : '' }}>
                                                &nbsp;&nbsp;-- {{ $child->{app()->getLocale() == 'ar' ? 'ar_name' : 'name'} ?? $child->name }}
                                            </option>
                                        @endforeach
                                    @endforeach
                            </td>

                            <td><input type="text" class="form-control" wire:model="data.expenses.{{ $index }}.description" placeholder="{{ __('general.pages.stock-transfers.description') }}"></td>
                            <td><input type="number" class="form-control" wire:model.blur="data.expenses.{{ $index }}.amount" step="any" min="0" placeholder="0.00"></td>
                            <td><input type="date" class="form-control" wire:model="data.expenses.{{ $index }}.expense_date"></td>
                            <td>
                                <button class="btn btn-danger btn-sm" wire:click="removeExpense({{ $index }})">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button class="btn btn-primary mt-2" wire:click="addExpense">
                <i class="fa fa-plus"></i> {{ __('general.pages.stock-transfers.add_new_expense') }}
            </button>
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <div class="row justify-content-center g-3">
                <div class="col-md-3">
                    <button type="button" class="btn btn-success w-100 btn-lg"
                        wire:click="save" {{ count($items ?? []) === 0 ? 'disabled' : '' }}>
                        <i class="fa fa-save"></i> {{ __('general.pages.stock-transfers.do_transfer') }}
                    </button>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-secondary w-100 btn-lg">
                        <i class="fa fa-times"></i> {{ __('general.pages.stock-transfers.cancel') }}
                    </button>
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
</div>

@push('styles')
<style>
    .order-products-table th, .order-products-table td {
        white-space: nowrap;
    }
    .order-products-table thead th {
        background: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    .order-products-table tbody tr:hover {
        background: #f1f3f9;
        transition: background 0.2s;
    }
</style>
@endpush

@push('scripts')
@livewire('admin.branches.branch-modal')
@include('layouts.hud.partials.select2-script')
<script>
    window.addEventListener('reset-search-input', () => {
        const input = document.getElementById('product_search');
        if (input) input.value = '';
    });

    function productSearchEvent(event) {
        if (event.key === "Enter") {
            @this.set('product_search', event.target.value);
            clearTimeout(window.productSearchTimeout);
        } else {
            clearTimeout(window.productSearchTimeout);
            window.productSearchTimeout = setTimeout(() => {
                @this.set('product_search', event.target.value);
            }, 2000);
        }
    }
</script>
@endpush
