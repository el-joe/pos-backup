<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Slider List</h5>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editSliderModal"
                    wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> Add Slider
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Number</th>
                            <th>Image</th>
                            <th>Active</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sliders as $slider)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $slider->title }}</td>
                                <td>{{ $slider->number }}</td>
                                <td>
                                    <img src="{{ $slider->image_path }}" alt="" class="img-thumbnail"
                                        width="60">
                                </td>
                                <td>
                                    <span class="badge bg-{{ $slider->active ? 'success' : 'danger' }}">
                                        {{ $slider->active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal"
                                        data-bs-target="#editSliderModal" wire:click="setCurrent({{ $slider->id }})"
                                        title="Edit Slider">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="deleteAlert({{ $slider->id }})"
                                        title="Delete Slider">
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

    <!-- Edit / Create Slider Modal -->
    <div class="modal fade" id="editSliderModal" tabindex="-1" aria-labelledby="editSliderModalLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-sm">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSliderModalLabel">
                        {{ $current?->id ? 'Edit Slider' : 'Add New Slider' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="sliderTitle" class="form-label">Slider Title</label>
                            <input type="text" class="form-control" wire:model="data.title" id="sliderTitle"
                                placeholder="Enter slider title">
                        </div>

                        <div class="mb-3">
                            <label for="sliderNumber" class="form-label">Slider Number</label>
                            <input type="text" class="form-control" wire:model="data.number" id="sliderNumber"
                                placeholder="Enter slider number">
                        </div>

                        <div class="mb-3">
                            <label for="sliderImage" class="form-label">Slider Image</label>
                            <input type="file" class="form-control" wire:model="data.image" id="sliderImage">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Preview</label>

                            <div class="border rounded p-2 text-center">
                                @if (!empty($data['image']))
                                    <img src="{{ $data['image']->temporaryUrl() }}" class="img-fluid rounded"
                                        style="max-height: 200px;">
                                @elseif (!empty($data['image_path']))
                                    <img src="{{ $data['image_path'] }}" class="img-fluid rounded"
                                        style="max-height: 200px;">
                                @else
                                    <div class="text-muted small">
                                        No image selected
                                    </div>
                                @endif

                                @if (!empty($data['image']))
                                    <button type="button" class="btn btn-sm btn-outline-danger mt-2"
                                        wire:click="$set('data.image', null)">
                                        Remove new image
                                    </button>
                                @endif

                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" wire:model="data.active" id="branchActive">
                            <label class="form-check-label" for="branchActive">
                                Is Active
                            </label>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="save">
                        <i class="fa fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
