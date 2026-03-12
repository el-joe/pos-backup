<div class="flex flex-col gap-6">
    <?php $_name = $type == 'customer' ? 'customers' : ($type == 'supplier' ? 'suppliers' : 'users'); ?>

    <x-tenant-tailwind-gemini.filter-card
        :title="__('general.pages.users.filters')"
        icon="fa fa-filter"
        :expanded="$collapseFilters"
    >

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.users.search_label') }}</label>
                <input type="text" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500"
                    placeholder="{{ __('general.pages.users.search_placeholder') }}"
                    wire:model.blur="filters.search">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.users.status') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" name="filters.active">
                    <option value="all" {{ ($filters['active']??'') == 'all' ? 'selected' : '' }}>{{ __('general.pages.users.all') }}</option>
                    <option value="1" {{ ($filters['active']??'') == '1' ? 'selected' : '' }}>{{ __('general.pages.users.active') }}</option>
                    <option value="0" {{ ($filters['active']??'') == '0' ? 'selected' : '' }}>{{ __('general.pages.users.inactive') }}</option>
                </select>
            </div>

            <div class="flex items-end justify-start lg:justify-end">
                <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.users.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="$type != null ? ucfirst($type) : __('general.pages.users.users')" icon="fa-users">
        <x-slot:actions>
            <div class="flex flex-wrap items-center gap-2">
                @adminCan($_name . '.export')
                    <button class="inline-flex items-center gap-2 rounded-xl border border-emerald-500 bg-white px-3 py-2 text-sm font-medium text-emerald-600 transition-colors hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 dark:focus:ring-offset-slate-900" wire:click="$set('export', 'excel')">
                        <i class="fa fa-file-excel"></i> {{ __('general.pages.users.export') }}
                    </button>
                @endadminCan
                @adminCan($_name . '.create')
                    <a class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900" data-bs-toggle="modal" data-bs-target="#editUserModal" wire:click="$dispatch('user-set-current', {id : null , type: '{{ $type }}' })">
                        <i class="fa fa-plus"></i> {{ __('general.pages.users.new_user') }}
                    </a>
                @endadminCan
            </div>
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
                <th class="text-right text-nowrap">{{ __('general.pages.users.action') }}</th>
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
                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $totalDue > 0 ? 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300' : 'bg-slate-100 text-slate-700 dark:bg-slate-700/60 dark:text-slate-300' }}">{{ currencyFormat($totalDue, true) }}</span>
                    </td>
                @endif
                <td>
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300' }}">
                        {{ $user->active ? __('general.pages.users.active') : __('general.pages.users.inactive') }}
                    </span>
                </td>
                <td class="text-right text-nowrap">
                    @if($type == 'customer' && (float)($dueTotals[$user->id] ?? 0) > 0)
                        <a href="{{ route('admin.customers.pay', $user->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-300 dark:hover:bg-emerald-500/20" title="{{ __('general.pages.users.pay') }}">
                            <i class="fa fa-money-bill"></i>
                        </a>
                    @elseif($type == 'supplier' && (float)($dueTotals[$user->id] ?? 0) > 0)
                        <a href="{{ route('admin.suppliers.pay', $user->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-300 dark:hover:bg-emerald-500/20" title="{{ __('general.pages.users.pay') }}">
                            <i class="fa fa-money-bill"></i>
                        </a>
                    @endif
                    @adminCan($_name . '.update')
                        <button class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition hover:bg-blue-100 dark:bg-blue-500/10 dark:text-blue-300 dark:hover:bg-blue-500/20" data-bs-toggle="modal" data-bs-target="#editUserModal" wire:click="$dispatch('user-set-current', {id : {{ $user->id }}, type: '{{ $type }}' })" title="{{ __('general.pages.users.edit') }}">
                            <i class="fa fa-edit"></i>
                        </button>
                    @endadminCan
                    @adminCan($_name . '.delete')
                        <button class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-300 dark:hover:bg-rose-500/20" wire:click="deleteAlert({{ $user->id }})" title="{{ __('general.pages.users.delete') }}">
                            <i class="fa fa-trash"></i>
                        </button>
                    @endadminCan
                    @adminCan($_name . '.show')
                        <a href="{{ route('admin.users.details', $user->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600 transition hover:bg-sky-100 dark:bg-sky-500/10 dark:text-sky-300 dark:hover:bg-sky-500/20" title="{{ __('general.pages.users.view') }}">
                            <i class="fa fa-eye"></i>
                        </a>
                    @endadminCan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ in_array($type, ['customer', 'supplier']) ? ($type == 'customer' ? 10 : 9) : 8 }}" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.messages.no_data_found') }}</td>
            </tr>
        @endforelse

        @if($users->hasPages())
            <x-slot:footer>
                {{ $users->links() }}
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('styles')
@livewire('admin.users.user-modal',['type'=> $type])
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
