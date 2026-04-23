@props(['titleKey' => '', 'entityKey' => '', 'icon' => 'fa-hard-hat'])

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div class="avatar-sm me-3">
                            <span class="avatar-title rounded bg-primary-subtle text-primary">
                                <i class="fa {{ $icon }}"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="card-title mb-0">{{ $titleKey ? __($titleKey) : '' }}</h4>
                            <p class="text-muted mb-0 small">{{ __('general.pages.contracting.module_description') }}
                            </p>
                        </div>
                        <span class="badge bg-warning-subtle text-warning">
                            {{ __('general.pages.contracting.coming_soon') }}
                        </span>
                    </div>
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="fa fa-tools fa-3x text-muted"></i>
                        </div>
                        <h5 class="mb-2">{{ __('general.pages.contracting.under_construction') }}</h5>
                        <p class="text-muted mb-0">{{ $entityKey ? __($entityKey . '.title') : '' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>