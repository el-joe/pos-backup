@extends('layouts.central.site.layout')

@section('title', __('website.blogs.title'))

@section('content')
    <div class="py-5 bg-component">
        <div class="container-xxl p-3 p-lg-5">
            <div class="text-center mb-5">
                <h1 class="mb-3 text-center">{{ __('website.blogs.title') }}</h1>
                <p class="fs-16px text-body text-opacity-50 text-center mb-0">
                    {{ __('website.blogs.subtitle') }}
                </p>
            </div>

            @if($blogs->count())
                <div class="row g-3 g-xl-4 mb-5">
                    @foreach($blogs as $blog)
                        <div class="col-xl-3 col-lg-4 col-sm-6">
                            <a href="{{ route('blogs.show', $blog->slug) }}" class="text-decoration-none text-body">
                                <div class="card d-flex flex-column h-100 mb-5 mb-lg-0">
                                    <div class="card-body">
                                        <img
                                            src="{{ $blog->image ? asset($blog->image) : asset('hud/assets/img/landing/blog-1.jpg') }}"
                                            alt="{{ $blog->title }}"
                                            class="object-fit-cover h-200px w-100 d-block">
                                    </div>
                                    <div class="flex-1 px-3 pb-0">
                                        <h5 class="mb-2">{{ $blog->title }}</h5>
                                        <p class="mb-0">
                                            {{ \Illuminate\Support\Str::limit($blog->excerpt ?: strip_tags($blog->content), 140) }}
                                        </p>
                                    </div>
                                    <div class="p-3 pt-0 text-body text-opacity-50">
                                        {{ optional($blog->published_at ?: $blog->created_at)->format('M d, Y') }}
                                    </div>
                                    <div class="card-arrow">
                                        <div class="card-arrow-top-left"></div>
                                        <div class="card-arrow-top-right"></div>
                                        <div class="card-arrow-bottom-left"></div>
                                        <div class="card-arrow-bottom-right"></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center">
                    {{ $blogs->links() }}
                </div>
            @else
                <div class="text-center text-body text-opacity-50">
                    {{ __('website.blogs.empty') }}
                </div>
            @endif
        </div>
    </div>
@endsection
