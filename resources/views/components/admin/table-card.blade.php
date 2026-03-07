@props([
    'title',
    'icon' => 'fa fa-table',
    'headers' => [],
    'renderTable' => true,
])

<div {{ $attributes->class(['white-box']) }}>
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

    @if($renderTable)
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped custom-table color-table primary-table">
                @if(isset($head))
                    <thead>
                        {{ $head }}
                    </thead>
                @elseif(count($headers))
                    <thead>
                        <tr>
                            @foreach($headers as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                @endif

                <tbody>
                    {{ $slot }}
                </tbody>
            </table>
        </div>
    @else
        {{ $slot }}
    @endif

    @isset($footer)
        <div class="pagination-wrapper t-a-c">
            {{ $footer }}
        </div>
    @endisset
</div>
