<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.titles.faqs') }} ({{ $faqs->total() }})</h5>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-primary" href="{{ route('cpanel.faqs.create') }}">
                    <i class="fa fa-plus"></i> New FAQ
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Question (EN)</th>
                            <th>Published</th>
                            <th>Sort</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($faqs as $faq)
                            <tr>
                                <td>{{ $faq->id }}</td>
                                <td>{{ $faq->question_en }}</td>
                                <td>
                                    <span class="badge bg-{{ $faq->is_published ? 'success' : 'danger' }}">
                                        {{ $faq->is_published ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>{{ $faq->sort_order }}</td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-primary me-1"
                                        href="{{ route('cpanel.faqs.edit', ['id' => $faq->id]) }}" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" wire:click="deleteAlert({{ $faq->id }})"
                                        title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $faqs->links("pagination::bootstrap-5") }}
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
