@extends('layouts.central.site.layout')

@section('title', __('website.blogs.title'))

@section('content')
    <div class="py-5 bg-component">
        <div class="container-xxl p-3 p-lg-5">
            <header class="text-center mb-5">
                <h1 class="mb-3 text-center h1">{{ __('website.blogs.title') }}</h1>
                <p class="fs-16px text-body text-opacity-50 text-center mb-0">
                    {{ __('website.blogs.subtitle') }}
                </p>
            </header>

            @if($blogs->count())
                <div class="row g-3 g-xl-4 mb-5" itemscope itemtype="https://schema.org/ItemList">
                    @foreach($blogs as $index => $blog)
                        <div class="col-xl-3 col-lg-4 col-sm-6" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a href="{{ route('blogs.show', $blog->slug) }}"
                               class="text-decoration-none text-body d-block h-100"
                               aria-label="{{ __('website.blogs.read_more_about') }}: {{ $blog->title }}">

                                <article class="card d-flex flex-column h-100 mb-5 mb-lg-0">
                                    <div class="card-body p-0 overflow-hidden" style="flex:none!important">
                                        <img
                                            src="{{ $blog->image ? asset($blog->image_path) : asset('hud/assets/img/landing/blog-1.jpg') }}"
                                            alt="{{ $blog->title }}"
                                            {{-- Disable lazy loading for the first 4 items to improve LCP --}}
                                            @if($index > 3) loading="lazy" @endif
                                            width="400"
                                            height="200"
                                            class="object-fit-cover h-200px w-100 d-block">
                                    </div>

                                    <div class="flex-1 px-3 pt-3 pb-0">
                                        <h2 class="h5 mb-2" itemprop="name">{{ $blog->title }}</h2>
                                        <p class="mb-0 text-opacity-75 small">
                                            {{ \Illuminate\Support\Str::limit($blog->excerpt ?: strip_tags($blog->content), 140) }}
                                        </p>
                                    </div>

                                    <div class="p-3 pt-2 text-body text-opacity-50 small">
                                        <time datetime="{{ optional($blog->published_at ?: $blog->created_at)->toIso8601String() }}">
                                            {{ optional($blog->published_at ?: $blog->created_at)->format('M d, Y') }}
                                        </time>
                                    </div>

                                    <meta itemprop="position" content="{{ $index + 1 }}" />
                                    <meta itemprop="url" content="{{ route('blogs.show', $blog->slug) }}" />

                                    <div class="card-arrow" aria-hidden="true">
                                        <div class="card-arrow-top-left"></div>
                                        <div class="card-arrow-top-right"></div>
                                        <div class="card-arrow-bottom-left"></div>
                                        <div class="card-arrow-bottom-right"></div>
                                    </div>
                                </article>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $blogs->links() }}
                </div>
            @else
                <div class="text-center py-5 text-body text-opacity-50">
                    <iconify-icon icon="solar:document-text-outline" class="display-1 mb-3 opacity-25"></iconify-icon>
                    <p>{{ __('website.blogs.empty') }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection
