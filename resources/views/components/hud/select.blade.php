@props([
    'options' => [],
    'placeholder' => null,
    'size' => 'md',
    'invalid' => false,
    'valueKey' => null,
    'labelKey' => null,
])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    <x-tenant-tailwind-gemini.select :options="$options" :placeholder="$placeholder" :size="$size" :invalid="$invalid" :valueKey="$valueKey" :labelKey="$labelKey" {{ $attributes }}>
        {{ $slot }}
    </x-tenant-tailwind-gemini.select>
@else
    @php
        $sizeClass = $size === 'sm' ? 'form-select-sm' : ($size === 'lg' ? 'form-select-lg' : '');
        $classes = 'form-select ' . $sizeClass . ($invalid ? ' is-invalid' : '');
    @endphp
<select {{ $attributes->merge(['class' => $classes]) }}>
    @if($placeholder !== null)<option value="">{{ $placeholder }}</option>@endif
    @if(!empty($options))
        @foreach($options as $key => $option)
            @php
                if (is_array($option) || is_object($option)) {
                    $val = $valueKey ? data_get($option, $valueKey) : (is_object($option) ? ($option->id ?? $key) : ($option['value'] ?? $key));
                    $lbl = $labelKey ? data_get($option, $labelKey) : (is_object($option) ? ($option->name ?? '') : ($option['label'] ?? $option));
                } else {
                    $val = $key;
                    $lbl = $option;
                }
            @endphp
            <option value="{{ $val }}">{{ $lbl }}</option>
        @endforeach
    @else
        {{ $slot }}
    @endif
</select>
@endif
