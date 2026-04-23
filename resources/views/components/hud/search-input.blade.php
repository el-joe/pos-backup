@props(['placeholder' => null, 'clearAction' => null])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    <x-tenant-tailwind-gemini.search-input :placeholder="$placeholder" :clearAction="$clearAction" {{ $attributes }} />
@else
    <div {{ $attributes->whereStartsWith('class') }}>
        <div class="input-group">
            <span class="input-group-text"><i class="fa fa-search"></i></span>
            <input type="search" @if($placeholder) placeholder="{{ $placeholder }}" @endif {{ $attributes->except(['class', 'clearAction', 'placeholder']) }} class="form-control">
            @if($clearAction)
                <button type="button" class="btn btn-outline-secondary" wire:click="{{ $clearAction }}">
                    <i class="fa fa-times"></i>
                </button>
            @endif
        </div>
    </div>
@endif