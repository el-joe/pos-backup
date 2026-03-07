<div class="col-sm-12">
    <x-admin.filter-card
        :title="__('general.pages.branches.filters')"
        icon="fa-filter"
        collapse-id="adminBranchFilterCollapse"
        :collapsed="$collapseFilters"
    >
        <x-slot:actions>
            <button type="button"
                    class="btn btn-default btn-sm"
                    wire:click="$toggle('collapseFilters')"
                    data-toggle="collapse"
                    data-target="#adminBranchFilterCollapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}">
                <i class="fa fa-filter"></i> {{ __('general.pages.branches.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row">
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.branches.search') }}</label>
                <input type="text"
                       class="form-control"
                       placeholder="{{ __('general.pages.branches.search') }} ..."
                       wire:model.live="filters.search">
            </div>

            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.branches.status') }}</label>
                <select class="form-control" wire:model.live="filters.active">
                    <option value="">{{ __('general.pages.branches.all') }}</option>
                    <option value="1">{{ __('general.pages.branches.active') }}</option>
                    <option value="0">{{ __('general.pages.branches.inactive') }}</option>
                </select>
            </div>

            <div class="col-xs-12 text-right">
                <button type="button" class="btn btn-default btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.branches.reset') }}
                </button>
            </div>
        </div>
    </x-admin.filter-card>

    <x-admin.table-card :title="__('general.pages.branches.branches')" icon="fa-code-branch">
        <x-slot:actions>
            @adminCan('branches.export')
                <button type="button" class="btn btn-success btn-sm" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel-o"></i> {{ __('general.pages.branches.export') }}
                </button>
            @endadminCan
            @adminCan('branches.create')
                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editBranchModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> {{ __('general.pages.branches.new_branch') }}
                </a>
            @endadminCan
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>{{ __('general.pages.branches.id') }}</th>
                <th>{{ __('general.pages.branches.name') }}</th>
                <th>{{ __('general.pages.branches.phone') }}</th>
                <th>{{ __('general.pages.branches.email') }}</th>
                <th>{{ __('general.pages.branches.address') }}</th>
                <th>{{ __('general.pages.branches.tax') }}</th>
                <th>{{ __('general.pages.branches.active') }}</th>
                <th class="text-nowrap">{{ __('general.pages.branches.action') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($branches as $branch)
            <tr>
                <td>{{ $branch->id }}</td>
                <td>{{ $branch->name }}</td>
                <td>{{ $branch->phone }}</td>
                <td>{{ $branch->email }}</td>
                <td>{{ $branch->address }}</td>
                <td>
                    @if($branch->tax)
                        {{ $branch->tax?->name }} ({{ $branch->tax?->rate ?? 0 }}%)
                    @else
                        {{ __('general.pages.branches.n_a') }}
                    @endif
                </td>
                <td>
                    <span class="badge badge-{{ $branch->active ? 'success' : 'danger' }}">
                        {{ $branch->active ? __('general.pages.branches.active') : __('general.pages.branches.inactive') }}
                    </span>
                </td>
                <td class="text-nowrap">
                    @adminCan('branches.update')
                        <a href="javascript:void(0)"
                           data-toggle="modal"
                           data-target="#editBranchModal"
                           wire:click="setCurrent({{ $branch->id }})"
                           data-original-title="{{ __('general.pages.branches.edit') }}">
                            <i class="fa fa-pencil text-primary m-r-10"></i>
                        </a>
                    @endadminCan
                    @adminCan('branches.delete')
                        <a href="javascript:void(0)"
                           data-original-title="{{ __('general.pages.branches.delete') }}"
                           wire:click="deleteAlert({{ $branch->id }})">
                            <i class="fa fa-close text-danger"></i>
                        </a>
                    @endadminCan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted">No data found.</td>
            </tr>
        @endforelse

        @if($branches->hasPages())
            <x-slot:footer>
                {{ $branches->links() }}
            </x-slot:footer>
        @endif
    </x-admin.table-card>

    {{-- add edit modal for branches page --}}
    <div class="modal fade" id="editBranchModal" tabindex="-1" role="dialog" aria-labelledby="editBranchModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBranchModalLabel">{{ $current?->id ? 'Edit' : 'New' }} Branch</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="branchName">Name</label>
                            <input type="text" class="form-control" wire:model="data.name" id="branchName" placeholder="Enter branch name">
                        </div>
                        <div class="form-group">
                            <label for="branchPhone">Phone</label>
                            <input type="text" class="form-control" wire:model="data.phone" id="branchPhone" placeholder="Enter branch phone">
                        </div>
                        <div class="form-group">
                            <label for="branchEmail">Email</label>
                            <input type="email" class="form-control" wire:model="data.email" id="branchEmail" placeholder="Enter branch email">
                        </div>
                        <div class="form-group">
                            <label for="branchAddress">Address</label>
                            <input type="text" class="form-control" wire:model="data.address" id="branchAddress" placeholder="Enter branch address">
                        </div>
                        <div class="form-group">
                            <label for="branchWebsite">Website</label>
                            <input type="text" class="form-control" wire:model="data.website" id="branchWebsite" placeholder="Enter branch website">
                        </div>
                        <div class="form-group">
                            <label for="branchTax">Tax</label>
                            <select class="form-control" wire:model="data.tax_id" id="branchTax">
                                <option value="">Select Tax</option>
                                @foreach ($taxes as $tax)
                                    <option value="{{ $tax->id }}">{{ $tax->name }} ({{ $tax->rate }}%)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="custom-checkbox">
                                <input type="checkbox" id="branchActive" wire:model="data.active">
                                <span class="checkmark"></span> Is Active
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="save">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
@endpush
