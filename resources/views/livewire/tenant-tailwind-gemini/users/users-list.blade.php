<div class="col-12">
    <?php $_name = $type == 'customer' ? 'customers' : ($type == 'supplier' ? 'suppliers' : 'users'); ?>

    <x-tenant-tailwind-gemini.filter-card
        :title="__('general.pages.users.filters')"
        icon="fa-filter"
        collapse-id="hudUserFilterCollapse"
        :collapsed="$collapseFilters"
    >
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#hudUserFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.users.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.users.search_label') }}</label>
                <input type="text" class="form-control"
                    placeholder="{{ __('general.pages.users.search_placeholder') }}"
                    wire:model.blur="filters.search">
            </div>

            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.users.status') }}</label>
                <select class="form-select select2" name="filters.active">
                    <option value="all" {{ ($filters['active']??'') == 'all' ? 'selected' : '' }}>{{ __('general.pages.users.all') }}</option>
                    <option value="1" {{ ($filters['active']??'') == '1' ? 'selected' : '' }}>{{ __('general.pages.users.active') }}</option>
                    <option value="0" {{ ($filters['active']??'') == '0' ? 'selected' : '' }}>{{ __('general.pages.users.inactive') }}</option>
                </select>
            </div>

            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo me-1"></i> {{ __('general.pages.users.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="$type != null ? ucfirst($type) : __('general.pages.users.users')" icon="fa-users">
        <x-slot:actions>
            @adminCan($_name . '.export')
                <button class="btn btn-outline-success" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.users.export') }}
                </button>
            @endadminCan
            @adminCan($_name . '.create')
                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editUserModal" wire:click="$dispatch('user-set-current', {id : null , type: '{{ $type }}' })">
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
                @if(in_array($type, ['customer','supplier']))
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
                @if(in_array($type, ['customer','supplier']))
                    @php($totalDue = (float)($dueTotals[$user->id] ?? 0))
                    <td>
                        <span class="badge bg-{{ $totalDue > 0 ? 'danger' : 'secondary' }}">{{ currencyFormat($totalDue, true) }}</span>
                    </td>
                @endif
                <td>
                    <span class="badge bg-{{ $user->active ? 'success' : 'danger' }}">
                        {{ $user->active ? __('general.pages.users.active') : __('general.pages.users.inactive') }}
                    </span>
                </td>
                <td class="text-nowrap">
                    @if($type == 'customer' && (float)($dueTotals[$user->id] ?? 0) > 0)
                        <a href="{{ route('admin.customers.pay', $user->id) }}" class="btn btn-sm btn-success me-1" title="{{ __('general.pages.users.pay') }}">
                            <i class="fa fa-money-bill"></i>
                        </a>
                    @elseif($type == 'supplier' && (float)($dueTotals[$user->id] ?? 0) > 0)
                        <a href="{{ route('admin.suppliers.pay', $user->id) }}" class="btn btn-sm btn-success me-1" title="{{ __('general.pages.users.pay') }}">
                            <i class="fa fa-money-bill"></i>
                        </a>
                    @endif
                    @adminCan($_name . '.update')
                        <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editUserModal" wire:click="$dispatch('user-set-current', {id : {{ $user->id }}, type: '{{ $type }}' })" title="{{ __('general.pages.users.edit') }}">
                            <i class="fa fa-edit"></i>
                        </button>
                    @endadminCan
                    @adminCan($_name . '.delete')
                        <button class="btn btn-sm btn-danger me-1" wire:click="deleteAlert({{ $user->id }})" title="{{ __('general.pages.users.delete') }}">
                            <i class="fa fa-trash"></i>
                        </button>
                    @endadminCan
                    @adminCan($_name . '.show')
                        <a href="{{ route('admin.users.details', $user->id) }}" class="btn btn-sm btn-info" title="{{ __('general.pages.users.view') }}">
                            <i class="fa fa-eye"></i>
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
                {{ $users->links('pagination::default5') }}
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('styles')
@livewire('admin.users.user-modal',['type'=> $type])
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
