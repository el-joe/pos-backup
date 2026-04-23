@php
    /** @var \Illuminate\Pagination\LengthAwarePaginator $records */
    /** @var array $columnsMeta */
    /** @var array $fieldsMeta */
    /** @var array $selectOptions */
    $T = fn($k) => __('general.pages.contracting.' . $k);
    $label = fn($key) => __('general.pages.contracting.fields.' . $key);

    $formatValue = function ($row, $col) use ($T) {
        $val = data_get($row, $col['field']);
        $type = $col['type'] ?? 'text';
        if (is_null($val) || $val === '')
            return '—';
        return match ($type) {
            'boolean' => $val
                ? '<span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">' . e($T('statuses.active')) . '</span>'
                : '<span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-medium text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">' . e($T('statuses.inactive')) . '</span>',
            'date' => $val instanceof \Carbon\CarbonInterface ? $val->format('Y-m-d') : e($val),
            'datetime' => $val instanceof \Carbon\CarbonInterface ? $val->format('Y-m-d H:i') : e($val),
            'decimal' => number_format((float) $val, 2),
            default => e((string) $val),
        };
    };
@endphp

<div class="flex flex-col gap-6">

    {{-- Filters --}}
    <x-tenant-tailwind-gemini.collapsed-card :title="__($titleKey)" :icon="$icon" :expanded="$collapseFilters">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <x-tenant-tailwind-gemini.form-group :label="$T('search')" class="md:col-span-3">
                <x-tenant-tailwind-gemini.search-input
                    :placeholder="$T('search_placeholder')"
                    wire:model.live.debounce.400ms="filters.search" />
            </x-tenant-tailwind-gemini.form-group>
            <div class="flex items-end justify-start md:justify-end">
                <x-tenant-tailwind-gemini.button variant="secondary" icon="fa fa-undo" wire:click="resetFilters">
                    {{ $T('reset') }}
                </x-tenant-tailwind-gemini.button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.collapsed-card>

    {{-- Records --}}
    <x-tenant-tailwind-gemini.card :title="__($titleKey)" :icon="$icon" :padded="false">
        <x-slot:actions>
            @adminCan($permission . '.create')
                <x-tenant-tailwind-gemini.button icon="fa fa-plus" size="sm" wire:click="create">
                    {{ $T('create') }}
                </x-tenant-tailwind-gemini.button>
            @endadminCan
        </x-slot:actions>

        <x-tenant-tailwind-gemini.table>
            <x-slot:head>
                <tr>
                    <th class="px-5 py-3 font-semibold">#</th>
                    @foreach($columnsMeta as $col)
                        <th class="px-5 py-3 font-semibold">{{ $label($col['label_key']) }}</th>
                    @endforeach
                    <th class="px-5 py-3 text-right font-semibold">{{ $T('actions') }}</th>
                </tr>
            </x-slot:head>

            @forelse($records as $row)
                <tr>
                    <x-tenant-tailwind-gemini.td>{{ $row->id }}</x-tenant-tailwind-gemini.td>
                    @foreach($columnsMeta as $col)
                        <x-tenant-tailwind-gemini.td>{!! $formatValue($row, $col) !!}</x-tenant-tailwind-gemini.td>
                    @endforeach
                    <x-tenant-tailwind-gemini.td align="end">
                        <x-tenant-tailwind-gemini.btn-group align="end">
                            @foreach(($statusActions ?? []) as $sa)
                                @php
                                    $permSuffix = $sa['permission_suffix'] ?? $sa['action'];
                                    $from = $sa['from'] ?? [];
                                    $canShow = empty($from) || (isset($row->status) && in_array($row->status, $from, true));
                                    $tone = $sa['class'] ?? 'emerald';
                                @endphp
                                @if($canShow)
                                    @adminCan($permission . '.' . $permSuffix)
                                        <x-tenant-tailwind-gemini.icon-btn
                                            :icon="$sa['icon'] ?? 'fa fa-check'"
                                            :tone="$tone"
                                            size="sm"
                                            :title="__('general.pages.contracting.' . ($sa['label_key'] ?? $sa['action']))"
                                            wire:click="runStatusAction('{{ $sa['action'] }}', {{ $row->id }})" />
                                    @endadminCan
                                @endif
                            @endforeach
                            @adminCan($permission . '.update')
                                <x-tenant-tailwind-gemini.icon-btn
                                    icon="fa fa-pencil" tone="blue" size="sm"
                                    :title="$T('edit')"
                                    wire:click="edit({{ $row->id }})" />
                            @endadminCan
                            @adminCan($permission . '.delete')
                                <x-tenant-tailwind-gemini.icon-btn
                                    icon="fa fa-times" tone="rose" size="sm"
                                    :title="$T('delete')"
                                    wire:click="deleteAlert({{ $row->id }})" />
                            @endadminCan
                        </x-tenant-tailwind-gemini.btn-group>
                    </x-tenant-tailwind-gemini.td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columnsMeta) + 2 }}" class="px-5 py-10">
                        <x-tenant-tailwind-gemini.empty-state :title="$T('no_records')" icon="fa fa-inbox" />
                    </td>
                </tr>
            @endforelse
        </x-tenant-tailwind-gemini.table>

        @if($records->hasPages())
            <x-slot:footer>
                <x-tenant-tailwind-gemini.pagination :paginator="$records" />
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.card>

    {{-- Form modal (event-driven, backed by ContractingCrudComponent dispatches) --}}
    <x-tenant-tailwind-gemini.modal
        :title="__($titleKey)" :icon="$icon" size="lg"
        openEvent="contracting-open-modal"
        closeEvent="contracting-close-modal">

        <form wire:submit.prevent="save" class="-mx-6 -my-5">
            <div class="max-h-[65vh] overflow-y-auto px-6 py-5">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    @foreach($fieldsMeta as $field)
                        @php
                            $f = $field['field'];
                            $type = $field['type'] ?? 'text';
                            $colSpan = ($field['full'] ?? false) ? 'md:col-span-2' : '';
                            $fieldError = $errors->first('form.' . $f);
                        @endphp
                        <div class="{{ $colSpan }}">
                            @if($type === 'boolean')
                                <x-tenant-tailwind-gemini.checkbox
                                    :label="$label($field['label_key'])"
                                    wire:model.blur="form.{{ $f }}" />
                                @if($fieldError)
                                    <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $fieldError }}</p>
                                @endif
                            @else
                                <x-tenant-tailwind-gemini.form-group
                                    :label="$label($field['label_key'])"
                                    :required="!empty($field['required'])"
                                    :error="$fieldError">

                                    @if($type === 'textarea')
                                        <x-tenant-tailwind-gemini.textarea
                                            :invalid="(bool) $fieldError"
                                            wire:model.blur="form.{{ $f }}" />
                                    @elseif($type === 'select')
                                        <x-tenant-tailwind-gemini.select
                                            :invalid="(bool) $fieldError"
                                            wire:model.blur="form.{{ $f }}"
                                            :placeholder="'— ' . $T('select') . ' —'">
                                            @foreach(($field['options'] ?? []) as $k => $v)
                                                <option value="{{ $k }}">{{ is_string($v) && str_starts_with($v, 'general.') ? __($v) : $v }}</option>
                                            @endforeach
                                        </x-tenant-tailwind-gemini.select>
                                    @elseif($type === 'belongs_to')
                                        <x-tenant-tailwind-gemini.select
                                            :invalid="(bool) $fieldError"
                                            wire:model.blur="form.{{ $f }}"
                                            :placeholder="'— ' . $T('select') . ' —'">
                                            @foreach(($selectOptions[$f] ?? []) as $id => $display)
                                                <option value="{{ $id }}">{{ $display }}</option>
                                            @endforeach
                                        </x-tenant-tailwind-gemini.select>
                                    @elseif($type === 'date')
                                        <x-tenant-tailwind-gemini.input type="date"
                                            :invalid="(bool) $fieldError"
                                            wire:model.blur="form.{{ $f }}" />
                                    @elseif($type === 'decimal' || $type === 'number')
                                        <x-tenant-tailwind-gemini.input type="number"
                                            :invalid="(bool) $fieldError"
                                            step="{{ $type === 'decimal' ? '0.01' : '1' }}"
                                            wire:model.blur="form.{{ $f }}" />
                                    @else
                                        <x-tenant-tailwind-gemini.input
                                            :type="$type === 'email' ? 'email' : 'text'"
                                            :invalid="(bool) $fieldError"
                                            wire:model.blur="form.{{ $f }}" />
                                    @endif
                                </x-tenant-tailwind-gemini.form-group>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </form>

        <x-slot:footer>
            <x-tenant-tailwind-gemini.button variant="secondary" @click="open = false">
                {{ $T('cancel') }}
            </x-tenant-tailwind-gemini.button>
            <x-tenant-tailwind-gemini.button icon="fa fa-save" wire:click="save">
                {{ $T('save') }}
            </x-tenant-tailwind-gemini.button>
        </x-slot:footer>
    </x-tenant-tailwind-gemini.modal>
</div>
@php
    /** @var \Illuminate\Pagination\LengthAwarePaginator $records */
    /** @var array $columnsMeta */
    /** @var array $fieldsMeta */
    /** @var array $selectOptions */
    $dataGet = fn($row, $path) => data_get($row, $path);
    $formatValue = function ($row, $col) {
        $val = data_get($row, $col['field']);
        $type = $col['type'] ?? 'text';
        if (is_null($val) || $val === '')
            return '—';
        return match ($type) {
            'boolean' => $val ? '<span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">' . __('general.pages.contracting.statuses.active') . '</span>'
            : '<span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-medium text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">' . __('general.pages.contracting.statuses.inactive') . '</span>',
            'date' => $val instanceof \Carbon\CarbonInterface ? $val->format('Y-m-d') : e($val),
            'datetime' => $val instanceof \Carbon\CarbonInterface ? $val->format('Y-m-d H:i') : e($val),
            'decimal' => number_format((float) $val, 2),
            default => e((string) $val),
        };
    };
    $label = fn($key) => __('general.pages.contracting.fields.' . $key);
@endphp
