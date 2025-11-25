<div class="col-12">
    <div class="card shadow-sm position-relative">
        @if(!!$user->active)
            <span class="badge bg-success position-absolute top-0 end-0 m-3">{{ __('general.pages.users.active') }}</span>
        @else
            <span class="badge bg-danger position-absolute top-0 end-0 m-3">{{ __('general.pages.users.not_active') }}</span>
        @endif

        <div class="card-header">
            <h5 class="mb-0">{{ $user->name }} - {{ __('general.pages.users.profile') }}</h5>
        </div>

        <div class="card-body">
            <!-- Nav Tabs -->
            <ul class="nav nav-tabs mb-3" id="userProfileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'details' ? 'active' : '' }}"
                        id="details-tab"
                        data-bs-toggle="tab"
                        type="button"
                        wire:click="$set('activeTab', 'details')"
                        role="tab"
                        aria-controls="details"
                        aria-selected="{{ $activeTab === 'details' ? 'true' : 'false' }}">
                        <i class="fa fa-user me-1"></i> {{ __('general.pages.users.details_tab') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'accounts' ? 'active' : '' }}"
                        id="accounts-tab"
                        data-bs-toggle="tab"
                        type="button"
                        wire:click="$set('activeTab', 'accounts')"
                        role="tab"
                        aria-controls="accounts"
                        aria-selected="{{ $activeTab === 'accounts' ? 'true' : 'false' }}">
                        <i class="fa fa-credit-card me-1"></i> {{ __('general.pages.users.accounts_tab') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'transactions' ? 'active' : '' }}"
                        id="transactions-tab"
                        data-bs-toggle="tab"
                        type="button"
                        wire:click="$set('activeTab', 'transactions')"
                        role="tab"
                        aria-controls="transactions"
                        aria-selected="{{ $activeTab === 'transactions' ? 'true' : 'false' }}">
                        <i class="fa fa-exchange me-1"></i> {{ __('general.pages.users.transactions_tab') }}
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="userProfileTabContent">
                <!-- Details Tab -->
                <div class="tab-pane fade {{ $activeTab === 'details' ? 'show active' : '' }}" id="details" role="tabpanel" aria-labelledby="details-tab">
                    <div class="row g-3">
                        <div class="col-md-3 col-6 border-end">
                            <strong>{{ __('general.pages.users.full_name') }}</strong>
                            <p class="text-muted mb-0">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-3 col-6 border-end">
                            <strong>{{ __('general.pages.users.mobile') }}</strong>
                            <p class="text-muted mb-0">{{ $user->phone }}</p>
                        </div>
                        <div class="col-md-3 col-6 border-end">
                            <strong>{{ __('general.pages.users.email') }}</strong>
                            <p class="text-muted mb-0">{{ $user->email }}</p>
                        </div>
                        <div class="col-md-3 col-6">
                            <strong>{{ __('general.pages.users.address') }}</strong>
                            <p class="text-muted mb-0">{{ $user->address }}</p>
                        </div>
                    </div>
                </div>

                <!-- Accounts Tab -->
                <div class="tab-pane fade {{ $activeTab === 'accounts' ? 'show active' : '' }}" id="accounts" role="tabpanel" aria-labelledby="accounts-tab">
                    @livewire('admin.accounts.accounts-list', [
                        'subPage' => true,
                        'filters' => [
                            'model_type' => \App\Models\Tenant\User::class,
                            'model_id' => $user->id
                        ]
                    ])
                </div>

                <!-- Transactions Tab -->
                <div class="tab-pane fade {{ $activeTab === 'transactions' ? 'show active' : '' }}" id="transactions" role="tabpanel" aria-labelledby="transactions-tab">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>{{ __('general.pages.users.details_intro_title') }}</h4>
                            <p class="text-muted">{{ __('general.pages.users.details_intro_text') }}</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p>{{ __('general.pages.users.details_intro_side') }}</p>
                        </div>
                    </div>
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

@push('styles')
<style>
    .position-relative {
        position: relative;
    }
    .position-absolute {
        position: absolute;
    }
</style>
@endpush
