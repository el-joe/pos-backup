@extends('layouts.central.site.layout')

@section('title', $blog->title)

@section('content')
    <div class="py-5 bg-component">
        <div class="container-xxl p-3 p-lg-5">
            <div class="mb-4">
                <a href="{{ route('blogs.index') }}" class="text-decoration-none text-body text-opacity-50 h6">
                    <i class="fa fa-arrow-left me-2"></i>{{ __('website.blogs.back_to_all') }}
                </a>
            </div>

            <h1 class="mb-2">{{ $blog->title }}</h1>
            <div class="text-body text-opacity-50 mb-4">
                {{ optional($blog->published_at ?: $blog->created_at)->translatedFormat('M d, Y') }}
            </div>

            @if($blog->image)
                <div class="mb-4">
                    <img
                        src="{{ asset($blog->image_path) }}"
                        alt="{{ $blog->title }}"
                        class="w-100 d-block object-fit-cover rounded">
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="fs-16px text-body text-opacity-75">
                        {!! $blog->content !!}
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
    </div>
@endsection
