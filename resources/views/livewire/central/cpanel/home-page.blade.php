<div class="row">
    <div class="col-12 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="fw-bold">{{ __('general.titles.statistics') }}</div>
                        <div class="text-muted small">CPanel overview</div>
                    </div>
                    <a class="btn btn-outline-theme" href="{{ route('cpanel.dashboard') }}">
                        <i class="fa fa-sync"></i> Refresh
                    </a>
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

    <div class="col-xl-3 col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">Pending Register Requests</div>
                    <i class="fa fa-user-plus text-warning"></i>
                </div>
                <h3 class="mb-0">{{ number_format($stats['pending_register_requests'] ?? 0) }}</h3>
                <div class="text-muted small">Waiting for approval</div>
                <div class="mt-2">
                    <a class="btn btn-sm btn-outline-theme" href="{{ route('cpanel.register-requests.list') }}">View</a>
                </div>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">Unread Register Requests</div>
                    <i class="fa fa-eye text-primary"></i>
                </div>
                <h3 class="mb-0">{{ number_format($stats['unread_register_requests'] ?? 0) }}</h3>
                <div class="text-muted small">Not opened yet</div>
                <div class="mt-2">
                    <a class="btn btn-sm btn-outline-theme" href="{{ route('cpanel.register-requests.list') }}">View</a>
                </div>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">Tenants</div>
                    <i class="fa fa-building text-inverse"></i>
                </div>
                <h3 class="mb-0">{{ number_format($stats['tenants'] ?? 0) }}</h3>
                <div class="text-muted small">All registered tenants</div>
                <div class="mt-2">
                    <a class="btn btn-sm btn-outline-theme" href="{{ route('cpanel.customers.list') }}">View</a>
                </div>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">Partners</div>
                    <i class="fa fa-handshake text-theme"></i>
                </div>
                <h3 class="mb-0">{{ number_format($stats['partners'] ?? 0) }}</h3>
                <div class="text-muted small">All partners</div>
                <div class="mt-2">
                    <a class="btn btn-sm btn-outline-theme" href="{{ route('cpanel.partners.list') }}">View</a>
                </div>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">Partner Commissions</div>
                    <i class="fa fa-coins text-warning"></i>
                </div>
                <h3 class="mb-0">{{ number_format($stats['partner_commissions'] ?? 0) }}</h3>
                <div class="text-muted small">Total commissions</div>
                <div class="mt-2">
                    <a class="btn btn-sm btn-outline-theme" href="{{ route('cpanel.partner-commissions.list') }}">View</a>
                </div>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">Pending Commissions</div>
                    <i class="fa fa-clock text-warning"></i>
                </div>
                <h3 class="mb-0">{{ number_format($stats['partner_commissions_pending'] ?? 0) }}</h3>
                <div class="text-muted small">Not collected yet</div>
                <div class="mt-2">
                    <a class="btn btn-sm btn-outline-theme" href="{{ route('cpanel.partner-commissions.list') }}">View</a>
                </div>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">All Subscriptions</div>
                    <i class="fa fa-list text-inverse"></i>
                </div>
                <h3 class="mb-0">{{ number_format($stats['subscriptions_all'] ?? 0) }}</h3>
                <div class="text-muted small">All statuses</div>
                <div class="mt-2">
                    <a class="btn btn-sm btn-outline-theme" href="{{ route('cpanel.subscriptions.list') }}">View</a>
                </div>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">Paid Subscriptions</div>
                    <i class="fa fa-check-circle text-success"></i>
                </div>
                <h3 class="mb-0">{{ number_format($stats['subscriptions_paid'] ?? 0) }}</h3>
                <div class="text-muted small">Status: paid</div>
                <div class="mt-2">
                    <a class="btn btn-sm btn-outline-theme" href="{{ route('cpanel.subscriptions.list') }}">View</a>
                </div>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">Expiring Soon</div>
                    <i class="fa fa-exclamation-triangle text-danger"></i>
                </div>
                <h3 class="mb-0">{{ number_format($stats['subscriptions_expiring_soon'] ?? 0) }}</h3>
                <div class="text-muted small">Within 3 days</div>
                <div class="mt-2">
                    <a class="btn btn-sm btn-outline-theme" href="{{ route('cpanel.subscriptions.list') }}">View</a>
                </div>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">Blogs</div>
                    <i class="fa fa-newspaper text-inverse"></i>
                </div>
                <h3 class="mb-0">{{ number_format($stats['blogs'] ?? 0) }}</h3>
                <div class="text-muted small">All posts</div>
                <div class="mt-2">
                    <a class="btn btn-sm btn-outline-theme" href="{{ route('cpanel.blogs.list') }}">View</a>
                </div>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">FAQs</div>
                    <i class="fa fa-question-circle text-inverse"></i>
                </div>
                <h3 class="mb-0">{{ number_format($stats['faqs'] ?? 0) }}</h3>
                <div class="text-muted small">All questions</div>
                <div class="mt-2">
                    <a class="btn btn-sm btn-outline-theme" href="{{ route('cpanel.faqs.list') }}">View</a>
                </div>
            </div>
            <div class="card-arrow"><div class="card-arrow-top-left"></div><div class="card-arrow-top-right"></div><div class="card-arrow-bottom-left"></div><div class="card-arrow-bottom-right"></div></div>
        </div>
    </div>

    <div class="col-xl-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Paid Subscriptions Amount (by Currency)</h5>
                <a class="btn btn-sm btn-outline-theme" href="{{ route('cpanel.subscriptions.list') }}">Subscriptions</a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Currency</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($paidAmountsByCurrency as $row)
                                <tr>
                                    <td>{{ $row['code'] }}</td>
                                    <td class="text-end">
                                        {{ $row['symbol'] }} {{ number_format((float) ($row['total'] ?? 0), 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">No paid subscriptions yet</td>
                                </tr>
                            @endforelse
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
    </div>

    <!-- BEGIN col-6 -->
    <div class="col-xl-6">
        <!-- BEGIN card -->
        <div class="card mb-3">
            <!-- BEGIN card-body -->
            <div class="card-body">
                <!-- BEGIN title -->
                <div class="d-flex fw-bold small mb-3">
                    <span class="flex-grow-1">TRAFFIC ANALYTICS</span>
                    <a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
                </div>
                <!-- END title -->
                <!-- BEGIN map -->
                <div class="ratio ratio-21x9 mb-3">
                    <div id="world-map" class="jvectormap-without-padding"></div>
                </div>
                <!-- END map -->
                <!-- BEGIN row -->
                <div class="row gx-4">
                    <!-- BEGIN col-6 -->
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <table class="w-100 small mb-0 text-truncate text-inverse text-opacity-60">
                            <thead>
                                <tr class="text-inverse text-opacity-75">
                                    <th class="w-50">COUNTRY</th>
                                    <th class="w-25 text-end">VISITS</th>
                                    <th class="w-25 text-end">PCT%</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>FRANCE</td>
                                    <td class="text-end">13,849</td>
                                    <td class="text-end">40.79%</td>
                                </tr>
                                <tr>
                                    <td>SPAIN</td>
                                    <td class="text-end">3,216</td>
                                    <td class="text-end">9.79%</td>
                                </tr>
                                <tr class="text-theme fw-bold">
                                    <td>MEXICO</td>
                                    <td class="text-end">1,398</td>
                                    <td class="text-end">4.26%</td>
                                </tr>
                                <tr>
                                    <td>UNITED STATES</td>
                                    <td class="text-end">1,090</td>
                                    <td class="text-end">3.32%</td>
                                </tr>
                                <tr>
                                    <td>BELGIUM</td>
                                    <td class="text-end">1,045</td>
                                    <td class="text-end">3.18%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- END col-6 -->
                    <!-- BEGIN col-6 -->
                    <div class="col-lg-6">
                        <!-- BEGIN card -->
                        <div class="card">
                            <!-- BEGIN card-body -->
                            <div class="card-body py-2">
                                <div class="d-flex align-items-center">
                                    <div class="w-70px">
                                        <div data-render="apexchart" data-type="donut" data-height="70"></div>
                                    </div>
                                    <div class="flex-1 ps-2">
                                        <table class="w-100 small mb-0 text-inverse text-opacity-60">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="w-6px h-6px rounded-pill me-2 bg-theme bg-opacity-95"></div> FEED
                                                        </div>
                                                    </td>
                                                    <td class="text-end">25.70%</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="w-6px h-6px rounded-pill me-2 bg-theme bg-opacity-75"></div> ORGANIC
                                                        </div>
                                                    </td>
                                                    <td class="text-end">24.30%</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="w-6px h-6px rounded-pill me-2 bg-theme bg-opacity-55"></div> REFERRAL
                                                        </div>
                                                    </td>
                                                    <td class="text-end">23.05%</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="w-6px h-6px rounded-pill me-2 bg-theme bg-opacity-35"></div> DIRECT
                                                        </div>
                                                    </td>
                                                    <td class="text-end">14.85%</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="w-6px h-6px rounded-pill me-2 bg-theme bg-opacity-15"></div> EMAIL
                                                        </div>
                                                    </td>
                                                    <td class="text-end">7.35%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- END card-body -->

                            <!-- BEGIN card-arrow -->
                            <div class="card-arrow">
                                <div class="card-arrow-top-left"></div>
                                <div class="card-arrow-top-right"></div>
                                <div class="card-arrow-bottom-left"></div>
                                <div class="card-arrow-bottom-right"></div>
                            </div>
                            <!-- END card-arrow -->
                        </div>
                        <!-- END card -->
                    </div>
                    <!-- END col-6 -->
                </div>
                <!-- END row -->
            </div>
            <!-- END card-body -->

            <!-- BEGIN card-arrow -->
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
            <!-- END card-arrow -->
        </div>
        <!-- END card -->
    </div>
    <!-- END col-6 -->

    <!-- END col-6 -->
</div>

@push('styles')
	<link href="{{ asset('hud/assets/') }}/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet">

@endpush

@push('scripts')
    <script src="{{ asset('hud/assets/') }}/plugins/jvectormap-next/jquery-jvectormap.min.js"></script>
	<script src="{{ asset('hud/assets/') }}/plugins/jvectormap-content/world-mill.js"></script>
	<script src="{{ asset('hud/assets/') }}/plugins/apexcharts/dist/apexcharts.min.js"></script>
	<script src="{{ asset('hud/assets/') }}/js/demo/dashboard.demo.js"></script>
@endpush
