@props([
    'label' => null,
    'for' => null,
    'required' => false,
    'hint' => null,
    'error' => null,
    'inline' => false,
])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    <x-tenant-tailwind-gemini.form-group :label="$label" :for="$for" :required="$required" :hint="$hint" :error="$error" :inline="$inline" {{ $attributes }}>
        {{ $slot }}
    </x-tenant-tailwind-gemini.form-group>
@else
<div {{ $attributes->merge(['class' => 'mb-3 ' . ($inline ? 'row g-2 align-items-center' : '')]) }}>
    @if($label && $inline)
        <label @if($for) for="{{ $for }}" @endif class="col-sm-3 col-form-label">
            {{ $label }} @if($required)<span class="text-danger">*</span>@endif
        </label>
        <div class="col-sm-9">
            {{ $slot }}
            @if($hint && !$error)<div class="form-text">{{ $hint }}</div>@endif
            @if($error)<div class="invalid-feedback d-block">{{ $error }}</div>@endif
        </div>
    @else
        @if($label)
            <label @if($for) for="{{ $for }}" @endif class="form-label">
                {{ $label }} @if($required)<span class="text-danger">*</span>@endif
            </label>
        @endif
        {{ $slot }}
        @if($hint && !$error)<div class="form-text">{{ $hint }}</div>@endif
        @if($error)<div class="invalid-feedback d-block">{{ $error }}</div>@endif
    @endif
</div>
@endif
