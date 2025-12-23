<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.titles.blogs') }}</h5>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-primary" href="{{ route('cpanel.blogs.create') }}">
                    <i class="fa fa-plus"></i> New Blog
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
                            <th>Published</th>
                            <th>Published At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($blogs as $blog)
                            <tr>
                                <td>{{ $blog->id }}</td>
                                <td>{{ $blog->title_en }}</td>
                                <td>
                                    <span class="badge bg-{{ $blog->is_published ? 'success' : 'danger' }}">
                                        {{ $blog->is_published ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>{{ $blog->published_at?->format('Y-m-d H:i') }}</td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-primary me-1"
                                        href="{{ route('cpanel.blogs.edit', ['id' => $blog->id]) }}" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" wire:click="deleteAlert({{ $blog->id }})"
                                        title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $blogs->links() }}
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
