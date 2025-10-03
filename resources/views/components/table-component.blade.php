<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped custom-table color-table primary-table">
        <thead>
            <tr>
                @foreach ($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    @foreach ($row as $key=>$value)
                        @continue(!isset($columns[$key]))
                        @if($columns[$key]['type'] == 'text' || $columns[$key]['type'] == 'number')
                            <td>{{ $value }}</td>
                        @elseif($columns[$key]['type'] == 'decimal')
                            <td>{{ number_format($value, 2) }}</td>
                        @elseif($columns[$key]['type'] == 'boolean')
                            <td>
                                <span class="badge badge-{{ $value ? 'success' : 'danger' }}">
                                    {{ $value ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        @elseif($columns[$key]['type'] == 'date')
                            <td>{{ carbon($value)->format('l ,d M Y') }}</td>
                        @elseif($columns[$key]['type'] == 'datetime')
                            <td>{{ carbon($value)->format('l ,d M Y H:i A') }}</td>
                        @else
                            <td>-----</td>
                        @endif
                    @endforeach
                    <td>
                        @foreach ($columns['actions']['actions'] ?? [] as $action)
                            @php
                                $params = collect($action['params'] ?? [])->map(fn($p)=> $row[$p])->implode('\', \'');
                                $params = count($action['params'] ?? []) > 0 ? "'$params'" : '';

                                // Check if action should be hidden
                                $shouldHide = false;
                                if (isset($action['hide']) && is_callable($action['hide'])) {
                                    $shouldHide = $action['hide']($row);
                                }
                            @endphp

                            @if(!$shouldHide)
                                <a
                                    href="{{ $action['route'] ?? '#' }}"
                                    @if($action['wire:click'])
                                    @if((isset($action['disabled']) && is_callable($action['disabled']) && !$action['disabled']($row) || !isset($action['disabled'])))
                                    wire:click="{{ ($action['wire:click'])($row) }}"
                                    @endif
                                    @endif
                                    class="{{ $action['class'] ?? '' }} text-nowrap"
                                    @if(isset($action['disabled']) && is_callable($action['disabled']) && $action['disabled']($row)) disabled  @endif
                                    @foreach($action['attributes'] ?? [] as $attr => $val)
                                        {{ $attr }}="{{ is_callable($val) ? ($val)($row) : $val }}"
                                    @endforeach
                                >
                                    <i class="{{ $action['icon'] ?? '' }}"></i>
                                </a>
                            @endif
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- center pagination --}}
    <div class="pagination-wrapper t-a-c">
        {{ $rows->links() }}
    </div>
</div>
