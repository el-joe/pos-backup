@props([
    'id' => null,
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

@if(defaultLayout() === 'tenant-tailwind-gemini')
        <x-tenant-tailwind-gemini.modal :name="$name" :title="$title" :icon="$icon" :size="$size" :wireModel="$wireModel" :show="$show" :closeOnBackdrop="$closeOnBackdrop" :scrollable="$scrollable" :openEvent="$openEvent" :closeEvent="$closeEvent" {{ $attributes }}>
            @isset($header)<x-slot:header>{{ $header }}</x-slot:header>@endisset
            {{ $slot }}
        @isset($footer)<x-slot:footer>{{ $footer }}</x-slot:footer>@endisset
    </x-tenant-tailwind-gemini.modal>
@else
    @php
        $sizeClass = ['sm' => 'modal-sm', 'md' => '', 'lg' => 'modal-lg', 'xl' => 'modal-xl', '2xl' => 'modal-xl', 'full' => 'modal-fullscreen'][$size] ?? 'modal-lg';
        $modalId = $id ?? ($name ? 'modal-' . $name : 'modal-' . \Illuminate\Support\Str::random(6));
    @endphp
    <div {{ $attributes->merge(['class' => 'modal fade', 'id' => $modalId, 'tabindex' => '-1', 'aria-hidden' => 'true']) }}
         @if(!$closeOnBackdrop) data-bs-backdrop="static" @endif>
        <div class="modal-dialog {{ $sizeClass }} {{ $scrollable ? 'modal-dialog-scrollable' : '' }} modal-dialog-centered">
        <div class="modal-content">
                @if($title || $icon || isset($header))
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center gap-2">
                            @if($icon)<i class="{{ $icon }}"></i>@endif
                            @isset($header){{ $header }}@else{{ $title }}@endisset
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                @endif
                <div class="modal-body">{{ $slot }}</div>
                @isset($footer)
                    <div class="modal-footer">{{ $footer }}</div>
                @endisset
            </div>
        </div>
    </div>
@endif
