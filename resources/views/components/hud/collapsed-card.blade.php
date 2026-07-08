@props([
    'title' => null,
    'description' => null,
    'icon' => null,
    'expanded' => false,
    'collapseId' => null,
])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    <x-tenant-tailwind-gemini.collapsed-card :title="$title" :description="$description" :icon="$icon" :expanded="$expanded" {{ $attributes }}>
        @isset($actions)
            <x-slot:actions>{{ $actions }}</x-slot:actions>
        @endisset
        {{ $slot }}
    </x-tenant-tailwind-gemini.collapsed-card>
@else
    @php $cid = $collapseId ?? ('collapse-' . \Illuminate\Support\Str::random(6)); @endphp
<div {{ $attributes->merge(['class' => 'card shadow-sm mb-3']) }}>
    <div class="card-header d-flex justify-content-between align-items-center" role="button"
         data-bs-toggle="collapse" data-bs-target="#{{ $cid }}" aria-expanded="{{ $expanded ? 'true' : 'false' }}">
        <h6 class="mb-0 d-flex align-items-center gap-2">
            @if($icon)<i class="{{ $icon }}"></i>@endif
            <span>{{ $title }}</span>
            @if($description)<small class="text-muted">{{ $description }}</small>@endif
        </h6>
        <div class="d-flex align-items-center gap-2">
            @isset($actions){{ $actions }}@endisset
            <i class="fa fa-chevron-down small"></i>
        </div>
    </div>
    <div id="{{ $cid }}" class="collapse {{ $expanded ? 'show' : '' }}">
        <div class="card-body">{{ $slot }}</div>
    </div>
    <div class="card-arrow">
        <div class="card-arrow-top-left"></div>
        <div class="card-arrow-top-right"></div>
        <div class="card-arrow-bottom-left"></div>
        <div class="card-arrow-bottom-right"></div>
    </div>
</div>
@endif
