@props([
    'title',
    'icon' => 'fa fa-table',
    'headers' => [],
    'renderTable' => true,
    'tableClass' => 'table table-bordered table-hover table-striped align-middle mb-0',
    'theadClass' => 'table-light',
])

@if(defaultLayout() === 'tenant-tailwind-gemini')
    @php
        $resolvedTableClass = collect(preg_split('/\s+/', trim($tableClass)) ?: [])
            ->reject(fn ($class) => in_array($class, ['table', 'table-bordered', 'table-hover', 'table-striped', 'align-middle', 'mb-0', 'table-light']))
            ->implode(' ');

        $resolvedTheadClass = collect(preg_split('/\s+/', trim($theadClass)) ?: [])
            ->reject(fn ($class) => in_array($class, ['table-light', 'table-primary', 'table-secondary']))
            ->implode(' ');
    @endphp

    <x-tenant-tailwind-gemini.table-card :title="$title" :icon="$icon" {{ $attributes }}>
        @isset($actions)
            <x-slot:actions>
                {{ $actions }}
            </x-slot:actions>
        @endisset

        @if(isset($head) || count($headers))
            <table class="min-w-full text-left text-sm rtl:text-right {{ $resolvedTableClass }}">
                @if(isset($head))
                    <thead class="bg-slate-50 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/70 dark:text-slate-400 {{ $resolvedTheadClass }}">
                        {{ $head }}
                    </thead>
                @elseif(count($headers))
                    <thead class="bg-slate-50 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/70 dark:text-slate-400 {{ $resolvedTheadClass }}">
                        <tr>
                            @foreach($headers as $header)
                                <th class="px-4 py-3">{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                @endif

                <tbody>
                    {{ $slot }}
                </tbody>
            </table>
        @elseif($renderTable)
            <table class="min-w-full text-left text-sm rtl:text-right {{ $resolvedTableClass }}">
                <tbody>
                    {{ $slot }}
                </tbody>
            </table>
        @else
            {{ $slot }}
        @endif

        @isset($footer)
            <x-slot:footer>
                {{ $footer }}
            </x-slot:footer>
        @endisset
    </x-tenant-tailwind-gemini.table-card>
@else
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
@endif
