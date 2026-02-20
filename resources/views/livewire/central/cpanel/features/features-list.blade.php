<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.cpanel.features.list_title') }}</h5>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editFeatureModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> {{ __('general.cpanel.features.add') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('general.cpanel.features.code') }}</th>
                            <th>{{ __('general.cpanel.features.module') }}</th>
                            <th>{{ __('general.cpanel.features.name_en') }}</th>
                            <th>{{ __('general.cpanel.features.name_ar') }}</th>
                            <th>{{ __('general.cpanel.features.type') }}</th>
                            <th>{{ __('general.cpanel.features.active') }}</th>
                            <th class="text-center">{{ __('general.cpanel.features.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($features as $feature)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $feature->code }}</td>
                                <td>{{ strtoupper($feature->module_name) }}</td>
                                <td>{{ $feature->name_en }}</td>
                                <td>{{ $feature->name_ar }}</td>
                                <td>
                                    <span class="badge bg-info">{{ __('general.cpanel.features.types.' . $feature->type) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $feature->active ? 'success' : 'danger' }}">
                                        {{ $feature->active ? __('general.cpanel.features.active') : __('general.cpanel.features.inactive') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editFeatureModal" wire:click="setCurrent({{ $feature->id }})" title="{{ __('general.cpanel.features.edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="deleteAlert({{ $feature->id }})" title="{{ __('general.cpanel.features.delete') }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $features->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>

    <!-- Edit / Create Feature Modal -->
    <div class="modal fade" id="editFeatureModal" tabindex="-1" aria-labelledby="editFeatureModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-sm">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFeatureModalLabel">{{ $current?->id ? __('general.cpanel.features.edit') : __('general.cpanel.features.add_new') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">{{ __('general.cpanel.features.code') }}</label>
                            <input type="text" class="form-control" wire:model="data.code" placeholder="{{ __('general.cpanel.features.placeholders.code') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('general.cpanel.features.module') }}</label>
                            <select class="form-select" wire:model="data.module_name">
                                @foreach ($modules as $module)
                                    <option value="{{ $module->value }}">{{ strtoupper($module->value) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('general.cpanel.features.name_en') }}</label>
                            <input type="text" class="form-control" wire:model="data.name_en" placeholder="{{ __('general.cpanel.features.placeholders.name_en') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('general.cpanel.features.name_ar') }}</label>
                            <input type="text" class="form-control" wire:model="data.name_ar" placeholder="{{ __('general.cpanel.features.placeholders.name_ar') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('general.cpanel.features.type') }}</label>
                            <select class="form-select" wire:model="data.type">
                                <option value="boolean">{{ __('general.cpanel.features.types.boolean') }}</option>
                                <option value="text">{{ __('general.cpanel.features.types.text') }}</option>
                            </select>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" wire:model="data.active" id="featureActive">
                            <label class="form-check-label" for="featureActive">{{ __('general.cpanel.features.is_active') }}</label>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> {{ __('general.cpanel.features.close') }}
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="save">
                        <i class="fa fa-save"></i> {{ __('general.cpanel.features.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
