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
                ? '<span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25">' . e($T('statuses.active')) . '</span>'
                : '<span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">' . e($T('statuses.inactive')) . '</span>',
            'date' => $val instanceof \Carbon\CarbonInterface ? $val->format('Y-m-d') : e($val),
            'datetime' => $val instanceof \Carbon\CarbonInterface ? $val->format('Y-m-d H:i') : e($val),
            'decimal' => number_format((float) $val, 2),
            default => e((string) $val),
        };
    };
@endphp

<div class="d-flex flex-column gap-3">

    {{-- Filters --}}
    <x-hud.collapsed-card :title="__($titleKey)" :icon="$icon" :expanded="$collapseFilters">
        <div class="row g-3">
            <div class="col-md-9">
                <x-hud.form-group :label="$T('search')">
                    <x-hud.search-input
                        :placeholder="$T('search_placeholder')"
                        wire:model.live.debounce.400ms="filters.search" />
                </x-hud.form-group>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <x-hud.button variant="secondary" outline icon="fa fa-undo" wire:click="resetFilters" class="w-100">
                    {{ $T('reset') }}
                </x-hud.button>
            </div>
        </div>
    </x-hud.collapsed-card>

    {{-- Records --}}
    <x-hud.card :title="__($titleKey)" :icon="$icon" :padded="false">
        <x-slot:actions>
            @adminCan($permission . '.create')
                <x-hud.button variant="primary" icon="fa fa-plus" size="sm" wire:click="create">
                    {{ $T('create') }}
                </x-hud.button>
            @endadminCan
        </x-slot:actions>

        <x-hud.table>
            <x-slot:head>
                <tr>
                    <th>#</th>
                    @foreach($columnsMeta as $col)
                        <th>{{ $label($col['label_key']) }}</th>
                    @endforeach
                    <th class="text-end">{{ $T('actions') }}</th>
                </tr>
            </x-slot:head>

            @forelse($records as $row)
                <tr>
                    <x-hud.td>{{ $row->id }}</x-hud.td>
                    @foreach($columnsMeta as $col)
                        <x-hud.td>{!! $formatValue($row, $col) !!}</x-hud.td>
                    @endforeach
                    <x-hud.td align="end">
                        <x-hud.btn-group align="end">
                            @foreach(($statusActions ?? []) as $sa)
                                @php
                                    $permSuffix = $sa['permission_suffix'] ?? $sa['action'];
                                    $from = $sa['from'] ?? [];
                                    $canShow = empty($from) || (isset($row->status) && in_array($row->status, $from, true));
                                    // map gemini tones to bootstrap tones
                                    $toneMap = ['emerald'=>'success','rose'=>'danger','amber'=>'warning','sky'=>'info','blue'=>'primary','violet'=>'primary','brand'=>'primary','slate'=>'secondary'];
                                    $tone = $toneMap[$sa['class'] ?? 'emerald'] ?? 'success';
                                @endphp
                                @if($canShow)
                                    @adminCan($permission . '.' . $permSuffix)
                                        <x-hud.icon-btn
                                            :icon="$sa['icon'] ?? 'fa fa-check'"
                                            :tone="$tone" size="sm"
                                            :title="__('general.pages.contracting.' . ($sa['label_key'] ?? $sa['action']))"
                                            wire:click="runStatusAction('{{ $sa['action'] }}', {{ $row->id }})" />
                                    @endadminCan
                                @endif
                            @endforeach
                            @adminCan($permission . '.update')
                                <x-hud.icon-btn icon="fa fa-pencil" tone="primary" size="sm"
                                    :title="$T('edit')" wire:click="edit({{ $row->id }})" />
                            @endadminCan
                            @adminCan($permission . '.delete')
                                <x-hud.icon-btn icon="fa fa-times" tone="danger" size="sm"
                                    :title="$T('delete')" wire:click="deleteAlert({{ $row->id }})" />
                            @endadminCan
                        </x-hud.btn-group>
                    </x-hud.td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columnsMeta) + 2 }}">
                        <x-hud.empty-state :title="$T('no_records')" icon="fa fa-inbox" />
                    </td>
                </tr>
            @endforelse
        </x-hud.table>

        @if($records->hasPages())
            <x-slot:footer>
                <x-hud.pagination :paginator="$records" />
            </x-slot:footer>
        @endif
    </x-hud.card>

    {{-- Form modal (Bootstrap modal; Livewire opens/closes via dispatched JS events) --}}
    @php $modalId = 'contractingFormModal'; @endphp
    <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <i class="{{ $icon }}"></i>
                        <span>{{ __($titleKey) }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="row g-3">
                            @foreach($fieldsMeta as $field)
                                @php
                                    $f = $field['field'];
                                    $type = $field['type'] ?? 'text';
                                    $colClass = ($field['full'] ?? false) ? 'col-12' : 'col-md-6';
                                    $fieldError = $errors->first('form.' . $f);
                                @endphp
                                <div class="{{ $colClass }}">
                                    @if($type === 'boolean')
                                        <x-hud.checkbox
                                            :label="$label($field['label_key'])"
                                            wire:model.blur="form.{{ $f }}" />
                                        @if($fieldError)
                                            <div class="invalid-feedback d-block">{{ $fieldError }}</div>
                                        @endif
                                    @else
                                        <x-hud.form-group
                                            :label="$label($field['label_key'])"
                                            :required="!empty($field['required'])"
                                            :error="$fieldError">

                                            @if($type === 'textarea')
                                                <x-hud.textarea :invalid="(bool) $fieldError"
                                                    wire:model.blur="form.{{ $f }}" />
                                            @elseif($type === 'select')
                                                <x-hud.select :invalid="(bool) $fieldError"
                                                    wire:model.blur="form.{{ $f }}"
                                                    :placeholder="'— ' . $T('select') . ' —'">
                                                    @foreach(($field['options'] ?? []) as $k => $v)
                                                        <option value="{{ $k }}">{{ is_string($v) && str_starts_with($v, 'general.') ? __($v) : $v }}</option>
                                                    @endforeach
                                                </x-hud.select>
                                            @elseif($type === 'belongs_to')
                                                <x-hud.select :invalid="(bool) $fieldError"
                                                    wire:model.blur="form.{{ $f }}"
                                                    :placeholder="'— ' . $T('select') . ' —'">
                                                    @foreach(($selectOptions[$f] ?? []) as $id => $display)
                                                        <option value="{{ $id }}">{{ $display }}</option>
                                                    @endforeach
                                                </x-hud.select>
                                            @elseif($type === 'date')
                                                <x-hud.input type="date" :invalid="(bool) $fieldError"
                                                    wire:model.blur="form.{{ $f }}" />
                                            @elseif($type === 'decimal' || $type === 'number')
                                                <x-hud.input type="number" :invalid="(bool) $fieldError"
                                                    step="{{ $type === 'decimal' ? '0.01' : '1' }}"
                                                    wire:model.blur="form.{{ $f }}" />
                                            @else
                                                <x-hud.input :type="$type === 'email' ? 'email' : 'text'"
                                                    :invalid="(bool) $fieldError"
                                                    wire:model.blur="form.{{ $f }}" />
                                            @endif
                                        </x-hud.form-group>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <x-hud.button variant="secondary" data-bs-dismiss="modal">{{ $T('cancel') }}</x-hud.button>
                        <x-hud.button type="submit" icon="fa fa-save">{{ $T('save') }}</x-hud.button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function () {
            const id = @json($modalId);
            window.addEventListener('contracting-open-modal', () => {
                const el = document.getElementById(id);
                if (el && window.bootstrap) bootstrap.Modal.getOrCreateInstance(el).show();
            });
            window.addEventListener('contracting-close-modal', () => {
                const el = document.getElementById(id);
                if (el && window.bootstrap) bootstrap.Modal.getOrCreateInstance(el).hide();
            });
        })();
    </script>
    @endpush
</div>
