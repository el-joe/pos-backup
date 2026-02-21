@php
    $isFolder = ($node['type'] ?? '') === 'folder';
    $hasChildren = $isFolder && !empty($node['children']);
    $selected = ($currentPath ?? '') === ($node['path'] ?? '');
@endphp

<div class="file-node {{ $hasChildren ? 'has-sub' : '' }} {{ $selected ? 'selected' : '' }}">
    <a href="javascript:;" class="file-link" @if($isFolder) wire:click.prevent="open(@js($node['path']))" @endif>
        <span class="file-arrow"></span>
        <span class="file-info">
            <span class="file-icon">
                @if ($isFolder)
                    <i class="fa fa-folder fa-lg text-warning"></i>
                @else
                    <i class="far fa-file fa-lg text-body text-opacity-50"></i>
                @endif
            </span>
            <span class="file-text">{{ $node['name'] }}</span>
        </span>
    </a>

    @if ($hasChildren)
        <div class="file-tree">
            @foreach ($node['children'] as $child)
                @include('livewire.central.cpanel.file-manager.partials.node', ['node' => $child, 'currentPath' => $currentPath])
            @endforeach
        </div>
    @endif
</div>
