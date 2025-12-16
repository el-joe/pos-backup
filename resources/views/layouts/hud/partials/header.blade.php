<!-- BEGIN #header -->
<div id="header" class="app-header">

    <!-- BEGIN desktop-toggler -->
    <div class="desktop-toggler">
        <button type="button" class="menu-toggler" data-toggle-class="app-sidebar-collapsed" data-dismiss-class="app-sidebar-toggled" data-toggle-target=".app">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </div>
    <!-- BEGIN desktop-toggler -->

    <!-- BEGIN mobile-toggler -->
    <div class="mobile-toggler">
        <button type="button" class="menu-toggler" data-toggle-class="app-sidebar-mobile-toggled" data-toggle-target=".app">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </div>
    <!-- END mobile-toggler -->



    <!-- BEGIN brand -->
    <div class="brand">
        <a href="{{ route('admin.statistics') }}" class="brand-logo">
            <span class="navbar-brand d-flex align-items-center me-auto px-0">
                <img src="{{ tenantSetting('logo', asset('mohaaseb_en_dark.png')) }}"
                    alt="Mohaaseb Logo"
                    class="d-inline-block"
                    height="50">
            </span>
            <span class="brand-text">{{ tenantSetting('business_name', tenant()->name) }}</span>
        </a>
    </div>
    <!-- END brand -->

    <!-- BEGIN menu -->
    <div class="menu">
        {{-- <div class="menu-item dropdown">
            <a href="#" data-toggle-class="app-header-menu-search-toggled" data-toggle-target=".app" class="menu-link">
                <div class="menu-icon"><i class="bi bi-search nav-icon"></i></div>
            </a>
        </div> --}}
        {{-- <div class="menu-item dropdown dropdown-mobile-full">
            <a href="#" data-bs-toggle="dropdown" data-bs-display="static" class="menu-link">
                <div class="menu-icon"><i class="bi bi-grid-3x3-gap nav-icon"></i></div>
            </a>
            <div class="dropdown-menu fade dropdown-menu-end w-300px text-center p-0 mt-1">
                <div class="row row-grid gx-0">
                    <div class="col-4">
                        <a href="email_inbox.html" class="dropdown-item text-decoration-none p-3 bg-none">
                            <div class="position-relative">
                                <i class="bi bi-circle-fill position-absolute text-theme top-0 mt-n2 me-n2 fs-6px d-block text-center w-100"></i>
                                <i class="bi bi-envelope h2 opacity-5 d-block my-1"></i>
                            </div>
                            <div class="fw-500 fs-10px text-inverse">INBOX</div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="pos_customer_order.html" target="_blank" class="dropdown-item text-decoration-none p-3 bg-none">
                            <div><i class="bi bi-hdd-network h2 opacity-5 d-block my-1"></i></div>
                            <div class="fw-500 fs-10px text-inverse">POS SYSTEM</div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="calendar.html" class="dropdown-item text-decoration-none p-3 bg-none">
                            <div><i class="bi bi-calendar4 h2 opacity-5 d-block my-1"></i></div>
                            <div class="fw-500 fs-10px text-inverse">CALENDAR</div>
                        </a>
                    </div>
                </div>
                <div class="row row-grid gx-0">
                    <div class="col-4">
                        <a href="helper.html" class="dropdown-item text-decoration-none p-3 bg-none">
                            <div><i class="bi bi-terminal h2 opacity-5 d-block my-1"></i></div>
                            <div class="fw-500 fs-10px text-inverse">HELPER</div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="settings.html" class="dropdown-item text-decoration-none p-3 bg-none">
                            <div class="position-relative">
                                <i class="bi bi-circle-fill position-absolute text-theme top-0 mt-n2 me-n2 fs-6px d-block text-center w-100"></i>
                                <i class="bi bi-sliders h2 opacity-5 d-block my-1"></i>
                            </div>
                            <div class="fw-500 fs-10px text-inverse">SETTINGS</div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="widgets.html" class="dropdown-item text-decoration-none p-3 bg-none">
                            <div><i class="bi bi-collection-play h2 opacity-5 d-block my-1"></i></div>
                            <div class="fw-500 fs-10px text-inverse">WIDGETS</div>
                        </a>
                    </div>
                </div>
            </div>
        </div> --}}

        @php
            $currentBranch = admin()->branch_id;
        @endphp

        <select id="branch-switcher" class="" style="display:inline-block; width:auto; min-width:180px;">
            <option value="">{{ __('general.layout.all_branches') }}</option>
            @foreach($__branches as $b)
                <option value="{{ $b->id }}" @if($currentBranch == $b->id) selected @endif>{{ $b->name }}</option>
            @endforeach
        </select>

        <div class="menu-item dropdown dropdown-mobile-full">
            <a href="#" data-bs-toggle="dropdown" data-bs-display="static" class="menu-link">
                <div class="menu-icon"><i class="bi bi-bell nav-icon"></i></div>
                @if(count($__unreaded_notifications) > 0)
                <div class="menu-badge bg-theme"></div>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-{{ $__locale == 'en' ? 'end' : 'start' }} mt-1 w-300px fs-11px pt-1">
                <h6 class="dropdown-header fs-10px mb-1">{{ __('general.layout.notifications') }}</h6>
                <div class="dropdown-divider mt-1"></div>
                @forelse ($__unreaded_notifications as $notification)
                    {!! __($notification->data['translation_key'], $notification->data['translation_params']+['id'=>$notification->id,'date'=>carbon($notification->created_at)->diffForHumans()] ?? []) !!}
                @empty
                    <div class="text-center p-2 text-muted">{{ __('general.layout.no_new_notifications') }}</div>
                @endforelse
                <hr class="my-0">
                <div class="py-10px mb-n2 text-center">
                    <a href="{{ route('admin.notifications.list') }}" class="text-decoration-none fw-bold">{{ __('general.layout.see_all') }}</a>
                </div>
            </div>
        </div>
        <div class="menu-item dropdown dropdown-mobile-full">
                <a href="#" data-bs-toggle="dropdown" data-bs-display="static" class="menu-link">
                    <div class="menu-img online">
                        <img src="{{ asset('hud/assets/img/user/profile.jpg') }}" alt="Profile" height="60">
                    </div>
                    {{-- <div class="menu-text d-sm-block d-none w-100px"></div> --}}
                </a>
                <div class="dropdown-menu  me-lg-3 fs-11px mt-1 dropdown-menu-end dropdown-menu-lg-{{ $__locale == 'en' ? 'end' : 'start' }}">
                    <h6 class="dropdown-header"> {{ __('general.pages.admins.name') }} : {{ admin()->name }}</h6>
                    <div class="dropdown-divider"></div>
                    {{-- <a class="dropdown-item d-flex align-items-center" href="profile.html">PROFILE <i class="bi bi-person-circle ms-auto text-theme fs-16px my-n1"></i></a>
                    <a class="dropdown-item d-flex align-items-center" href="email_inbox.html">INBOX <i class="bi bi-envelope ms-auto text-theme fs-16px my-n1"></i></a>
                    <a class="dropdown-item d-flex align-items-center" href="calendar.html">CALENDAR <i class="bi bi-calendar ms-auto text-theme fs-16px my-n1"></i></a>
                    <a class="dropdown-item d-flex align-items-center" href="settings.html">SETTINGS <i class="bi bi-gear ms-auto text-theme fs-16px my-n1"></i></a>
                    <div class="dropdown-divider"></div> --}}
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.logout') }}">{{ __('general.layout.logout') }} <i class="bi bi-toggle-off ms-auto text-theme fs-16px my-n1"></i></a>
                </div>
        </div>

    </div>
    <!-- END menu -->

    <!-- BEGIN menu-search -->
    <form class="menu-search" method="POST" name="header_search_form">
        <div class="menu-search-container">
            <div class="menu-search-icon"><i class="bi bi-search"></i></div>
            <div class="menu-search-input">
                <input type="text" class="form-control form-control-lg" placeholder="{{ __('general.layout.search') }} ...">
            </div>
            <div class="menu-search-icon">
                <a href="#" data-toggle-class="app-header-menu-search-toggled" data-toggle-target=".app"><i class="bi bi-x-lg"></i></a>
            </div>
        </div>
    </form>
    <!-- END menu-search -->
</div>
<!-- END #header -->

<script>
    document.addEventListener('DOMContentLoaded', function(){
        var sel = document.getElementById('branch-switcher');
        if (!sel) return;
        sel.addEventListener('change', function(){
            window.location.href = '/admin/switch-branch/' + this.value;
        });
    });
</script>

