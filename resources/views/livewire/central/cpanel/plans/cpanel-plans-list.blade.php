<div class="col-12">
    <div class="row g-3">
        @foreach (['monthly' => 'Monthly Plan', 'annual' => 'Annual Plan'] as $slug => $label)
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $label }}</h5>
                        <span class="badge bg-{{ !empty($data[$slug]['active']) ? 'success' : 'danger' }}">
                            {{ !empty($data[$slug]['active']) ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Name (EN)</label>
                                <input type="text" class="form-control" wire:model="data.{{ $slug }}.name_en">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Name (AR)</label>
                                <input type="text" class="form-control" wire:model="data.{{ $slug }}.name_ar" dir="rtl">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Price / Month</label>
                                <input type="number" step="0.01" class="form-control" wire:model="data.{{ $slug }}.price_month">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Price / Year</label>
                                <input type="number" step="0.01" class="form-control" wire:model="data.{{ $slug }}.price_year">
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" wire:model="data.{{ $slug }}.active" id="active_{{ $slug }}">
                                    <label class="form-check-label" for="active_{{ $slug }}">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button class="btn btn-primary" wire:click="save('{{ $slug }}')">
                            <i class="fa fa-save"></i> Save
                        </button>
                    </div>

                    <div class="card-arrow">
                        <div class="card-arrow-top-left"></div>
                        <div class="card-arrow-top-right"></div>
                        <div class="card-arrow-bottom-left"></div>
                        <div class="card-arrow-bottom-right"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
