<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Countries List</h5>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editCountryModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> Add Country
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Country Name</th>
                            <th>Country Code</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($countries as $country)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $country->name }}</td>
                                <td>{{ $country->code }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary me-1"
                                            data-bs-toggle="modal" data-bs-target="#editCountryModal"
                                            wire:click="setCurrent({{ $country->id }})"
                                            title="country.edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger"
                                            wire:click="deleteAlert({{ $country->id }})"
                                            title="country.delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- pagination center aligned (optional) --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $countries->links("pagination::bootstrap-5") }}
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

    <!-- Edit / Create Country Modal -->
    <div class="modal fade" id="editCountryModal" tabindex="-1" aria-labelledby="editCountryModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-sm">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCountryModalLabel">{{ $current?->id ? 'Edit Country' : 'Add New Country' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="countryName" class="form-label">Country Name</label>
                            <input type="text" class="form-control" wire:model="data.name" id="countryName" placeholder="Enter Country name">
                        </div>

                        <div class="mb-3">
                            <label for="countryCode" class="form-label">Country Code</label>
                            <input type="text" class="form-control" wire:model="data.code" id="countryCode" placeholder="Enter country code">
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
