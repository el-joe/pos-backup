<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- Role Details Card -->
            <div class="card shadow-sm mb-4 border-primary">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="card-title mb-0">
                        <i class="fa fa-lock"></i> Role Details
                    </h3>
                </div>

                <div class="card-body">
                    <!-- Role Name -->
                    <div class="mb-4">
                        <label for="roleName" class="form-label fw-semibold">Role Name</label>
                        <input type="text"
                               class="form-control"
                               id="roleName"
                               name="roleName"
                               wire:model.lazy="data.roleName"
                               placeholder="Enter role name">
                    </div>

                    <hr class="my-4">

                    <!-- Permissions List Card -->
                    <div class="card border-0 shadow-sm bg-light mb-4">
                        <div class="card-header bg-secondary">
                            <h4 class="card-title mb-0">
                                <i class="fa fa-list-ul"></i> Permissions List
                            </h4>
                        </div>

                        <div class="card-body">
                            <div class="accordion" id="permissionsAccordion">

                                @foreach ($permissionsList as $key => $list)
                                @php $collapseId = 'collapse_' . $key; @endphp

                                <div class="accordion-item mb-2 border rounded">
                                    <h2 class="accordion-header" id="heading_{{ $key }}">
                                        <button class="accordion-button fw-semibold {{ ($collapses[$key] ?? false) ? '' : 'collapsed' }}"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#{{ $collapseId }}"
                                                aria-expanded="{{ ($collapses[$key] ?? false) ? 'true' : 'false' }}"
                                                aria-controls="{{ $collapseId }}"
                                                wire:click.prevent="toggleCollapse('{{ $key }}')">
                                            {{ __(ucwords(str_replace('_', ' ', $key))) }}
                                        </button>
                                    </h2>

                                    <div id="{{ $collapseId }}"
                                         class="accordion-collapse collapse {{ ($collapses[$key] ?? false) ? 'show' : '' }}"
                                         aria-labelledby="heading_{{ $key }}"
                                         data-bs-parent="#permissionsAccordion">
                                        <div class="accordion-body">
                                            <div class="row">
                                                @foreach ($list as $per)
                                                <div class="col-sm-6 col-md-4 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input"
                                                               type="checkbox"
                                                               wire:click="setPermission('{{ $key }}','{{ $per }}', $event.target.checked)"
                                                               id="{{ $per }}"
                                                               {{ ($permissions["$key.$per"] ?? false) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="{{ $per }}">
                                                            {{ __(ucwords(str_replace(['_', $key], [' ', ''], $per))) }}
                                                        </label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="text-center mt-4">
                        <button class="btn btn-success me-2" wire:click="save">
                            <i class="fa fa-save"></i> Save Role
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                    </div>
                </div>

                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
{{-- <style>
    .card {
        border-radius: 16px;
        border: 1.5px solid #e3e6ed;
    }
    .card-header {
        padding: 16px 24px;
    }
    .accordion-button {
        background-color: #f8f9fa;
        color: #333;
        font-weight: 600;
        border-radius: 8px;
    }
    .accordion-button:not(.collapsed) {
        background-color: #e9ecef;
        color: #0d6efd;
        box-shadow: none;
    }
    .accordion-body {
        background-color: #fff;
        border-top: 1px solid #dee2e6;
    }
    .form-check-label {
        cursor: pointer;
    }
</style> --}}
@endpush
