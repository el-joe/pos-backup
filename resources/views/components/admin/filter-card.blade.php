@props([
    'title',
    'icon' => 'fa fa-filter',
    'collapseId' => null,
    'collapsed' => false,
])

<div {{ $attributes->class(['white-box mb-3']) }}>
    <div class="row" style="margin-bottom:15px;">
        <div class="col-xs-6">
            <h3 class="box-title m-b-0" style="margin:0;">
                @if($icon)
                    <i class="fa {{ $icon }} m-r-5"></i>
                @endif
                {{ $title }}
            </h3>
        </div>

        <div class="col-xs-6 text-right">
            @isset($actions)
                {{ $actions }}
            @endisset
        </div>
    </div>

    <div @if($collapseId) id="{{ $collapseId }}" class="collapse{{ $collapsed ? ' in' : '' }}" @endif>
        {{ $slot }}
    </div>
</div>
