@extends('admin.layout.index')


@push('styles')
@endpush


@section('content')

@isset($id)
    @livewire($livewireView,['id'=>$id])
@else
    @livewire($livewireView)
@endisset

@endsection

@push('scripts')

@endpush
