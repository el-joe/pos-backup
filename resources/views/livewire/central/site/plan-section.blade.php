<!-- BEGIN #pricing -->
<div id="pricing" class="py-5 text-body text-opacity-75">
    <div class="container-xxl p-3 p-lg-5">
        <h1 class="mb-3 text-center">Our Pricing Plans</h1>
        <p class="fs-16px text-body text-opacity-50 text-center mb-0">Choose the perfect plan that suits your needs. <br>Our pricing is designed to be flexible and affordable, providing value for businesses of all sizes. <br>Explore our plans to find the best fit for your requirements.</p>

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

        <div class="row g-3 py-3 gx-lg-5 pt-lg-5">
            @foreach ($plans as $plan)
                <div class="col-xl-4 col-md-6 py-3 {{ $plan->recommended == 1 ? 'py-xl-0' : 'py-xl-5' }}">
                    <div class="card h-100 {{ $plan->recommended == 1 ? 'border-theme' : '' }}">
                        <div class="card-body p-30px d-flex flex-column">
                            <div class="d-flex align-items-center">
                                <div class="flex-1">
                                    <div class="h6 font-monospace">Starter Plan</div>
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
</div>
<!-- END #pricing -->
