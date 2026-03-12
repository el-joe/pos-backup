<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.roles.roles')" icon="fa fa-id-badge">
        <x-slot:actions>
            @adminCan('role_management.create')
                <a class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900" href="{{ route('admin.roles.show','create') }}">
                    <i class="fa fa-plus"></i>
                    {{ __('general.pages.roles.new_role') }}
                </a>
            @endadminCan
        </x-slot:actions>

        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400 rtl:text-right">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.roles.id') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.roles.role') }}</th>
                    <th class="px-5 py-3 text-center font-semibold">{{ __('general.pages.roles.members') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.roles.created_at') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.roles.status') }}</th>
                    <th class="px-5 py-3 text-right font-semibold">{{ __('general.pages.roles.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                @forelse ($roles as $role)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $loop->iteration }}</td>
                        <td class="px-5 py-4 text-slate-700 dark:text-slate-200">{{ $role->name ?? $role->ar_name }}</td>
                        <td class="px-5 py-4 text-center text-slate-600 dark:text-slate-300">{{ $role->users_count ?? 0 }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $role->created_at->format('D, d M Y - h:i A') }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $role->active == 1 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' }}">
                                {{ $role->active == 1 ? __('general.pages.roles.active') : __('general.pages.roles.inactive') }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @adminCan('role_management.update')
                                    <a href="{{ route('admin.roles.show', $role->id) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-600 transition-colors hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-500/10" title="{{ __('general.pages.admins.edit') }}">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                @endadminCan
                                @adminCan('role_management.delete')
                                    <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-rose-600 transition-colors hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10" wire:click="deleteAlert({{ $role->id }})" title="{{ __('general.pages.admins.delete') }}">
                                        <i class="fa fa-times text-lg"></i>
                                    </button>
                                @endadminCan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                            No data found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-tenant-tailwind-gemini.table-card>
</div>
