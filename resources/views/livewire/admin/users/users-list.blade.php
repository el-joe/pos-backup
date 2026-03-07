<div class="col-sm-12">
    <?php $_name = $type == 'customer' ? 'customers' : ($type == 'supplier' ? 'suppliers' : 'users'); ?>

    <x-admin.filter-card
        :title="__('general.pages.users.filters')"
        icon="fa-filter"
        collapse-id="adminUserFilterCollapse"
        :collapsed="$collapseFilters"
    >
        <x-slot:actions>
            <button type="button"
                    class="btn btn-default btn-sm"
                    wire:click="$toggle('collapseFilters')"
                    data-toggle="collapse"
                    data-target="#adminUserFilterCollapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}">
                <i class="fa fa-filter"></i> {{ __('general.pages.users.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row">
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.users.search_label') }}</label>
                <input type="text"
                       class="form-control"
                       placeholder="{{ __('general.pages.users.search_placeholder') }}"
                       wire:model.live="filters.search">
            </div>

            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.users.status') }}</label>
                <select class="form-control" wire:model.live="filters.active">
                    <option value="">{{ __('general.pages.users.all') }}</option>
                    <option value="1">{{ __('general.pages.users.active') }}</option>
                    <option value="0">{{ __('general.pages.users.inactive') }}</option>
                </select>
            </div>

            <div class="col-xs-12 text-right">
                <button type="button" class="btn btn-default btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.users.reset') }}
                </button>
            </div>
        </div>
    </x-admin.filter-card>

    <x-admin.table-card :title="$type != null ? ucfirst($type) : __('general.pages.users.users')" icon="fa-users">
        <x-slot:actions>
            @adminCan($_name . '.export')
                <button type="button" class="btn btn-success btn-sm" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel-o"></i> {{ __('general.pages.users.export') }}
                </button>
            @endadminCan
            @adminCan($_name . '.create')
                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editUserModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> {{ __('general.pages.users.new_user') }}
                </a>
            @endadminCan
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>{{ __('general.pages.users.id') }}</th>
                <th>{{ __('general.pages.users.name') }}</th>
                <th>{{ __('general.pages.users.email') }}</th>
                <th>{{ __('general.pages.users.phone') }}</th>
                <th>{{ __('general.pages.users.address') }}</th>
                @if($type == 'customer')
                    <th>{{ __('general.pages.users.sales_threshold') }}</th>
                @endif
                <th>{{ __('general.pages.users.vat_number') }}</th>
                @if(in_array($type, ['customer', 'supplier']))
                    <th>{{ __('general.pages.users.due_amount') }}</th>
                @endif
                <th>{{ __('general.pages.users.active') }}</th>
                <th class="text-nowrap">{{ __('general.pages.users.action') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->address }}</td>
                @if($type == 'customer')
                    <td>{{ currencyFormat($user->sales_threshold, true) }}</td>
                @endif
                <td>{{ $user->vat_number }}</td>
                @if(in_array($type, ['customer', 'supplier']))
                    @php($totalDue = (float)($dueTotals[$user->id] ?? 0))
                    <td>
                        <span class="badge badge-{{ $totalDue > 0 ? 'danger' : 'default' }}">{{ currencyFormat($totalDue, true) }}</span>
                    </td>
                @endif
                <td>
                    <span class="badge badge-{{ $user->active ? 'success' : 'danger' }}">
                        {{ $user->active ? __('general.pages.users.active') : __('general.pages.users.inactive') }}
                    </span>
                </td>
                <td class="text-nowrap">
                    @if($type == 'customer' && (float)($dueTotals[$user->id] ?? 0) > 0)
                        <a href="{{ route('admin.customers.pay', $user->id) }}" class="btn btn-success btn-xs m-r-5" title="{{ __('general.pages.users.pay') }}">
                            <i class="fa fa-money"></i>
                        </a>
                    @elseif($type == 'supplier' && (float)($dueTotals[$user->id] ?? 0) > 0)
                        <a href="{{ route('admin.suppliers.pay', $user->id) }}" class="btn btn-success btn-xs m-r-5" title="{{ __('general.pages.users.pay') }}">
                            <i class="fa fa-money"></i>
                        </a>
                    @endif
                    @adminCan($_name . '.update')
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#editUserModal" wire:click="setCurrent({{ $user->id }})" data-original-title="{{ __('general.pages.users.edit') }}">
                            <i class="fa fa-pencil text-primary m-r-10"></i>
                        </a>
                    @endadminCan
                    @adminCan($_name . '.delete')
                        <a href="javascript:void(0)" data-original-title="{{ __('general.pages.users.delete') }}" wire:click="deleteAlert({{ $user->id }})">
                            <i class="fa fa-close text-danger m-r-10"></i>
                        </a>
                    @endadminCan
                    @adminCan($_name . '.show')
                        <a href="{{ route('admin.users.details', $user->id) }}" data-original-title="{{ __('general.pages.users.view') }}">
                            <i class="fa fa-eye text-info"></i>
                        </a>
                    @endadminCan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ in_array($type, ['customer', 'supplier']) ? ($type == 'customer' ? 10 : 9) : 8 }}" class="text-center text-muted">No data found.</td>
            </tr>
        @endforelse

        @if($users->hasPages())
            <x-slot:footer>
                {{ $users->links() }}
            </x-slot:footer>
        @endif
    </x-admin.table-card>

    {{-- add edit modal for users page --}}
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">{{ $current?->id ? 'Edit' : 'New' }} User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="userName">Name</label>
                            <input type="text" class="form-control" wire:model="data.name" id="userName" placeholder="Enter user name" required>
                        </div>
                        <div class="form-group">
                            <label for="userEmail">Email</label>
                            <input type="email" class="form-control" wire:model="data.email" id="userEmail" placeholder="Enter user email" required>
                        </div>
                        <div class="form-group">
                            <label for="userPhone">Phone</label>
                            <input type="text" class="form-control" wire:model="data.phone" id="userPhone" placeholder="Enter user phone" required>
                        </div>
                        <div class="form-group">
                            <label for="userAddress">Address</label>
                            <input type="text" class="form-control" wire:model="data.address" id="userAddress" placeholder="Enter user address" required>
                        </div>
                        @if($type == 'customer')
                        <div class="form-group">
                            <label for="userSalesThreshold">Sales Threshold</label>
                            <input type="number" class="form-control" wire:model="data.sales_threshold" id="userSalesThreshold" placeholder="Enter sales threshold" min="0" step="0.01" required>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="custom-checkbox">
                                <input type="checkbox" id="userActive" wire:model="data.active">
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
