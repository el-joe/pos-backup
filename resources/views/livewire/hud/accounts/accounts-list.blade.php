<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.titles.accounts') }}</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editAccountModal" wire:click="setCurrent(null)">
                <i class="fa fa-plus me-1"></i> {{ __('general.pages.accounts.new_account') }}
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.pages.accounts.id') }}</th>
                            <th>{{ __('general.pages.accounts.name') }}</th>
                            <th>{{ __('general.pages.accounts.code') }}</th>
                            <th>{{ __('general.pages.accounts.type') }}</th>
                            <th>{{ __('general.pages.accounts.branch') }}</th>
                            <th>{{ __('general.pages.accounts.payment_method') }}</th>
                            <th>{{ __('general.pages.accounts.status') }}</th>
                            <th class="text-center">{{ __('general.pages.accounts.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $account)
                        <tr>
                            <td>{{ $account->id }}</td>
                            <td>{{ $account->name }}</td>
                            <td>{{ $account->code }}</td>
                            <td>
                                <span class="badge bg-{{ $account->type->color() }}">{{ $account->type->label() }}</span>
                            </td>
                            <td>{{ $account->branch?->name ?? __('general.pages.accounts.all') }}</td>
                            <td>{{ $account->paymentMethod?->name ?? __('general.pages.accounts.placeholder_line') }}</td>
                            <td>
                                <span class="badge bg-{{ $account->active ? 'success' : 'danger' }}">
                                    {{ $account->active ? __('general.pages.accounts.active') : __('general.pages.accounts.inactive') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editAccountModal" wire:click="setCurrent({{ $account->id }})" title="{{ __('general.pages.accounts.edit') }}">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" wire:click="deleteAlert({{ $account->id }})" title="{{ __('general.pages.accounts.delete') }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $accounts->links() }}
                </div>
            </div>
        </div>

        <!-- Card Arrow -->
        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>
    @if(!$subPage)
        @livewire('admin.accounts.add-edit-modal',[
            'current' => $current,
            'filters' => $filters,
            'subPage' => $subPage
        ])
    @endif
</div>
