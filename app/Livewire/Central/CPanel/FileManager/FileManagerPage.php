<?php

namespace App\Livewire\Central\CPanel\FileManager;

use App\Traits\LivewireOperations;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.cpanel')]
class FileManagerPage extends Component
{
    use LivewireOperations;

    public string $currentPath = '';

    public array $tree = [];
    public array $items = [];

    public bool $showCreateFolder = false;
    public bool $showCreateFile = false;

    public bool $showRename = false;
    public ?string $renameTargetPath = null;
    public ?string $renameTargetType = null;

    public array $rename = [
        'name' => '',
    ];

    public ?string $pendingDeletePath = null;
    public ?string $pendingDeleteType = null;

    /**
     * Livewire temporary uploads (supports multiple).
     *
     * @var array
     */
    public $uploads = [];

    public array $create = [
        'name' => '',
        'content' => '',
    ];

    public function updatedUploads(): void
    {
        $this->uploadFiles();
    }

    public function uploadFiles(): void
    {
        if (empty($this->uploads)) {
            return;
        }

        $this->validate([
            'uploads.*' => 'file|max:51200',
        ]);

        $disk = Storage::disk('public');

        foreach ((array) $this->uploads as $uploadedFile) {
            if (!$uploadedFile) {
                continue;
            }

            $originalName = method_exists($uploadedFile, 'getClientOriginalName')
                ? (string) $uploadedFile->getClientOriginalName()
                : 'file';

            $safeName = $this->sanitizeName($originalName);
            if ($safeName === '') {
                $safeName = 'file';
            }

            $finalName = $this->uniqueName($this->currentPath, $safeName);

            // storeAs takes a directory path (can be '')
            $uploadedFile->storeAs($this->currentPath, $finalName, 'public');
        }

        $this->uploads = [];
        $this->popup('success', 'Uploaded successfully');
        $this->refresh();
    }

    public function mount(?string $path = null): void
    {
        $this->currentPath = $this->normalizePath($path ?? '');
        $this->refresh();
    }

    public function refresh(): void
    {
        $disk = Storage::disk('public');

        $this->tree = $this->buildTree('');
        $this->items = $this->listItems($this->currentPath);

        if ($this->currentPath !== '' && !$this->isDirectory($this->currentPath)) {
            $this->currentPath = '';
            $this->items = $this->listItems($this->currentPath);
        }
    }

    public function open(string $path): void
    {
        $path = $this->normalizePath($path);

        if (!$this->isDirectory($path)) {
            $this->popup('error', 'Folder not found');
            return;
        }

        $this->currentPath = $path;
        $this->items = $this->listItems($this->currentPath);
    }

    public function openCreateFolder(): void
    {
        $this->resetCreate();
        $this->cancelRename();
        $this->showCreateFolder = true;
        $this->showCreateFile = false;
    }

    public function openCreateFile(): void
    {
        $this->resetCreate();
        $this->cancelRename();
        $this->showCreateFile = true;
        $this->showCreateFolder = false;
    }

    public function cancelCreate(): void
    {
        $this->resetCreate();
        $this->showCreateFolder = false;
        $this->showCreateFile = false;
    }

    public function startRename(string $path, string $type): void
    {
        $path = $this->normalizePath($path);

        if ($type === 'folder' && !$this->isDirectory($path)) {
            $this->popup('error', 'Folder not found');
            return;
        }

        if ($type === 'file' && !$this->isFile($path)) {
            $this->popup('error', 'File not found');
            return;
        }

        $this->cancelCreate();

        $this->showRename = true;
        $this->renameTargetPath = $path;
        $this->renameTargetType = $type;
        $this->rename['name'] = basename($path);
    }

    public function cancelRename(): void
    {
        $this->showRename = false;
        $this->renameTargetPath = null;
        $this->renameTargetType = null;
        $this->rename = ['name' => ''];
    }

