@extends('layouts.central.site.layout')

@section('title', $page->title)

@section('content')
    <div class="py-5 bg-component">
        <div class="container-xxl p-3 p-lg-5">
            <h1 class="mb-3">{{ $page->title }}</h1>

            @if($page->short_description)
                <p class="fs-16px text-body text-opacity-50 mb-4">{{ $page->short_description }}</p>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="fs-16px text-body text-opacity-75">
                        {!! $page->content !!}
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
