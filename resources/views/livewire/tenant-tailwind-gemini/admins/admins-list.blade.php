<div class="space-y-6">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.admins.filters')" icon="fa fa-filter" :expanded="$collapseFilters">
        <x-slot:actions>
            <button type="button" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600 transition hover:border-brand-300 hover:text-brand-600 dark:border-slate-700 dark:text-slate-300 dark:hover:border-brand-400/50 dark:hover:text-brand-300" wire:click="$toggle('collapseFilters')">
                <i class="fa fa-filter"></i>
                {{ __('general.pages.admins.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div>
                <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.admins.search_label') }}</label>
                <input type="text" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" placeholder="{{ __('general.pages.admins.search_placeholder') }}" wire:model.blur="filters.search">
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.admins.branch') }}</label>
                <select class="select2 mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" name="filters.branch_id">
                    <option value="all">{{ __('general.pages.admins.all') }}</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ ($filters['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.admins.status') }}</label>
                <select class="select2 mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" name="filters.active">
                    <option value="all" {{ ($filters['active']??'') == 'all' ? 'selected' : '' }}>{{ __('general.pages.admins.all') }}</option>
                    <option value="1" {{ ($filters['active']??'') == '1' ? 'selected' : '' }}>{{ __('general.pages.admins.active') }}</option>
                    <option value="0" {{ ($filters['active']??'') == '0' ? 'selected' : '' }}>{{ __('general.pages.admins.inactive') }}</option>
                </select>
            </div>
            <div class="flex items-end justify-start md:justify-end">
                <button class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 dark:hover:bg-slate-900" wire:click="resetFilters">
                    <i class="fa fa-undo"></i>
                    {{ __('general.pages.admins.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.admins.admins')" description="Manage administrative users, branch assignment, and access level from one screen." icon="fa fa-user-secret">
        <x-slot:actions>
            @adminCan('user_management.export')
                <button class="inline-flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 transition hover:border-emerald-300 hover:bg-emerald-100 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel"></i>
                    {{ __('general.pages.admins.export') }}
                </button>
            @endadminCan
            @adminCan('user_management.create')
                <button class="inline-flex items-center gap-2 rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500" data-bs-toggle="modal" data-bs-target="#editAdminModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i>
                    {{ __('general.pages.admins.new_admin') }}
                </button>
            @endadminCan
        </x-slot:actions>

        <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800 rtl:text-right">
            <thead class="bg-slate-50/80 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/50 dark:text-slate-400">
                <tr>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.admins.id') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.admins.name') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.admins.phone') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.admins.email') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.admins.type') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.admins.branch') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.admins.active') }}</th>
                    <th class="px-5 py-4 text-center font-semibold">{{ __('general.pages.admins.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse ($admins as $admin)
                    <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">#{{ $admin->id }}</td>
                        <td class="px-5 py-4 text-slate-700 dark:text-slate-200">{{ $admin->name }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $admin->phone }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $admin->email }}</td>
                        <td class="px-5 py-4 capitalize text-slate-600 dark:text-slate-300">{{ $admin->type }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $admin->branch?->name }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $admin->active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300' }}">
                                {{ $admin->active ? __('general.pages.admins.active') : __('general.pages.admins.inactive') }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex justify-center gap-2">
                                @adminCan('user_management.update')
                                    <button class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 text-slate-600 transition hover:border-brand-300 hover:text-brand-600 dark:border-slate-700 dark:text-slate-300 dark:hover:border-brand-400/50 dark:hover:text-brand-300" data-bs-toggle="modal" data-bs-target="#editAdminModal" wire:click="setCurrent({{ $admin->id }})" title="{{ __('general.pages.admins.edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                @endadminCan
                                @adminCan('user_management.delete')
                                    <button class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-rose-200 text-rose-600 transition hover:bg-rose-50 dark:border-rose-500/30 dark:text-rose-300 dark:hover:bg-rose-500/10" wire:click="deleteAlert({{ $admin->id }})" title="{{ __('general.pages.admins.delete') }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                @endadminCan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center">
                            <div class="mx-auto max-w-sm space-y-2">
                                <div class="text-base font-semibold text-slate-900 dark:text-white">No administrators found.</div>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Create an admin user to grant operational or supervisory access.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(method_exists($admins, 'hasPages') && $admins->hasPages())
            <x-slot:footer>
                <div class="flex justify-center">
                    {{ $admins->links('pagination::default5') }}
                </div>
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
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Update credentials, assignment, and access level.</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>

                <div class="modal-body bg-white px-6 py-6 dark:bg-slate-900">
                    <form class="space-y-6">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label for="adminName" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.admins.name') }}</label>
                                <input type="text" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model="data.name" id="adminName" placeholder="{{ __('general.pages.admins.enter_admin_name') }}">
                            </div>
                            <div>
                                <label for="adminPhone" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.admins.phone') }}</label>
                                <input type="text" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model="data.phone" id="adminPhone" placeholder="{{ __('general.pages.admins.enter_admin_phone') }}">
                            </div>
                            <div>
                                <label for="adminEmail" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.admins.email') }}</label>
                                <input type="email" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model="data.email" id="adminEmail" placeholder="{{ __('general.pages.admins.enter_admin_email') }}">
                            </div>
                            <div class="md:col-span-2">
                                <label for="adminPassword" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.admins.password') }}</label>
                                <input type="password" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model="data.password" id="adminPassword" placeholder="{{ __('general.pages.admins.enter_admin_password') }}">
                            </div>
                            <div>
                                <label for="adminType" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.admins.type') }}</label>
                                <select class="select2 mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" name="data.type" id="adminType">
                                    <option value="">{{ __('general.pages.admins.select_type') }}</option>
                                    <option value="super_admin" {{ ($data['type']??'') == 'super_admin' ? 'selected' : '' }}>{{ __('general.pages.admins.super_admin') }}</option>
                                    <option value="admin" {{ ($data['type']??'') == 'admin' ? 'selected' : '' }}>{{ __('general.pages.admins.admin') }}</option>
                                </select>
                            </div>
                            @if(($data['type']??false) == 'admin')
                                <div>
                                    <label for="adminRole" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.admins.role') }}</label>
                                    <select class="select2 mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" name="data.role_id" id="adminRole">
                                        <option value="">{{ __('general.pages.admins.select_role') }}</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" {{ ($data['role_id']??'') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="md:col-span-2">
                                <label for="branchId" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.admins.branch') }}</label>
                                <select class="select2 mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" name="data.branch_id" id="branchId">
                                    <option value="">{{ __('general.pages.purchases.select_branch') }}</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ ($data['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                            <input class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500" type="checkbox" wire:model="data.active" id="branchActive">
                            <span>{{ __('general.pages.admins.is_active') }}</span>
                        </label>
                    </form>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 dark:border-slate-800 dark:bg-slate-950/70">
                    <button type="button" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 dark:hover:bg-slate-900" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i>
                        {{ __('general.pages.admins.close') }}
                    </button>
                    <button type="button" class="inline-flex items-center gap-2 rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500" wire:click="save">
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
