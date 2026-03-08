<div class="space-y-6">
    <section class="rounded-[28px] border border-slate-200 bg-white px-6 py-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 lg:px-8">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl space-y-2">
                <span class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-brand-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-brand-700 dark:border-brand-500/20 dark:bg-brand-500/10 dark:text-brand-300">
                    <i class="fa fa-user-shield"></i>
                    Access Control
                </span>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ __('general.pages.roles.roles') }}</h1>
                <p class="text-sm text-slate-600 dark:text-slate-300">Review roles, member counts, and activation status before assigning or editing administrative permissions.</p>
            </div>

            @adminCan('role_management.create')
                <a class="inline-flex items-center gap-2 rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500" href="{{ route('admin.roles.show','create') }}">
                    <i class="fa fa-plus"></i>
                    {{ __('general.pages.roles.new_role') }}
                </a>
            @endadminCan
        </div>
    </section>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.roles.roles')" description="Every role below controls access to operational and administrative modules." icon="fa fa-id-badge">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800 rtl:text-right">
            <thead class="bg-slate-50/80 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/50 dark:text-slate-400">
                <tr>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.roles.id') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.roles.role') }}</th>
                    <th class="px-5 py-4 text-center font-semibold">{{ __('general.pages.roles.members') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.roles.created_at') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.roles.status') }}</th>
                    <th class="px-5 py-4 text-center font-semibold">{{ __('general.pages.roles.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse ($roles as $role)
                    <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $loop->iteration }}</td>
                        <td class="px-5 py-4 text-slate-700 dark:text-slate-200">{{ $role->name ?? $role->ar_name }}</td>
                        <td class="px-5 py-4 text-center text-slate-600 dark:text-slate-300">{{ $role->users_count ?? 0 }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $role->created_at->format('D, d M Y - h:i A') }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $role->active == 1 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300' }}">
                                {{ $role->active == 1 ? __('general.pages.roles.active') : __('general.pages.roles.inactive') }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex justify-center gap-2">
                                @adminCan('role_management.update')
                                    <a href="{{ route('admin.roles.show', $role->id) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 text-slate-600 transition hover:border-brand-300 hover:text-brand-600 dark:border-slate-700 dark:text-slate-300 dark:hover:border-brand-400/50 dark:hover:text-brand-300" title="{{ __('general.pages.admins.edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endadminCan
                                @adminCan('role_management.delete')
                                    <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-rose-200 text-rose-600 transition hover:bg-rose-50 dark:border-rose-500/30 dark:text-rose-300 dark:hover:bg-rose-500/10" wire:click="deleteAlert({{ $role->id }})" title="{{ __('general.pages.admins.delete') }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                @endadminCan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center">
                            <div class="mx-auto max-w-sm space-y-2">
                                <div class="text-base font-semibold text-slate-900 dark:text-white">No roles found.</div>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Create a role to start segmenting access across your admin team.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-tenant-tailwind-gemini.table-card>
</div>
