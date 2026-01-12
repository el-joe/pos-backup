<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.sale_requests.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#saleRequestFilterCollapse">
                <i class="fa fa-filter me-1"></i> Show/Hide
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="saleRequestFilterCollapse">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.sale_requests.search_label') }}</label>
                        <input type="text" class="form-control" placeholder="{{ __('general.pages.sale_requests.search_placeholder') }}" wire:model.blur="filters.search">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.sale_requests.customer') }}</label>
                        <select class="form-select select2" name="filters.customer_id">
                            <option value="">{{ __('general.pages.sale_requests.all_customers') }}</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ ($filters['customer_id']??'') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.sale_requests.branch') }}</label>
                        <select class="form-select select2" name="filters.branch_id">
                            <option value="">{{ __('general.pages.sale_requests.all_branches_option') }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ ($filters['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.sale_requests.reset') }}
                        </button>
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

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">{{ __('general.pages.sale_requests.sale_requests') }}</h3>
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-outline-success" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.sale_requests.export') }}
                </button>
                <a class="btn btn-primary btn-sm" href="{{ route('admin.sale-requests.create') }}">
                    <i class="fa fa-plus"></i> {{ __('general.pages.sale_requests.new_sale_request') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>{{ __('general.pages.sale_requests.id') }}</th>
                            <th>{{ __('general.pages.sale_requests.quote_no') }}</th>
                            <th>{{ __('general.pages.sale_requests.customer') }}</th>
                            <th>{{ __('general.pages.sale_requests.branch') }}</th>
                            <th>{{ __('general.pages.sale_requests.status') }}</th>
                            <th>{{ __('general.pages.sale_requests.total') }}</th>
                            <th class="text-nowrap text-center">{{ __('general.pages.sale_requests.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $request)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td>{{ $request->quote_number }}</td>
                                <td>{{ $request->customer?->name }}</td>
                                <td>{{ $request->branch?->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $request->status?->colorClass() ?? 'secondary' }}">
                                        {{ $request->status?->label() ?? (string) $request->status }}
                                    </span>
                                </td>
                                <td>{{ currencyFormat($request->grand_total_amount ?? 0, true) }}</td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.sale-requests.details', $request->id) }}">
                                        {{ __('general.pages.sale_requests.details') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">{{ __('general.pages.sale_requests.no_records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>
