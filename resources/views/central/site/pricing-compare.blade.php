@extends('layouts.central.site.layout')

@section('content')

<div class="container pb-5">

    <!-- COMPARISON TABLE -->

        @livewire('central.site.plan-section', ['period' => 'month'])

        <div class="table-responsive">
            {{-- title --}}
            <div class="mb-4">
                <h1 class="mb-3 text-center">Compare Our Plans</h1>
                <p class="fs-16px text-body text-opacity-50 text-center mb-0">
                    Compare our plans and choose the solution that best suits your business.
                    <br>All plans are designed to support your business growth and provide powerful tools for management,
                    <br>accounting, and sales.
                </p>
            </div>
            <table class="table table-bordered text-center align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>الميزة</th>
                        @foreach($plans as $plan)
                            <th>{{ $plan->name }}</th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @foreach ($plan->features as $title=>$feature)
                        @php $featureEnum = \App\Enums\PlanFeaturesEnum::from($title); @endphp
                        <tr>
                            <td class="text-muted">{{ $featureEnum->label() }}</td>
                            @foreach ($plans as $plan)
                                @php $planFeature = $plan->features[$title] ?? null; @endphp
                                @if(!($planFeature['description']??false))
                                    <td><i class="{{ $planFeature['status'] ? 'fa fa-check text-theme' : 'fa fa-times text-danger' }} fa-lg"></i></td>
                                @else
                                    <td><strong>{{ $planFeature['description'] ?? '' }}</strong></td>
                                @endif

                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


</div>

@endsection
