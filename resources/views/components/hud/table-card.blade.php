@props([
    'title',
    'icon' => 'fa fa-table',
    'headers' => [],
    'renderTable' => true,
    'tableClass' => 'table table-bordered table-hover table-striped align-middle mb-0',
    'theadClass' => 'table-light',
])

<div {{ $attributes->class(['card shadow-sm']) }}>
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            @if($icon)
                <i class="fa {{ $icon }} me-1"></i>
            @endif
            {{ $title }}
        </h5>

        <div class="d-flex align-items-center gap-2">
            @isset($actions)
                {{ $actions }}
            @endisset
        </div>
    </div>

    <div class="card-body">
        @if($renderTable)
            <div class="table-responsive">
                <table class="{{ $tableClass }}">
                    @if(isset($head))
                        <thead class="{{ $theadClass }}">
                            {{ $head }}
                        </thead>
                    @elseif(count($headers))
                        <thead class="{{ $theadClass }}">
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
    </div>

    @isset($footer)
        <div class="card-body border-top d-flex justify-content-center">
            {{ $footer }}
        </div>
    @endisset

    <div class="card-arrow">
        <div class="card-arrow-top-left"></div>
        <div class="card-arrow-top-right"></div>
        <div class="card-arrow-bottom-left"></div>
        <div class="card-arrow-bottom-right"></div>
    </div>
</div>
