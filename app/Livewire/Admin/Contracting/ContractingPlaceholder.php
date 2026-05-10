<?php

namespace App\Livewire\Admin\Contracting;

use Livewire\Component;

abstract class ContractingPlaceholder extends Component
{
    /** Permission to check (e.g. "contracting_tenders.list"). */
    protected string $permission = '';

    /** View path relative to livewire.{layout} (e.g. "contracting.tenders.tenders-list"). */
    protected string $viewPath = '';

    /** Translation key for the page title (e.g. "general.titles.contracting_tenders"). */
    protected string $titleKey = '';

    /** Translation key for the module/entity description (e.g. "general.pages.contracting.tenders.title"). */
    protected string $entityKey = '';

    public function mount(): void
    {
        if ($this->permission && !adminCan($this->permission)) {
            abort(403);
        }
    }

    public function render()
    {
        $titleKey = $this->titleKey;
        $entityKey = $this->entityKey;

        return layoutView($this->viewPath, get_defined_vars())
            ->title(__($this->titleKey));
    }
}
