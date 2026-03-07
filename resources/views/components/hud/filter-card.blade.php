@props([
    'title',
    'icon' => 'fa fa-filter',
    'collapseId' => null,
    'collapsed' => false,
])

<div {{ $attributes->class(['card shadow-sm mb-3']) }}>
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            @if($icon)
                <i class="fa {{ $icon }} me-1"></i>
            @endif
            {{ $title }}
        </h5>

        <div class="d-flex align-items-center gap-2">
            @isset($actions)
                {{ $actions }}
            @endisset
        </div>
    </div>

    <div @if($collapseId) id="{{ $collapseId }}" class="collapse {{ $collapsed ? 'show' : '' }}" @endif>
        <div class="card-body">
            {{ $slot }}
        </div>
    </div>

    <div class="card-arrow">
        <div class="card-arrow-top-left"></div>
        <div class="card-arrow-top-right"></div>
        <div class="card-arrow-bottom-left"></div>
        <div class="card-arrow-bottom-right"></div>
    </div>
</div>
