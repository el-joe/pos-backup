<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Pages ({{ $pages->total() }})</h5>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-primary" href="{{ route('cpanel.pages.create') }}">
                    <i class="fa fa-plus"></i> New Page
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Title (EN)</th>
                            <th>Slug</th>
                            <th>Published</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pages as $page)
                            <tr>
                                <td>{{ $page->id }}</td>
                                <td>{{ $page->title_en }}</td>
                                <td><code>{{ $page->slug }}</code></td>
                                <td>
                                    <span class="badge bg-{{ $page->is_published ? 'success' : 'danger' }}">
                                        {{ $page->is_published ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-primary me-1"
                                       href="{{ route('cpanel.pages.edit', ['id' => $page->id]) }}" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" wire:click="deleteAlert({{ $page->id }})" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $pages->links("pagination::bootstrap-5") }}
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
</div>