    public function renameItem(): void
    {
        if (!$this->renameTargetPath || !$this->renameTargetType) {
            $this->popup('error', 'Nothing selected to rename');
            return;
        }

        $newName = trim((string) ($this->rename['name'] ?? ''));
        $newName = $this->sanitizeName($newName);
        if ($newName === '') {
            $this->popup('error', 'Invalid name');
            return;
        }

        $oldPath = $this->normalizePath($this->renameTargetPath);
        $type = (string) $this->renameTargetType;

        $dir = $this->normalizePath(dirname($oldPath));
        if ($dir === '.' || $dir === '/') {
            $dir = '';
        }

        $newPath = $this->joinPath($dir, $newName);

        if ($newPath === $oldPath) {
            $this->cancelRename();
            return;
        }

        $disk = Storage::disk('public');
        if ($disk->exists($newPath)) {
            $this->popup('error', 'Target name already exists');
            return;
        }

        if ($type === 'folder') {
            if (!$this->isDirectory($oldPath)) {
                $this->popup('error', 'Folder not found');
                return;
            }

            $src = $this->absPath($oldPath);
            $dst = $this->absPath($newPath);
            if (!$src || !$dst) {
                $this->popup('error', 'Rename failed');
                return;
            }

            if (!@rename($src, $dst)) {
                $this->popup('error', 'Rename failed');
                return;
            }

            // Update current path if it is inside renamed folder
            if ($this->currentPath === $oldPath) {
                $this->currentPath = $newPath;
            } elseif (Str::startsWith($this->currentPath, $oldPath . '/')) {
                $this->currentPath = $newPath . substr($this->currentPath, strlen($oldPath));
                $this->currentPath = $this->normalizePath($this->currentPath);
            }
        } else {
            if (!$this->isFile($oldPath)) {
                $this->popup('error', 'File not found');
                return;
            }

            if (!$disk->move($oldPath, $newPath)) {
                $this->popup('error', 'Rename failed');
                return;
            }
        }

        $this->popup('success', 'Renamed');
        $this->cancelRename();
        $this->refresh();
    }

    public function createFolder(): void
    {
        $name = trim((string) ($this->create['name'] ?? ''));
        if ($name === '') {
            $this->popup('error', 'Folder name is required');
            return;
        }

        $name = $this->sanitizeName($name);
        if ($name === '') {
            $this->popup('error', 'Invalid folder name');
            return;
        }

        $path = $this->joinPath($this->currentPath, $name);

        $disk = Storage::disk('public');
        if ($disk->exists($path)) {
            $this->popup('error', 'Folder already exists');
            return;
        }

        $disk->makeDirectory($path);

        $this->popup('success', 'Folder created');
        $this->cancelCreate();
        $this->refresh();
    }

    public function createFile(): void
    {
        $name = trim((string) ($this->create['name'] ?? ''));
        if ($name === '') {
            $this->popup('error', 'File name is required');
            return;
        }

        $name = $this->sanitizeName($name);
        if ($name === '' || Str::endsWith($name, '/')) {
            $this->popup('error', 'Invalid file name');
            return;
        }

        $path = $this->joinPath($this->currentPath, $name);

        $disk = Storage::disk('public');
        if ($disk->exists($path)) {
            $this->popup('error', 'File already exists');
            return;
        }

        $disk->put($path, (string) ($this->create['content'] ?? ''));

        $this->popup('success', 'File created');
        $this->cancelCreate();
        $this->refresh();
    }

    public function deleteItem(string $path, string $type): void
    {
        $path = $this->normalizePath($path);

        $this->pendingDeletePath = $path;
        $this->pendingDeleteType = $type;

        $this->confirm('deleteConfirmed', 'warning', 'Are you sure?', 'You want to delete this ' . $type, 'Yes, delete it!');
    }

    public function deleteConfirmed(): void
    {
        if (!$this->pendingDeletePath || !$this->pendingDeleteType) {
            $this->popup('error', 'Nothing selected to delete');
            return;
        }

        $this->performDelete($this->pendingDeletePath, $this->pendingDeleteType);

        $this->pendingDeletePath = null;
        $this->pendingDeleteType = null;
    }

    public function performDelete(string $path, string $type): void
    {
        $path = $this->normalizePath($path);
        $disk = Storage::disk('public');

        if ($type === 'folder') {
            if (!$this->isDirectory($path)) {
                $this->popup('error', 'Folder not found');
                return;
            }

            $disk->deleteDirectory($path);
            $this->popup('success', 'Folder deleted');
        } else {
            if (!$this->isFile($path)) {
                $this->popup('error', 'File not found');
                return;
            }

            $disk->delete($path);
            $this->popup('success', 'File deleted');
        }

        if ($this->currentPath !== '' && !$this->isDirectory($this->currentPath)) {
            $this->currentPath = '';
        }

        $this->refresh();
    }

    public function copyFileUrl(string $path): void
    {
        $path = $this->normalizePath($path);

        if (!$this->isFile($path)) {
            $this->popup('error', 'File not found');
            return;
        }

        $fullUrl = url('/storage/' . ltrim($path, '/'));

        $this->dispatch('copy-to-clipboard', ['text' => $fullUrl]);
        $this->popup('success', 'Copied');
    }

