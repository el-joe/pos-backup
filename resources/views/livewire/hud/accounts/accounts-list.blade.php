<div class="col-12">
    <x-hud.table-card :title="__('general.titles.accounts')" icon="fa-book">
        <x-slot:actions>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editAccountModal" wire:click="setCurrent(null)">
                <i class="fa fa-plus me-1"></i> {{ __('general.pages.accounts.new_account') }}
            </button>
        </x-slot:actions>

        <x-slot:head>
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
        </x-slot:head>

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

        <x-slot:footer>
            {{ $accounts->links('pagination::default5') }}
        </x-slot:footer>
    </x-hud.table-card>

    @if(!$subPage)
        @livewire('admin.accounts.add-edit-modal',[
            'current' => $current,
            'filters' => $filters,
            'subPage' => $subPage
        ])
    @endif
</div>
