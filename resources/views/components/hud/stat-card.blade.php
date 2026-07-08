@props([
    'label' => null,
    'value' => null,
    'icon' => null,
    'tone' => 'primary',
    'trend' => null,
    'trendDirection' => null,
])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    @php
        $geminiToneMap = ['primary'=>'brand','success'=>'emerald','danger'=>'rose','warning'=>'amber','info'=>'sky','secondary'=>'slate'];
        $geminiTone = $geminiToneMap[$tone] ?? 'brand';
    @endphp
    <x-tenant-tailwind-gemini.stat-card :label="$label" :value="$value" :icon="$icon" :tone="$geminiTone" :trend="$trend" :trendDirection="$trendDirection" {{ $attributes }}>{{ $slot }}</x-tenant-tailwind-gemini.stat-card>
@else
<div {{ $attributes->merge(['class' => 'card shadow-sm h-100']) }}>
    <div class="card-body d-flex justify-content-between align-items-start gap-3">
        <div>
            @if($label)<div class="text-muted small text-uppercase fw-semibold">{{ $label }}</div>@endif
            <div class="fs-3 fw-bold mt-2">{{ $value ?? $slot }}</div>
            @if($trend)
                <div class="small mt-1 text-{{ $trendDirection === 'down' ? 'danger' : ($trendDirection === 'up' ? 'success' : 'muted') }}">
                    @if($trendDirection === 'up')<i class="fa fa-arrow-up"></i>
                    @elseif($trendDirection === 'down')<i class="fa fa-arrow-down"></i>@endif
                    {{ $trend }}
                </div>
            @endif
        </div>
        @if($icon)
            <span class="d-inline-flex align-items-center justify-content-center rounded bg-{{ $tone }} bg-opacity-10 text-{{ $tone }}" style="width:3rem;height:3rem;">
                <i class="{{ $icon }} fa-lg"></i>
            </span>
        @endif
    </div>
    <div class="card-arrow">
        <div class="card-arrow-top-left"></div>
        <div class="card-arrow-top-right"></div>
        <div class="card-arrow-bottom-left"></div>
        <div class="card-arrow-bottom-right"></div>
    </div>
</div>
@endif
