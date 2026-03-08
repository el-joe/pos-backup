<div class="col-12">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.purchase_requests.filters')" icon="fa-filter" collapse-id="purchaseRequestFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}" wire:click="$toggle('collapseFilters')" data-bs-target="#purchaseRequestFilterCollapse">
                <i class="fa fa-filter me-1"></i> Show/Hide
            </button>
        </x-slot:actions>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.purchase_requests.request_no') }}</label>
                <input type="text" class="form-control" placeholder="{{ __('general.pages.purchase_requests.search_placeholder') }}" wire:model.blur="filters.request_number">
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.purchase_requests.supplier') }}</label>
                <select class="form-select select2" name="filters.supplier_id">
                    <option value="">{{ __('general.pages.purchase_requests.all_suppliers') }}</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ ($filters['supplier_id']??'') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.purchase_requests.branch') }}</label>
                <select class="form-select select2" name="filters.branch_id">
                    <option value="">{{ __('general.pages.purchase_requests.all_branches_option') }}</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ ($filters['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo me-1"></i> {{ __('general.pages.purchase_requests.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.purchase_requests.purchase_requests')" icon="fa-file-text-o">
        <x-slot:actions>
            <button class="btn btn-outline-success" wire:click="$set('export', 'excel')">
                <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.purchase_requests.export') }}
            </button>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.purchase-requests.create') }}">
                <i class="fa fa-plus"></i> {{ __('general.pages.purchase_requests.new_purchase_request') }}
            </a>
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>{{ __('general.pages.purchase_requests.id') }}</th>
                <th>{{ __('general.pages.purchase_requests.request_no') }}</th>
                <th>{{ __('general.pages.purchase_requests.supplier') }}</th>
                <th>{{ __('general.pages.purchase_requests.branch') }}</th>
                <th>{{ __('general.pages.purchase_requests.status') }}</th>
                <th>{{ __('general.pages.purchase_requests.total') }}</th>
                <th class="text-nowrap text-center">{{ __('general.pages.purchase_requests.action') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($requests as $request)
            <tr>
                <td>{{ $request->id }}</td>
                <td>{{ $request->request_number }}</td>
                <td>{{ $request->supplier?->name ?? __('general.messages.n_a') }}</td>
                <td>{{ $request->branch?->name }}</td>
                <td>
                    <span class="badge bg-{{ $request->status?->colorClass() ?? 'secondary' }}">
                        {{ $request->status?->label() ?? (string) $request->status }}
                    </span>
                </td>
                <td>{{ currencyFormat($request->total_amount ?? 0, true) }}</td>
                <td class="text-center">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.purchase-requests.details', $request->id) }}">
                        {{ __('general.pages.purchase_requests.details') }}
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">{{ __('general.pages.purchase_requests.no_records') }}</td>
            </tr>
        @endforelse

        <x-slot:footer>
            {{ $requests->links('pagination::default5') }}
        </x-slot:footer>
    </x-tenant-tailwind-gemini.table-card>
</div>
