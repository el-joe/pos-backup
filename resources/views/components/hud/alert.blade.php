@props([
    'tone' => 'info',
    'icon' => null,
    'title' => null,
    'dismissible' => false,
])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    @php
        $map = ['primary'=>'info','success'=>'success','danger'=>'danger','warning'=>'warning','info'=>'info','secondary'=>'neutral'];
        $geminiTone = $map[$tone] ?? 'info';
    @endphp
    <x-tenant-tailwind-gemini.alert :tone="$geminiTone" :icon="$icon" :title="$title" :dismissible="$dismissible" {{ $attributes }}>
        {{ $slot }}
    </x-tenant-tailwind-gemini.alert>
@else
    @php
        $defaultIcons = ['info'=>'fa fa-info-circle','success'=>'fa fa-check-circle','warning'=>'fa fa-exclamation-triangle','danger'=>'fa fa-times-circle'];
        $resolvedIcon = $icon ?? ($defaultIcons[$tone] ?? 'fa fa-info-circle');
    @endphp
<div {{ $attributes->merge(['class' => 'alert alert-' . $tone . ' ' . ($dismissible ? 'alert-dismissible fade show' : '') . ' d-flex align-items-start gap-2']) }} role="alert">
    @if($resolvedIcon)<i class="{{ $resolvedIcon }} mt-1"></i>@endif
    <div class="flex-fill">
        @if($title)<div class="fw-semibold">{{ $title }}</div>@endif
        {{ $slot }}
    </div>
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>
@endif
