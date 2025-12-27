@extends('layouts.central.site.layout')

@section('title', __('website.faqs.title'))

@section('content')
    <div class="py-5 bg-component">
        <div class="container-xxl p-3 p-lg-5">
            <header class="text-center mb-5">
                <h1 class="mb-3 text-center h1">{{ __('website.faqs.title') }}</h1>
                <p class="fs-16px text-body text-opacity-50 text-center mb-0">
                    {{ __('website.faqs.subtitle') }}
                </p>
            </header>

            @if($faqs->count())
                <div class="accordion" id="faqAccordion" itemscope itemtype="https://schema.org/FAQPage">
                    @foreach($faqs as $index => $faq)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHeading{{ $faq->id }}" itemprop="mainEntity" itemscope itemtype="https://schema.org/Question">
                                <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#faqCollapse{{ $faq->id }}"
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-controls="faqCollapse{{ $faq->id }}">
                                    <span itemprop="name">{{ $faq->question }}</span>
                                </button>
                            </h2>
                            <div id="faqCollapse{{ $faq->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                 aria-labelledby="faqHeading{{ $faq->id }}" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" itemprop="acceptedAnswer" itemscope itemtype="https://schema.org/Answer">
                                    <div class="fs-16px text-body text-opacity-75" itemprop="text">
                                        {!! $faq->answer !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5 text-body text-opacity-50">
                    <iconify-icon icon="solar:chat-round-line-outline" class="display-1 mb-3 opacity-25"></iconify-icon>
                    <p class="mb-0">{{ __('website.faqs.empty') }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection
