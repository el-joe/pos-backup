<!-- BEGIN row -->
<div class="row">
    @if(adminCan('statistics.show'))
    <!-- BEGIN col-3 -->
    <div class="col-xl-3 col-lg-6 mb-3">
        <a href="#" class="card text-decoration-none">
            <div class="card-body d-flex align-items-center m-5px bg-success bg-opacity-10 text-success">
                <div class="flex-fill">
                <div class="mb-1">{{ __('general.pages.statistics.total_sales') }}</div>
                <h2>${{ $data['totalSales'] }}</h2>
                </div>
                <div class="opacity-5">
                <i class="fa fa-cash-register fa-4x"></i>
                </div>
            </div>

            <!-- card-arrow -->
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </a>
    </div>
    <!-- END col-3 -->

        <!-- BEGIN col-3 -->
    <div class="col-xl-3 col-lg-6 mb-3">
        <a href="#" class="card text-decoration-none">
            <div class="card-body d-flex align-items-center m-5px bg-success bg-opacity-25 text-success">
                <div class="flex-fill">
                <div class="mb-1">{{ __('general.pages.statistics.net_sales') }}</div>
                <h2>${{ $data['netSales'] }}</h2>
                </div>
                <div class="opacity-5">
                <i class="fa fa-chart-line fa-4x"></i>
                </div>
            </div>

            <!-- card-arrow -->
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </a>
    </div>
    <!-- END col-3 -->

    <!-- BEGIN col-3 -->
    <div class="col-xl-3 col-lg-6 mb-3">
        <a href="#" class="card text-decoration-none">
            <div class="card-body d-flex align-items-center m-5px bg-warning bg-opacity-10 text-warning">
                <div class="flex-fill">
                <div class="mb-1">{{ __('general.pages.statistics.due_amount') }}</div>
                <h2>${{ $data['dueAmount'] }}</h2>
                </div>
                <div class="opacity-5">
                <i class="fa fa-hand-holding-usd fa-4x"></i>
                </div>
            </div>

            <!-- card-arrow -->
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </a>
    </div>
    <!-- END col-3 -->
    <!-- BEGIN col-3 -->
    <div class="col-xl-3 col-lg-6 mb-3">
        <a href="#" class="card text-decoration-none">
            <div class="card-body d-flex align-items-center m-5px bg-danger bg-opacity-10 text-danger">
                <div class="flex-fill">
                <div class="mb-1">{{ __('general.pages.statistics.total_sales_return') }}</div>
                <h2>${{ $data['totalSalesReturn'] }}</h2>
                </div>
                <div class="opacity-5">
                <i class="fa fa-undo-alt fa-4x"></i>
                </div>
            </div>

            <!-- card-arrow -->
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </a>
    </div>
    <!-- END col-3 -->

        <!-- BEGIN col-3 -->
    <div class="col-xl-3 col-lg-6 mb-3">
        <a href="#" class="card text-decoration-none">
            <div class="card-body d-flex align-items-center m-5px bg-primary bg-opacity-10 text-primary">
                <div class="flex-fill">
                <div class="mb-1">{{ __('general.pages.statistics.total_purchases') }}</div>
                <h2>${{ $data['totalPurchases'] }}</h2>
                </div>
                <div class="opacity-5">
                <i class="fa fa-shopping-cart fa-4x"></i>
                </div>
            </div>

            <!-- card-arrow -->
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </a>
    </div>
    <!-- END col-3 -->


        <!-- BEGIN col-3 -->
    <div class="col-xl-3 col-lg-6 mb-3">
        <a href="#" class="card text-decoration-none">
            <div class="card-body d-flex align-items-center m-5px bg-purple bg-opacity-10 text-purple">
                <div class="flex-fill">
                <div class="mb-1">{{ __('general.pages.statistics.purchase_due') }}</div>
                <h2>${{ $data['purchaseDue'] }}</h2>
                </div>
                <div class="opacity-5">
                <i class="fa fa-file-invoice-dollar fa-4x"></i>
                </div>
            </div>

            <!-- card-arrow -->
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </a>
    </div>
    <!-- END col-3 -->


        <!-- BEGIN col-3 -->
    <div class="col-xl-3 col-lg-6 mb-3">
        <a href="#" class="card text-decoration-none">
            <div class="card-body d-flex align-items-center m-5px bg-danger bg-opacity-25 text-danger">
                <div class="flex-fill">
                <div class="mb-1">{{ __('general.pages.statistics.total_purchase_return') }}</div>
                <h2>${{ $data['totalPurchaseReturn'] }}</h2>
                </div>
                <div class="opacity-5">
                <i class="fa fa-reply-all fa-4x"></i>
                </div>
            </div>

            <!-- card-arrow -->
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </a>
    </div>
    <!-- END col-3 -->


        <!-- BEGIN col-3 -->
    <div class="col-xl-3 col-lg-6 mb-3">
        <a href="#" class="card text-decoration-none">
            <div class="card-body d-flex align-items-center m-5px bg-secondary bg-opacity-10 text-secondary">
                <div class="flex-fill">
                <div class="mb-1">{{ __('general.pages.statistics.total_expense') }}</div>
                <h2>${{ $data['totalExpense'] }}</h2>
                </div>
                <div class="opacity-5">
                <i class="fa fa-receipt fa-4x"></i>
                </div>
            </div>

            <!-- card-arrow -->
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </a>
    </div>
    <!-- END col-3 -->

    <div class="col-sm-12 mb-3">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('general.pages.statistics.sales_overview_last_30_days') }}</h4>
            </div>
            <div class="card-body">
                <canvas id="lineChart"></canvas>
            </div>
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 mb-3">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('general.pages.statistics.sales_overview_last_12_months') }}</h4>
            </div>
            <div class="card-body">
                <canvas id="lineChart2"></canvas>
            </div>
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </div>
    </div>
    @else
        <div class="col-12">
            <div class="alert alert-danger">
                {{ __('general.messages.you_do_not_have_permission_to_access') }}
            </div>
        </div>
    @endif

