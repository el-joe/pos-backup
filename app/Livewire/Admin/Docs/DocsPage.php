<?php

namespace App\Livewire\Admin\Docs;

use Livewire\Component;

class DocsPage extends Component
{
    public string $section = 'overview';

    public array $sections = [
        'overview', 'pos', 'products', 'sales', 'purchases',
        'accounting', 'hrm', 'contracting', 'settings', 'api',
    ];

    public function mount(?string $section = null): void
    {
        if ($section && in_array($section, $this->sections, true)) {
            $this->section = $section;
        }
    }

    public function selectSection(string $section): void
    {
        if (in_array($section, $this->sections, true)) {
            $this->section = $section;
        }
    }

    public function render()
    {
        return layoutView('docs.docs-page', get_defined_vars())
            ->title(__('general.titles.docs'));
    }
}
