<div class="col-12">
    <form wire:submit.prevent="save">
        <div class="card shadow-sm mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $title ?? ($employee_id ? __('hrm.edit_employee') : __('hrm.add_employee')) }}</h5>
                <a class="btn btn-outline-secondary" href="{{ route('admin.hrm.employees.list') }}">
                    <i class="fa fa-arrow-left me-1"></i> {{ __('general.pages.hrm.back') }}
                </a>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('hrm.first_name') }}</label>
                        <input type="text" class="form-control" wire:model.defer="first_name">
                        @error('first_name') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('hrm.last_name') }}</label>
                        <input type="text" class="form-control" wire:model.defer="last_name">
                        @error('last_name') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">{{ __('hrm.email') }}</label>
                        <input type="email" class="form-control" wire:model.defer="email">
                        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('hrm.phone') }}</label>
                        <input type="text" class="form-control" wire:model.defer="phone">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('hrm.mobile') }}</label>
                        <input type="text" class="form-control" wire:model.defer="mobile">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">{{ __('hrm.gender') }}</label>
                        <select class="form-select" wire:model.defer="gender">
                            <option value="male">{{ __('hrm.male') }}</option>
                            <option value="female">{{ __('hrm.female') }}</option>
                            <option value="other">{{ __('hrm.other') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('hrm.date_of_birth') }}</label>
                        <input type="date" class="form-control" wire:model.defer="date_of_birth">
                    </div>

                    <div class="col-12"><hr class="my-2"></div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('hrm.department') }}</label>
                        <select class="form-select" wire:model.defer="department_id">
                            <option value="">--</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('hrm.designation') }}</label>
                        <select class="form-select" wire:model.defer="designation_id">
                            <option value="">--</option>
                            @foreach($designations as $designation)
                                <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                            @endforeach
                        </select>
                        @error('designation_id') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('hrm.branch') }}</label>
                        <select class="form-select" wire:model.defer="branch_id">
                            <option value="">--</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @error('branch_id') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('hrm.manager') }}</label>
                        <select class="form-select" wire:model.defer="manager_id">
                            <option value="">--</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->id }}">{{ $manager->first_name }} {{ $manager->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('hrm.employment_type') }}</label>
                        <select class="form-select" wire:model.defer="employment_type">
                            @foreach($employment_types as $type)
                                <option value="{{ $type->value }}">{{ ucfirst(str_replace('_',' ', $type->value)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('hrm.status') }}</label>
                        <select class="form-select" wire:model.defer="status">
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}">{{ ucfirst(str_replace('_',' ', $status->value)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('hrm.joining_date') }}</label>
                        <input type="date" class="form-control" wire:model.defer="joining_date">
                        @error('joining_date') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('hrm.probation_end_date') }}</label>
                        <input type="date" class="form-control" wire:model.defer="probation_end_date">
                    </div>

                    <div class="col-12"><hr class="my-2"></div>

                    <div class="col-md-6">
                        <label class="form-label">{{ __('hrm.photo') ?? 'Photo' }}</label>
                        <input type="file" class="form-control" wire:model="photo" accept="image/*">
                        @error('photo') <div class="text-danger small">{{ $message }}</div> @enderror
                        @if($photo)
                            <div class="mt-2">
                                <img src="{{ $photo->temporaryUrl() }}" alt="preview" class="rounded border" style="width: 96px; height: 96px; object-fit: cover;">
                            </div>
                        @endif
                    </div>

                    <div class="col-12">
                        <label class="form-label">{{ __('hrm.bio') ?? 'Bio' }}</label>
                        <textarea class="form-control" rows="3" wire:model.defer="bio"></textarea>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end gap-2">
                <a class="btn btn-secondary" href="{{ route('admin.hrm.employees.list') }}">{{ __('general.pages.hrm.back') }}</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> {{ __('general.pages.hrm.save') }}
                </button>
            </div>

            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </div>
    </form>
</div>
