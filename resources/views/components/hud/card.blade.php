@props([
    'title' => null,
    'description' => null,
    'icon' => null,
    'padded' => true,
])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    <x-tenant-tailwind-gemini.card :title="$title" :description="$description" :icon="$icon" :padded="$padded" {{ $attributes }}>
        @isset($header)<x-slot:header>{{ $header }}</x-slot:header>@endisset
        @isset($actions)<x-slot:actions>{{ $actions }}</x-slot:actions>@endisset
        {{ $slot }}
        @isset($footer)<x-slot:footer>{{ $footer }}</x-slot:footer>@endisset
    </x-tenant-tailwind-gemini.card>
@else
<div {{ $attributes->merge(['class' => 'card shadow-sm mb-3']) }}>
    @if($title || $icon || isset($header) || isset($actions))
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 d-flex align-items-center gap-2">
                @if($icon)<i class="{{ $icon }}"></i>@endif
                @isset($header){{ $header }}@else{{ $title }}@endisset
            </h5>
            @isset($actions)
                <div class="d-flex flex-wrap align-items-center gap-2">{{ $actions }}</div>
            @endisset
        </div>
    @endif
    <div class="{{ $padded ? 'card-body' : '' }}">
        @if($description && !$padded)
            <div class="px-3 pt-3 text-muted small">{{ $description }}</div>
        @elseif($description)
            <p class="text-muted small mb-3">{{ $description }}</p>
        @endif
        {{ $slot }}
    </div>
    @isset($footer)
        <div class="card-footer">{{ $footer }}</div>
    @endisset
    <div class="card-arrow">
        <div class="card-arrow-top-left"></div>
        <div class="card-arrow-top-right"></div>
        <div class="card-arrow-bottom-left"></div>
        <div class="card-arrow-bottom-right"></div>
    </div>
</div>
@endif
