@props([
    'title' => null,
    'description' => null,
    'icon' => null,
    'breadcrumbs' => [],
])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    <x-tenant-tailwind-gemini.page-header :title="$title" :description="$description" :icon="$icon" :breadcrumbs="$breadcrumbs" {{ $attributes }}>
        @isset($actions)
            <x-slot:actions>{{ $actions }}</x-slot:actions>
        @endisset
    </x-tenant-tailwind-gemini.page-header>
@else
<div {{ $attributes->merge(['class' => 'd-flex flex-wrap justify-content-between align-items-start mb-3 gap-3']) }}>
    <div class="d-flex align-items-start gap-3">
        @if($icon)
            <span class="d-inline-flex align-items-center justify-content-center rounded bg-primary bg-opacity-10 text-primary" style="width:3rem;height:3rem;">
                <i class="{{ $icon }} fa-lg"></i>
            </span>
        @endif
        <div>
            @if(!empty($breadcrumbs))
                <nav aria-label="breadcrumb" class="small mb-1">
                    <ol class="breadcrumb mb-0">
                        @foreach($breadcrumbs as $crumb)
                            @if(is_array($crumb) && !empty($crumb['href']))
                                <li class="breadcrumb-item"><a href="{{ $crumb['href'] }}">{{ $crumb['label'] ?? '' }}</a></li>
                            @else
                                <li class="breadcrumb-item active">{{ is_array($crumb) ? ($crumb['label'] ?? '') : $crumb }}</li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
            @endif
            @if($title)<h4 class="mb-1 fw-bold">{{ $title }}</h4>@endif
            @if($description)<p class="text-muted small mb-0">{{ $description }}</p>@endif
        </div>
    </div>
    @isset($actions)
        <div class="d-flex flex-wrap align-items-center gap-2">{{ $actions }}</div>
    @endisset
</div>
@endif
