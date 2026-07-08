@props([
    'icon' => 'fa fa-inbox',
    'title' => null,
    'description' => null,
])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    <x-tenant-tailwind-gemini.empty-state :icon="$icon" :title="$title" :description="$description" {{ $attributes }}>
        {{ $slot }}
    </x-tenant-tailwind-gemini.empty-state>
@else
<div {{ $attributes->merge(['class' => 'd-flex flex-column align-items-center justify-content-center text-center p-5 gap-2']) }}>
    <span class="d-inline-flex align-items-center justify-content-center rounded bg-body-tertiary text-muted" style="width:4rem;height:4rem;">
        <i class="{{ $icon }} fa-lg"></i>
    </span>
    @if($title)<h6 class="mb-0">{{ $title }}</h6>@endif
    @if($description)<p class="text-muted small mb-0" style="max-width:32rem;">{{ $description }}</p>@endif
    @if(trim($slot))<div class="mt-2">{{ $slot }}</div>@endif
</div>
@endif
