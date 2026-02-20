@extends('layouts.central.gemini.layout')

@section('content')
    <main itemscope itemtype="https://schema.org/Blog">
        @livewire('central.site.blogs-list')
    </main>
@endsection