    private function resetCreate(): void
    {
        $this->create = ['name' => '', 'content' => ''];
    }

    private function buildTree(string $path): array
    {
        $disk = Storage::disk('public');

        $directories = $disk->directories($path);
        $files = $disk->files($path);

        $nodes = [];

        foreach ($directories as $dir) {
            $nodes[] = [
                'type' => 'folder',
                'name' => basename($dir),
                'path' => $dir,
                'children' => $this->buildTree($dir),
            ];
        }

        foreach ($files as $file) {
            $nodes[] = [
                'type' => 'file',
                'name' => basename($file),
                'path' => $file,
                'children' => [],
            ];
        }

        usort($nodes, function ($a, $b) {
            if ($a['type'] !== $b['type']) {
                return $a['type'] === 'folder' ? -1 : 1;
            }

            return strcasecmp($a['name'], $b['name']);
        });

        return $nodes;
    }

    private function listItems(string $path): array
    {
        $disk = Storage::disk('public');

        $path = $this->normalizePath($path);

        $items = [];

        foreach ($disk->directories($path) as $dir) {
            $items[] = [
                'type' => 'folder',
                'name' => basename($dir),
                'path' => $dir,
                'size' => null,
                'last_modified' => null,
                'mime' => 'http:/unix-directory',
            ];
        }

        foreach ($disk->files($path) as $file) {
            $mime = 'application/octet-stream';
            $abs = $this->absPath($file);
            if ($abs && is_file($abs) && function_exists('mime_content_type')) {
                $detected = @mime_content_type($abs);
                if (is_string($detected) && $detected !== '') {
                    $mime = $detected;
                }
            }

            $items[] = [
                'type' => 'file',
                'name' => basename($file),
                'path' => $file,
                'size' => $disk->size($file),
                'last_modified' => $disk->lastModified($file),
                'mime' => $mime,
            ];
        }

        usort($items, function ($a, $b) {
            if ($a['type'] !== $b['type']) {
                return $a['type'] === 'folder' ? -1 : 1;
            }

            return strcasecmp($a['name'], $b['name']);
        });

        return $items;
    }

    private function normalizePath(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        $path = trim($path);
        $path = trim($path, '/');

        $parts = array_values(array_filter(explode('/', $path), fn ($p) => $p !== '' && $p !== '.'));

        $safe = [];
        foreach ($parts as $part) {
            if ($part === '..') {
                array_pop($safe);
                continue;
            }
            $safe[] = $part;
        }

        return implode('/', $safe);
    }

    private function sanitizeName(string $name): string
    {
        $name = str_replace('\\', '/', $name);
        $name = trim($name);
        $name = trim($name, '/');

        // forbid path traversal and separators
        if ($name === '' || str_contains($name, '..') || str_contains($name, '/')) {
            return '';
        }

        return $name;
    }

    private function joinPath(string $base, string $name): string
    {
        $base = $this->normalizePath($base);

        if ($base === '') {
            return $this->normalizePath($name);
        }

        return $this->normalizePath($base . '/' . $name);
    }

    private function uniqueName(string $dir, string $fileName): string
    {
        $disk = Storage::disk('public');

        $dir = $this->normalizePath($dir);

        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $base = pathinfo($fileName, PATHINFO_FILENAME);
        $base = $base !== '' ? $base : 'file';

        $candidate = $fileName;
        $i = 1;

        while ($disk->exists($this->joinPath($dir, $candidate))) {
            $suffix = '-' . $i;
            $candidate = $ext !== '' ? ($base . $suffix . '.' . $ext) : ($base . $suffix);
            $i++;

            if ($i > 500) {
                // safety break
                $candidate = Str::random(12) . ($ext !== '' ? '.' . $ext : '');
                break;
            }
        }

        return $candidate;
    }

    private function absPath(string $path): ?string
    {
        try {
            return Storage::disk('public')->path($path);
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function isDirectory(string $path): bool
    {
        if ($path === '') {
            return true;
        }

        $abs = $this->absPath($path);
        return $abs ? is_dir($abs) : false;
    }

    private function isFile(string $path): bool
    {
        $abs = $this->absPath($path);
        return $abs ? is_file($abs) : false;
    }

    public function render()
    {
        $breadcrumbs = $this->currentPath === '' ? [] : explode('/', $this->currentPath);

        return view('livewire.central.cpanel.file-manager.file-manager', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
}
