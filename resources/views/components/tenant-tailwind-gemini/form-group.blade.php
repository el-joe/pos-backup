@props([
    'label' => null,
    'for' => null,
    'required' => false,
    'hint' => null,
    'error' => null,
    'inline' => false,
])

<div {{ $attributes->merge(['class' => 'flex ' . ($inline ? 'flex-row items-center gap-3' : 'flex-col gap-1.5')]) }}>
    @if($label)
        <label @if($for) for="{{ $for }}" @endif class="block text-sm font-medium text-slate-700 dark:text-slate-300">
            {{ $label }}
            @if($required)
                <span class="text-rose-500">*</span>
            @endif
        </label>
    @endif

    <div class="{{ $inline ? 'flex-1' : '' }}">
        {{ $slot }}
        @if($hint && !$error)
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $hint }}</p>
        @endif
        @if($error)
            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $error }}</p>
        @endif
    </div>
</div>
