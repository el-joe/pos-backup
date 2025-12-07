<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Languages List</h5>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editLanguageModal"
                    wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> Add Language
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Active</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($languages as $Language)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $Language->name }}</td>
                                <td>{{ $Language->code }}</td>
                                <td>
                                    <span class="badge bg-{{ $Language->active ? 'success' : 'danger' }}">
                                        {{ $Language->active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal"
                                        data-bs-target="#editLanguageModal" wire:click="setCurrent({{ $Language->id }})"
                                        title="language.edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="deleteAlert({{ $Language->id }})"
                                        title="language.delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- pagination center aligned (optional) --}}
                {{-- <div class="d-flex justify-content-center mt-3">
                    {{ $languages->links() }}
                </div> --}}
            </div>
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>

    <!-- Edit / Create Lamguage Modal -->
    <div class="modal fade" id="editLanguageModal" tabindex="-1" aria-labelledby="editLanguageModalLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-sm">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLanguageModalLabel">
                        {{ $current?->id ? 'Edit Language' : 'Add New Language' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="languageName" class="form-label">Language Name</label>
                            <input type="text" class="form-control" wire:model="data.name" id="languageName"
                                placeholder="Enter language name">
                        </div>

                        <div class="mb-3">
                            <label for="languageCode" class="form-label">Language Code</label>
                            <input type="text" class="form-control" wire:model="data.code" id="languageCode"
                                placeholder="Enter language code">
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
