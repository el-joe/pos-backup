<div class="col-12">
    <x-hud.filter-card
        :title="__('general.pages.branches.filters')"
        icon="fa fa-filter"
        collapse-id="branchFilterCollapse"
        :collapsed="$collapseFilters"
    >
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-toggle="collapse"
                    data-bs-target="#branchFilterCollapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.branches.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.branches.search') }}</label>
                <input type="text"
                       class="form-control"
                       placeholder="{{ __('general.pages.branches.search') }} ..."
                       wire:model.live="filters.search">
            </div>

            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.branches.status') }}</label>
                <select class="form-select" wire:model.live="filters.active">
                    <option value="">{{ __('general.pages.branches.all') }}</option>
                    <option value="1">{{ __('general.pages.branches.active') }}</option>
                    <option value="0">{{ __('general.pages.branches.inactive') }}</option>
                </select>
            </div>

            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo me-1"></i> {{ __('general.pages.branches.reset') }}
                </button>
            </div>
        </div>
    </x-hud.filter-card>

    <x-hud.table-card :title="__('general.pages.branches.branches')" icon="fa fa-code-branch">
        <x-slot:actions>
            <div class="d-flex align-items-center gap-2">
                @adminCan('branches.export')
                    <button class="btn btn-outline-success"
                            wire:click="$set('export', 'excel')">
                        <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.branches.export') }}
                    </button>
                @endadminCan
                @adminCan('branches.create')
                <button class="btn btn-theme"
                        data-bs-toggle="modal"
                        data-bs-target="#editBranchModal"
                        wire:click="$dispatch('branch-set-current', null)">
                    <i class="fa fa-plus me-1"></i> {{ __('general.pages.branches.new_branch') }}
                </button>
                @endadminCan
            </div>
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
                <th class="text-nowrap text-end">{{ __('general.pages.branches.action') }}</th>
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
                    <span class="badge bg-{{ $branch->active ? 'success' : 'danger' }}">
                        {{ $branch->active ? __('general.pages.branches.active') : __('general.pages.branches.inactive') }}
                    </span>
                </td>
                <td class="text-end text-nowrap">
                    @adminCan('branches.update')
                    <button class="btn btn-sm btn-outline-primary me-1"
                            data-bs-toggle="modal"
                            data-bs-target="#editBranchModal"
                            wire:click="$dispatch('branch-set-current', { id: {{ $branch->id }} })"
                            title="{{ __('general.pages.branches.edit') }}">
                        <i class="fa fa-pencil"></i>
                    </button>
                    @endadminCan
                    @adminCan('branches.delete')
                    <button class="btn btn-sm btn-outline-danger"
                            wire:click="deleteAlert({{ $branch->id }})"
                            title="{{ __('general.pages.branches.delete') }}">
                        <i class="fa fa-times"></i>
                    </button>
                    @endadminCan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted py-4">No data found.</td>
            </tr>
        @endforelse

        @if($branches->hasPages())
            <x-slot:footer>
                {{ $branches->links('pagination::default5') }}
            </x-slot:footer>
        @endif
    </x-hud.table-card>

</div>

@push('scripts')
    @livewire('admin.branches.branch-modal')
@endpush
