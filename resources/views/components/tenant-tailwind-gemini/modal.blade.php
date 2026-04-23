@props([
    'name' => null,
    'title' => null,
    'icon' => null,
    'size' => 'lg',
    'wireModel' => null,
    'show' => null,
    'closeOnBackdrop' => true,
    'scrollable' => true,
    'openEvent' => null,
    'closeEvent' => null,
])

@php
    $sizes = [
        'sm'   => 'max-w-md',
        'md'   => 'max-w-lg',
        'lg'   => 'max-w-2xl',
        'xl'   => 'max-w-4xl',
        '2xl'  => 'max-w-5xl',
        'full' => 'max-w-[95vw]',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['lg'];

    // Resolve open/close event names. Explicit props win, else derive from $name.
    $resolvedOpenEvent  = $openEvent  ?: ($name ? 'modal-open-' . $name  : null);
    $resolvedCloseEvent = $closeEvent ?: ($name ? 'modal-close-' . $name : null);

    // Determine how modal open-state is bound.
    $xData = $wireModel
        ? '{ open: @entangle(\'' . $wireModel . '\') }'
        : ($resolvedOpenEvent || $resolvedCloseEvent
            ? '{ open: false }'
            : '{ open: ' . (($show === true || $show === 'true') ? 'true' : 'false') . ' }');
    $openEventAttr  = $resolvedOpenEvent  ? "@{$resolvedOpenEvent}.window=\"open = true\"" : '';
    $closeEventAttr = $resolvedCloseEvent ? "@{$resolvedCloseEvent}.window=\"open = false\"" : '';
@endphp

<div x-data="{{ $xData }}" {!! $openEventAttr !!} {!! $closeEventAttr !!} x-show="open" x-cloak x-transition.opacity
    {{ $attributes->merge(['class' => 'fixed inset-0 z-[1050] flex items-start justify-center overflow-y-auto bg-slate-900/60 px-4 py-10 backdrop-blur-sm']) }}>
    <div class="w-full {{ $sizeClass }} overflow-hidden rounded-2xl bg-white shadow-xl dark:bg-slate-900"
        @if($closeOnBackdrop) @click.outside="open = false" @endif x-transition>

        @if($title || $icon || isset($header))
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 dark:border-slate-800">
                <h3 class="flex items-center gap-2 text-lg font-semibold text-slate-900 dark:text-white">
                    @if($icon)<i class="{{ $icon }}"></i>@endif
                    @isset($header)
                        {{ $header }}
                    @else
                        {{ $title }}
                    @endisset
                </h3>
                <button type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200" @click="open = false">
                    <i class="fa fa-times fa-lg"></i>
                </button>
            </div>
        @endif

        <div class="{{ $scrollable ? 'max-h-[65vh] overflow-y-auto' : '' }} px-6 py-5">
            {{ $slot }}
        </div>

        @isset($footer)
            <div class="flex items-center justify-end gap-2 border-t border-slate-100 bg-slate-50 px-6 py-4 dark:border-slate-800 dark:bg-slate-900/60">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
