<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.admins.filters')" icon="fa fa-filter" :expanded="$collapseFilters">
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-4">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.admins.search_label') }}</label>
                <input type="text" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500" placeholder="{{ __('general.pages.admins.search_placeholder') }}" wire:model.blur="filters.search">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.admins.branch') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" name="filters.branch_id">
                    <option value="all">{{ __('general.pages.admins.all') }}</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ ($filters['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.admins.status') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" name="filters.active">
                    <option value="all" {{ ($filters['active']??'') == 'all' ? 'selected' : '' }}>{{ __('general.pages.admins.all') }}</option>
                    <option value="1" {{ ($filters['active']??'') == '1' ? 'selected' : '' }}>{{ __('general.pages.admins.active') }}</option>
                    <option value="0" {{ ($filters['active']??'') == '0' ? 'selected' : '' }}>{{ __('general.pages.admins.inactive') }}</option>
                </select>
            </div>
            <div class="col-span-1 flex items-end justify-start md:col-span-2 lg:col-span-1 lg:justify-end">
                <button class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700" wire:click="resetFilters">
                    <i class="fa fa-undo"></i>
                    {{ __('general.pages.admins.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.admins.admins')" icon="fa fa-user-secret">
        <x-slot:actions>
            @adminCan('user_management.export')
                <button class="inline-flex items-center gap-2 rounded-xl border border-emerald-500 bg-white px-3 py-1.5 text-sm font-medium text-emerald-600 transition-colors hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 dark:focus:ring-offset-slate-900" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel"></i>
                    {{ __('general.pages.admins.export') }}
                </button>
            @endadminCan
            @adminCan('user_management.create')
                <button class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900" data-bs-toggle="modal" data-bs-target="#editAdminModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i>
                    {{ __('general.pages.admins.new_admin') }}
                </button>
            @endadminCan
        </x-slot:actions>

        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400 rtl:text-right">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.admins.id') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.admins.name') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.admins.phone') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.admins.email') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.admins.type') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.admins.branch') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.admins.active') }}</th>
                    <th class="px-5 py-3 text-right font-semibold">{{ __('general.pages.admins.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                @forelse ($admins as $admin)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $admin->id }}</td>
                        <td class="px-5 py-4 text-slate-700 dark:text-slate-200">{{ $admin->name }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $admin->phone }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $admin->email }}</td>
                        <td class="px-5 py-4 capitalize text-slate-600 dark:text-slate-300">{{ $admin->type }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $admin->branch?->name }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $admin->active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' }}">
                                {{ $admin->active ? __('general.pages.admins.active') : __('general.pages.admins.inactive') }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @adminCan('user_management.update')
                                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-600 transition-colors hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-500/10" data-bs-toggle="modal" data-bs-target="#editAdminModal" wire:click="setCurrent({{ $admin->id }})" title="{{ __('general.pages.admins.edit') }}">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                @endadminCan
                                @adminCan('user_management.delete')
                                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-rose-600 transition-colors hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10" wire:click="deleteAlert({{ $admin->id }})" title="{{ __('general.pages.admins.delete') }}">
                                        <i class="fa fa-times text-lg"></i>
                                    </button>
                                @endadminCan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                            No data found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(method_exists($admins, 'hasPages') && $admins->hasPages())
            <x-slot:footer>
                {{ $admins->links('pagination::default5') }}
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>

    <div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content overflow-hidden rounded-[28px] border-0 shadow-2xl dark:bg-slate-900">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-5 dark:border-slate-800 dark:bg-slate-950/70">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h5 class="text-lg font-semibold text-slate-900 dark:text-white" id="editAdminModalLabel">{{ $current?->id ? __('general.pages.admins.edit') : __('general.pages.admins.new_admin') }}</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>

                <div class="modal-body bg-white px-6 py-6 dark:bg-slate-900">
                    <form class="space-y-6">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label for="adminName" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.admins.name') }}</label>
                                <input type="text" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" wire:model="data.name" id="adminName" placeholder="{{ __('general.pages.admins.enter_admin_name') }}">
                            </div>
                            <div>
                                <label for="adminPhone" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.admins.phone') }}</label>
                                <input type="text" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" wire:model="data.phone" id="adminPhone" placeholder="{{ __('general.pages.admins.enter_admin_phone') }}">
                            </div>
                            <div>
                                <label for="adminEmail" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.admins.email') }}</label>
                                <input type="email" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" wire:model="data.email" id="adminEmail" placeholder="{{ __('general.pages.admins.enter_admin_email') }}">
                            </div>
                            <div class="md:col-span-2">
                                <label for="adminPassword" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.admins.password') }}</label>
                                <input type="password" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" wire:model="data.password" id="adminPassword" placeholder="{{ __('general.pages.admins.enter_admin_password') }}">
                            </div>
                            <div>
                                <label for="adminType" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.admins.type') }}</label>
                                <select class="select2 mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" name="data.type" id="adminType">
                                    <option value="">{{ __('general.pages.admins.select_type') }}</option>
                                    <option value="super_admin" {{ ($data['type']??'') == 'super_admin' ? 'selected' : '' }}>{{ __('general.pages.admins.super_admin') }}</option>
                                    <option value="admin" {{ ($data['type']??'') == 'admin' ? 'selected' : '' }}>{{ __('general.pages.admins.admin') }}</option>
                                </select>
                            </div>
                            @if(($data['type']??false) == 'admin')
                                <div>
                                    <label for="adminRole" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.admins.role') }}</label>
                                    <select class="select2 mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" name="data.role_id" id="adminRole">
                                        <option value="">{{ __('general.pages.admins.select_role') }}</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" {{ ($data['role_id']??'') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="md:col-span-2">
                                <label for="branchId" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.admins.branch') }}</label>
                                <select class="select2 mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white" name="data.branch_id" id="branchId">
                                    <option value="">{{ __('general.pages.purchases.select_branch') }}</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ ($data['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                            <input class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500" type="checkbox" wire:model="data.active" id="branchActive">
                            <span>{{ __('general.pages.admins.is_active') }}</span>
                        </label>
                    </form>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 dark:border-slate-800 dark:bg-slate-950/70">
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i>
                        {{ __('general.pages.admins.close') }}
                    </button>
                    <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-brand-600" wire:click="save">
                        <i class="fa fa-save"></i>
                        {{ __('general.pages.admins.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
