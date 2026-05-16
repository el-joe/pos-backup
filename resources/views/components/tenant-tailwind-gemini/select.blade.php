@props([
'options' => [],
'placeholder' => null,
'size' => 'md',
'invalid' => false,
'valueKey' => null,
'labelKey' => null,
])

@php
$sizes = [
'sm' => 'px-2.5 py-1.5 text-xs',
'md' => 'px-3 py-2 text-sm',
'lg' => 'px-4 py-2.5 text-base',
];
$base = 'w-full rounded-xl border bg-white text-slate-900 focus:outline-none focus:ring-1 dark:!bg-slate-900 dark:text-white disabled:opacity-60';
$border = $invalid
? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500 dark:border-rose-700'
: 'border-slate-200 focus:border-brand-500 focus:ring-brand-500 dark:border-slate-700';
$classes = trim($base . ' ' . $border . ' ' . ($sizes[$size] ?? $sizes['md']));
@endphp

<select {{ $attributes->merge(['class' => $classes]) }}>
    @if($placeholder !== null)
    <option value="">{{ $placeholder }}</option>
    @endif
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