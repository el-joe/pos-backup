@push('styles')
    <link href="/hud/assets/plugins/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
@endpush

<div id="fileManager" class="col-12">
    <h1 class="page-header">{{ __('general.titles.file_manager') }}</h1>

    <div class="card flex-1 m-0 d-flex flex-column overflow-hidden rounded-0">
        <div class="card-header fw-bold small d-flex">
            <span class="flex-grow-1">{{ __('general.titles.file_manager') }}</span>
            <a href="#" data-toggle="card-expand" class="text-white text-opacity-50 text-decoration-none"><i class="fa fa-fw fa-expand"></i> EXPAND</a>
        </div>

        <div class="card-body p-0 flex-1 overflow-hidden">
            <div class="file-manager h-100">
                <div class="file-manager-toolbar">
                    <button type="button" class="btn border-0" wire:click="openCreateFile"><i class="bi bi-plus-lg me-1"></i> File</button>
                    <button type="button" class="btn border-0" wire:click="openCreateFolder"><i class="bi bi-plus-lg me-1"></i> Folder</button>
                    <label for="fmUpload" class="btn border-0 mb-0">
                        <i class="bi me-1 bi-upload"></i> Upload
                    </label>
                    <input id="fmUpload" type="file" class="d-none" wire:model="uploads" multiple>
                    <button type="button" class="btn border-0" wire:click="refresh"><i class="bi me-1 bi-arrow-clockwise"></i> Refresh</button>
                </div>

                <div class="file-manager-container">
                    <div class="file-manager-sidebar">
                        <div class="file-manager-sidebar-content">
                            <div data-scrollbar="true" data-height="100%" class="p-3">
                                <div class="file-tree mb-3">
                                    <div class="file-node has-sub expand {{ $currentPath === '' ? 'selected' : '' }}">
                                        <a href="javascript:;" class="file-link" wire:click.prevent="open('')">
                                            <span class="file-arrow"></span>
                                            <span class="file-info">
                                                <span class="file-icon"><i class="fa fa-folder fa-lg text-warning"></i></span>
                                                <span class="file-text">storage/public</span>
                                            </span>
                                        </a>

                                        <div class="file-tree">
                                            @foreach ($tree as $node)
                                                @include('livewire.central.cpanel.file-manager.partials.node', ['node' => $node, 'currentPath' => $currentPath])
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="file-manager-content d-flex flex-column">
                        <div class="mb-0 d-flex flex-wrap text-nowrap px-10px pt-10px pb-0 border-bottom align-items-center">
                            <button type="button" class="btn btn-sm btn-default me-2 mb-10px px-2" wire:click="open('')"><i class="fa fa-fw fa-home"></i></button>

                            <div class="me-2 mb-10px small text-body text-opacity-75">
                                /{{ $currentPath }}
                            </div>
                        </div>

                        <div class="flex-1 overflow-hidden">
                            <div data-scrollbar="true" data-skip-mobile="true" data-height="100%" class="p-0">
                                <table class="table table-striped table-borderless table-sm m-0 text-nowrap small">
                                    <thead>
                                        <tr class="border-bottom">
                                            <th class="w-10px ps-10px"></th>
                                            <th class="px-10px">Name</th>
                                            <th class="px-10px w-100px">Size</th>
                                            <th class="px-10px w-200px">Last Modified</th>
                                            <th class="px-10px w-200px">Type</th>
                                            <th class="px-10px w-120px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr>
                                                <td class="ps-10px border-0 text-center">
                                                    @if ($item['type'] === 'folder')
                                                        <i class="fa fa-folder text-warning fa-lg"></i>
                                                    @else
                                                        <i class="far fa-file text-body text-opacity-50 fa-lg"></i>
                                                    @endif
                                                </td>
                                                <td class="px-10px border-0">
                                                    @if ($item['type'] === 'folder')
                                                        <a href="javascript:;" class="text-decoration-none" wire:click.prevent="open(@js($item['path']))">
                                                            {{ $item['name'] }}
                                                        </a>
                                                    @else
                                                        {{ $item['name'] }}
                                                    @endif
                                                </td>
                                                <td class="px-10px">
                                                    @if ($item['type'] === 'folder')
                                                        -
                                                    @else
                                                        {{ number_format(($item['size'] ?? 0) / 1024, 1) }} KB
                                                    @endif
                                                </td>
                                                <td class="px-10px">
                                                    @if ($item['type'] === 'file' && $item['last_modified'])
                                                        {{ \Carbon\Carbon::createFromTimestamp($item['last_modified'])->format('Y-m-d H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="px-10px">{{ $item['mime'] }}</td>
                                                <td class="px-10px">
                                                    @if ($item['type'] === 'file')
                                                        <button class="btn btn-xs btn-default me-1" type="button"
                                                            wire:click="copyFileUrl(@js($item['path']))" title="Copy URL">
                                                            <i class="bi bi-clipboard"></i>
                                                        </button>
                                                    @endif
                                                    <button class="btn btn-xs btn-default me-1" type="button"
                                                        wire:click="startRename(@js($item['path']), @js($item['type']))" title="Rename">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <button class="btn btn-xs btn-danger" type="button"
                                                        wire:click="deleteItem(@js($item['path']), @js($item['type']))">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($showRename)
                            <div class="border-top p-3">
                                <div class="row g-2 align-items-start">
                                    <div class="col-md-8">
                                        <input type="text" class="form-control form-control-sm" placeholder="New name" wire:model.defer="rename.name">
                                    </div>
                                    <div class="col-md-4 d-flex gap-2 justify-content-end">
                                        <button class="btn btn-sm btn-theme" type="button" wire:click="renameItem">Rename</button>
                                        <button class="btn btn-sm btn-default" type="button" wire:click="cancelRename">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($showCreateFolder || $showCreateFile)
                            <div class="border-top p-3">
                                <div class="row g-2 align-items-start">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control form-control-sm" placeholder="Name" wire:model.defer="create.name">
                                    </div>

                                    @if ($showCreateFile)
                                        <div class="col-md-6">
                                            <textarea class="form-control form-control-sm" rows="2" placeholder="File content (optional)" wire:model.defer="create.content"></textarea>
                                        </div>
                                    @else
                                        <div class="col-md-6"></div>
                                    @endif

                                    <div class="col-md-2 d-flex gap-2">
                                        @if ($showCreateFolder)
                                            <button class="btn btn-sm btn-theme" type="button" wire:click="createFolder">Create</button>
                                        @else
                                            <button class="btn btn-sm btn-theme" type="button" wire:click="createFile">Create</button>
                                        @endif
                                        <button class="btn btn-sm btn-default" type="button" wire:click="cancelCreate">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('copy-to-clipboard', async (event) => {
            const text = event?.detail?.[0]?.text;
            if (!text) return;

            try {
                if (navigator?.clipboard?.writeText) {
                    await navigator.clipboard.writeText(text);
                } else {
                    const el = document.createElement('textarea');
                    el.value = text;
                    document.body.appendChild(el);
                    el.select();
                    document.execCommand('copy');
                    document.body.removeChild(el);
                }
            } catch (e) {
                console.error('Copy failed', e);
            }
        });
    </script>
@endpush
