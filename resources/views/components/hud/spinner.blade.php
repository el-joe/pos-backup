@props(['size' => 'md', 'label' => null])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    <x-tenant-tailwind-gemini.spinner :size="$size" :label="$label" {{ $attributes }} />
@else
    @php $dim = ['xs' => '0.75rem', 'sm' => '1rem', 'md' => '1.5rem', 'lg' => '2.25rem'][$size] ?? '1.5rem'; @endphp
    <span {{ $attributes->merge(['class' => 'd-inline-flex align-items-center gap-2 text-muted']) }}>
        <span class="spinner-border" style="width:{{ $dim }};height:{{ $dim }};" role="status"></span>
        @if($label)<span class="small">{{ $label }}</span>@endif
    </span>
@endif