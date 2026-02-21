<?php

namespace App\Livewire\Central\CPanel\Translations;

use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.cpanel')]
class TranslationsEditor extends Component
{
    use LivewireOperations;

    #[Url(as: 'file')]
    public string $file = '';

    /** @var array<int, string> */
    public array $locales = [];

    /** @var array<int, string> */
    public array $files = [];

    /**
     * Each row: ['key' => string, 'values' => [locale => string]]
     *
     * @var array<int, array{key:string,values:array<string,string>}>
     */
    public array $rows = [];

    /** @var array<string, array> */
    private array $originalByLocale = [];

    public function mount(): void
    {
        $this->locales = $this->discoverLocales();
        $this->files = $this->discoverFiles($this->locales);

        if ($this->file === '' && !empty($this->files)) {
            $this->file = $this->files[0];
        }

        $this->loadSelectedFile();
    }

    public function updatedFile(): void
    {
        $this->loadSelectedFile();
    }

    public function save(): void
    {
        if ($this->file === '') {
            $this->popup('error', 'No file selected');
            return;
        }

        $prefix = $this->filePrefix();

        foreach ($this->locales as $locale) {
            $data = $this->originalByLocale[$locale] ?? [];

            foreach ($this->rows as $row) {
                $fullKey = (string)($row['key'] ?? '');
                $relativeKey = $this->relativeKey($fullKey, $prefix);
                if ($relativeKey === null || $relativeKey === '') {
                    continue;
                }

                $segments = explode('.', $relativeKey);
                $newValue = (string)($row['values'][$locale] ?? '');

                $oldValue = $this->getBySegments($this->originalByLocale[$locale] ?? [], $segments);
                $castedValue = $this->castToOriginalType($oldValue, $newValue);

                $this->setBySegments($data, $segments, $castedValue);
            }

            $filePath = lang_path($locale . DIRECTORY_SEPARATOR . $this->file);
            File::ensureDirectoryExists(dirname($filePath));

            $php = "<?php\n\nreturn " . var_export($data, true) . ";\n";
            File::put($filePath, $php);
        }

        $this->popup('success', 'Translations saved successfully');

        $this->loadSelectedFile();
    }

    public function render()
    {
        return view('livewire.central.cpanel.translations.translations-editor');
    }

    /** @return array<int, string> */
    private function discoverLocales(): array
    {
        $base = lang_path();
        if (!is_dir($base)) {
            return [];
        }

        $dirs = File::directories($base);
        $locales = [];
        foreach ($dirs as $dir) {
            $name = basename($dir);
            if ($name === '' || str_starts_with($name, '.')) {
                continue;
            }
            $locales[] = $name;
        }

        sort($locales);
        return $locales;
    }

    /**
     * Build union of translation PHP files across locales (relative paths, e.g. 'auth.php' or 'vendor/foo.php').
     *
     * @param array<int, string> $locales
     * @return array<int, string>
     */
    private function discoverFiles(array $locales): array
    {
        $files = [];

        foreach ($locales as $locale) {
            $localeDir = lang_path($locale);
            if (!is_dir($localeDir)) {
                continue;
            }

            foreach (File::allFiles($localeDir) as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $relative = str_replace($localeDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $relative = str_replace(DIRECTORY_SEPARATOR, '/', $relative);
                $files[$relative] = true;
            }
        }

        $result = array_keys($files);
        sort($result);
        return $result;
    }

    private function loadSelectedFile(): void
    {
        $this->rows = [];
        $this->originalByLocale = [];

        if ($this->file === '') {
            return;
        }

        $prefix = $this->filePrefix();

        $flatByLocale = [];
        foreach ($this->locales as $locale) {
            $path = lang_path($locale . DIRECTORY_SEPARATOR . $this->file);

            $data = [];
            if (File::exists($path)) {
                $loaded = include $path;
                $data = is_array($loaded) ? $loaded : [];
            }

            $this->originalByLocale[$locale] = $data;
            $flatByLocale[$locale] = $this->flattenToFullKeys($data, $prefix);
        }

        $allKeys = [];
        foreach ($flatByLocale as $flat) {
            foreach (array_keys($flat) as $key) {
                $allKeys[$key] = true;
            }
        }

        $keys = array_keys($allKeys);
        sort($keys);

        foreach ($keys as $key) {
            $values = [];
            foreach ($this->locales as $locale) {
                $val = $flatByLocale[$locale][$key] ?? '';
                $values[$locale] = $this->stringify($val);
            }

            $this->rows[] = [
                'key' => $key,
                'values' => $values,
            ];
        }
    }

    private function filePrefix(): string
    {
        $file = str_replace('\\', '/', $this->file);
        $file = preg_replace('/\.php$/', '', $file) ?? $file;
        return str_replace('/', '.', $file);
    }

    private function relativeKey(string $fullKey, string $prefix): ?string
    {
        $needle = $prefix . '.';
        if (!str_starts_with($fullKey, $needle)) {
            return null;
        }
        return substr($fullKey, strlen($needle));
    }

    /**
     * Flatten nested translation array into [fullKey => scalar|null].
     *
     * @param array $data
     * @return array<string, mixed>
     */
    private function flattenToFullKeys(array $data, string $prefix): array
    {
        $out = [];

        $walk = function ($value, string $path) use (&$walk, &$out, $prefix) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $segment = (string)$k;
                    $nextPath = $path === '' ? $segment : ($path . '.' . $segment);
                    $walk($v, $nextPath);
                }
                return;
            }

            if ($path === '') {
                // Edge case: file returns scalar instead of array.
                $out[$prefix] = $value;
                return;
            }

            $out[$prefix . '.' . $path] = $value;
        };

        $walk($data, '');

        return $out;
    }

    private function stringify(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_int($value) || is_float($value)) {
            return (string)$value;
        }

        if (is_string($value)) {
            return $value;
        }

        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '';
    }

    private function castToOriginalType(mixed $oldValue, string $newValue): mixed
    {
        if (is_int($oldValue)) {
            return (int)$newValue;
        }

        if (is_float($oldValue)) {
            return (float)$newValue;
        }

        if (is_bool($oldValue)) {
            $normalized = strtolower(trim($newValue));
            return in_array($normalized, ['1', 'true', 'yes', 'on'], true);
        }

        if ($oldValue === null) {
            return $newValue === '' ? null : $newValue;
        }

        return $newValue;
    }

    /**
     * @param array $array
     * @param array<int, string> $segments
     */
    private function getBySegments(array $array, array $segments): mixed
    {
        $ref = $array;
        foreach ($segments as $seg) {
            $key = ctype_digit($seg) ? (int)$seg : $seg;
            if (!is_array($ref) || !array_key_exists($key, $ref)) {
                return null;
            }
            $ref = $ref[$key];
        }

        return $ref;
    }

    /**
     * @param array $array
     * @param array<int, string> $segments
     */
    private function setBySegments(array &$array, array $segments, mixed $value): void
    {
        $ref =& $array;
        $count = count($segments);

        for ($i = 0; $i < $count; $i++) {
            $seg = $segments[$i];
            $key = ctype_digit($seg) ? (int)$seg : $seg;

            if ($i === $count - 1) {
                $ref[$key] = $value;
                return;
            }

            if (!isset($ref[$key]) || !is_array($ref[$key])) {
                $ref[$key] = [];
            }

            $ref =& $ref[$key];
        }
    }
}
