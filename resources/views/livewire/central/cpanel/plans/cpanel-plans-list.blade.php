<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Plans List</h5>
            <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editPlanModal" wire:click="setCurrent(null)">
                <i class="fa fa-layer-group"></i> New Plan
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Price (Month)</th>
                            <th>Price (Year)</th>
                            <th>3 Months Free</th>
                            <th>Active</th>
                            <th>Recommended</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plans as $plan)
                        <tr>
                            <td>{{ $plan->id }}</td>
                            <td>{{ $plan->name }}</td>
                            <td>${{ $plan->price_month }}</td>
                            <td>${{ $plan->price_year }}</td>
                            <td>
                                @if($plan->three_months_free)
                                    <span class="badge bg-success">Enabled</span>
                                @else
                                    <span class="badge bg-secondary">Disabled</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $plan->active ? 'success' : 'danger' }}">
                                    {{ $plan->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $plan->recommended ? 'primary' : 'secondary' }}">
                                    {{ $plan->recommended ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm {{ $plan->active ? 'btn-danger' : 'btn-success' }}"
                                        wire:click="triggerActive({{ $plan->id }})"
                                        title="{{ $plan->active ? 'Deactivate' : 'Activate' }}">
                                    <i class="fa {{ $plan->active ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                </button>
                                <button class="btn btn-sm btn-primary mx-1"
                                        data-bs-toggle="modal" data-bs-target="#editPlanModal"
                                        wire:click="setCurrent({{ $plan->id }})"
                                        title="Edit">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger"
                                        wire:click="deleteAlert({{ $plan->id }})"
                                        title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>

    <!-- Edit/Create Plan Modal -->
<div class="modal fade" id="editPlanModal" tabindex="-1" aria-labelledby="editPlanModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-sm">
            <div class="modal-header">
                <h5 class="modal-title" id="editPlanModalLabel">{{ $current?->id ? 'Edit Plan' : 'New Plan' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Plan Name</label>
                            <input type="text" class="form-control" wire:model="data.name" placeholder="Plan name">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Module</label>
                            <select class="form-select" wire:model.live="data.module_name">
                                @foreach ($modules as $module)
                                    <option value="{{ $module->value }}">{{ strtoupper($module->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Price per Month</label>
                            <input type="number" class="form-control" wire:model="data.price_month" placeholder="Monthly price">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Price per Year</label>
                            <input type="number" class="form-control" wire:model="data.price_year" placeholder="Yearly price">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" wire:model="data.three_months_free" id="planThreeMonthsFree">
                                <label class="form-check-label" for="planThreeMonthsFree">3 months free trial</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-check form-switch my-3">
                        <input class="form-check-input" type="checkbox" wire:model="data.recommended" id="planRecommended">
                        <label class="form-check-label" for="planRecommended">Recommended</label>
                    </div>

                    <div class="accordion" id="featuresAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFeatures">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFeatures" aria-expanded="false" aria-controls="collapseFeatures">
                                    Plan Features
                                </button>
                            </h2>
                            <div id="collapseFeatures" class="accordion-collapse collapse" aria-labelledby="headingFeatures" data-bs-parent="#featuresAccordion">
                                <div class="accordion-body">
                                    @foreach ($features as $feature)
                                        <div class="mb-3">
                                            @php
                                                $label = app()->getLocale() === 'ar' ? $feature->name_ar : $feature->name_en;
                                            @endphp

                                            <label class="form-label">{{ $label }}</label>

                                            @if ($feature->type === 'boolean')
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" wire:model="data.plan_features.{{ $feature->id }}.value" id="feature_{{ $feature->id }}">
                                                    <label class="form-check-label" for="feature_{{ $feature->id }}">Enabled</label>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" wire:model="data.plan_features.{{ $feature->id }}.content_en" placeholder="Content (EN)">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" wire:model="data.plan_features.{{ $feature->id }}.content_ar" placeholder="Content (AR)">
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row g-2">
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control" wire:model="data.plan_features.{{ $feature->id }}.value" placeholder="Value">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" wire:model="data.plan_features.{{ $feature->id }}.content_en" placeholder="Content (EN)">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" wire:model="data.plan_features.{{ $feature->id }}.content_ar" placeholder="Content (AR)">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i> Close
                </button>
                <button class="btn btn-primary" wire:click="save">
                    <i class="fa fa-save"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>

</div>
