@extends('layouts.central.site.layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <div class="card shadow-sm text-center">
                @if($type == 'success')
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">{{ __('website.payment.success_title') }}</h4>
                    </div>

                    <div class="card-body">
                        <i class="fa fa-check-circle fa-4x text-success mb-3"></i>
                        <h5 class="mb-3">{{ __('website.payment.success_subtitle') }}</h5>

                        <!-- Custom Message -->
                        <p class="text-muted">
                            {{ $message ?? __('website.payment.success_message') }}
                        </p>

                        <a href="/" class="btn btn-primary mt-3">
                            <i class="fa fa-home"></i> {{ __('website.payment.back_to_home') }}
                        </a>
                    </div>
                @else
                    <div class="card-header bg-danger text-white">
                        <h4 class="mb-0">{{ __('website.payment.failed_title') }}</h4>
                    </div>

                    <div class="card-body">
                        <i class="fa fa-times-circle fa-4x text-danger mb-3"></i>
                        <h5 class="mb-3">{{ __('website.payment.failed_subtitle') }}</h5>

                        <!-- Custom Message -->
                        <p class="text-muted">
                            {{ $message ?? __('website.payment.failed_message') }}
                        </p>

                        <a href="/payment/retry" class="btn btn-secondary mt-3">
                            <i class="fa fa-redo"></i> {{ __('website.payment.try_again') }}
                        </a>
                    </div>
                @endif

                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
