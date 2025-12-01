<div>
    <!-- BEGIN page-header -->
    <h1 class="page-header">Pricing Page</h1>

<div class="d-flex justify-content-center mb-4">
    <div class="form-check form-switch">
        <input
            class="form-check-input"
            type="checkbox"
            id="planToggleSwitch"
            wire:model.live="yearly"
        >
        <label class="form-check-label ms-2" for="planToggleSwitch">
            <span class="{{ $yearly ? 'fw-bold text-primary' : '' }}">Yearly Billing</span>
        </label>
    </div>
</div>

    <!-- END page-header -->
    <div class="row gx-4 py-5">
        @foreach ($plans as $plan)
            <div class="col-xl-4 col-md-6 py-3 {{ $plan->recommended == 1 ? 'py-xl-0' : 'py-xl-5' }}">
                <div class="card h-100 {{ $plan->recommended == 1 ? 'border-theme' : '' }}">
                    <div class="card-body p-30px d-flex flex-column">
                        <div class="d-flex align-items-center">
                            <div class="flex-1">
                                <div class="h6 font-monospace">{{ $plan->name }} Plan</div>
                                <div class="display-6 fw-bold mb-0">${{ $plan->{"price_".$period} }}<small
                                        class="h6 text-body text-opacity-50">/{{ $period }}</small></div>
                            </div>
                            <div>
								<span class="{{ $plan->icon }} display-4 text-body text-opacity-50 rounded-3"></span>
                            </div>
                        </div>
                        <hr class="my-20px" />
                        <div class="mb-5 text-body text-opacity-75 flex-1">
                            @foreach ($plan->features as $title=>$feature)
                                @php $featureEnum = \App\Enums\PlanFeaturesEnum::from($title); @endphp
                                <div class="d-flex align-items-center mb-1">
                                    <i class="{{ $feature['status'] ? 'fa fa-check text-theme' : 'fa fa-times text-body text-opacity-25' }} fa-lg"></i>
                                    <div class="flex-1 ps-3">
                                        <span class="font-monospace small">{{ $featureEnum->label() }} {{ $feature['description']??false ? ':' : '' }}</span>
                                        @if($feature['description']??false)
                                            <b class="text-body">{{ $feature['description'] }}</b>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mx-n2">
                            <a href="#" class="btn {{ $plan->recommended == 1 ? 'btn-theme text-black' : 'btn-outline-default' }}  btn-lg w-100 font-monospace">Get Started <i
                                    class="fa fa-arrow-right"></i></a>
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
        @endforeach
    </div>
</div>

@push('scripts')
	<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
@endpush