</div>
<!-- END row -->

@push('scripts')
    	<!-- ================== BEGIN page-js ================== -->
	<script src="{{ asset('hud/assets/plugins/jvectormap-next/jquery-jvectormap.min.js') }}"></script>
	<script src="{{ asset('hud/assets/plugins/jvectormap-content/world-mill.js') }}"></script>
	<script src="{{ asset('hud/assets/plugins/apexcharts/dist/apexcharts.min.js') }}"></script>
	<script src="{{ asset('hud/assets/js/demo/dashboard.demo.js') }}"></script>
	<!-- ================== END page-js ================== -->
    <script src="{{ asset('hud/assets/plugins/chart.js/dist/chart.umd.js') }}"></script>
    <script>
        var ctx = document.getElementById('lineChart');

        var lineChart = new Chart(ctx, {
            type: 'line',
            data: {
            labels: @json($saleOrdersPerDaylabels),
            datasets: [{
                color: app.color.theme,
                borderColor: app.color.theme,
                borderWidth: 1.5,
                pointBackgroundColor: app.color.theme,
                pointBorderWidth: 1.5,
                pointRadius: 4,
                pointHoverBackgroundColor: app.color.theme,
                pointHoverBorderColor: app.color.theme,
                pointHoverRadius: 7,
                label: '{{ __('general.pages.statistics.total_sales') }}',
                data: @json($saleOrdersPerDayData)
            }]
            }
        });
    </script>

    <script>
        var ctx = document.getElementById('lineChart2');

        var lineChart = new Chart(ctx, {
            type: 'line',
            data: {
            labels: @json($saleOrdersPerMonthlabels),
            datasets: [{
                color: app.color.theme,
                borderColor: app.color.theme,
                borderWidth: 1.5,
                pointBackgroundColor: app.color.theme,
                pointBorderWidth: 1.5,
                pointRadius: 4,
                pointHoverBackgroundColor: app.color.theme,
                pointHoverBorderColor: app.color.theme,
                pointHoverRadius: 7,
                label: '{{ __('general.pages.statistics.total_sales') }}',
                data: @json($saleOrdersPerMonthData)
            }]
            }
        });
    </script>


@endpush

@push('styles')
    <!-- ================== BEGIN page-css ================== -->
<link href="{{ asset('hud/assets/plugins/jvectormap-next/jquery-jvectormap.css') }}" rel="stylesheet">
<!-- ================== END page-css ================== -->
@endpush
