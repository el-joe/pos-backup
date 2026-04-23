<?php

namespace App\Livewire\Admin\Contracting\Tenders;

use App\Livewire\Admin\Contracting\ContractingCrudComponent;
use App\Models\Tenant\Contracting\Tender;

class TendersList extends ContractingCrudComponent
{
    protected string $modelClass = Tender::class;
    protected string $permission = 'contracting_tenders';
    protected string $viewPath = 'contracting.tenders.tenders-list';
    protected string $titleKey = 'general.titles.contracting_tenders';
    protected string $entityKey = 'general.pages.contracting.tenders';
    protected string $icon = 'fa fa-gavel';

    protected function listColumns(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code'],
            ['field' => 'name', 'label_key' => 'name'],
            ['field' => 'submission_date', 'label_key' => 'submission_date', 'type' => 'date'],
            ['field' => 'estimated_value', 'label_key' => 'estimated_value', 'type' => 'decimal'],
            ['field' => 'status', 'label_key' => 'status'],
        ];
    }

    protected function formFields(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code', 'type' => 'text', 'required' => true],
            ['field' => 'name', 'label_key' => 'name', 'type' => 'text', 'required' => true],
            ['field' => 'submission_date', 'label_key' => 'submission_date', 'type' => 'date'],
            ['field' => 'opening_date', 'label_key' => 'opening_date', 'type' => 'date'],
            ['field' => 'estimated_value', 'label_key' => 'estimated_value', 'type' => 'decimal'],
            ['field' => 'status', 'label_key' => 'status', 'type' => 'select', 'options' => ['draft' => 'general.pages.contracting.statuses.draft', 'open' => 'general.pages.contracting.statuses.open', 'submitted' => 'general.pages.contracting.statuses.submitted', 'awarded' => 'general.pages.contracting.statuses.awarded', 'rejected' => 'general.pages.contracting.statuses.rejected', 'cancelled' => 'general.pages.contracting.statuses.cancelled'], 'default' => 'draft'],
            ['field' => 'scope_of_work', 'label_key' => 'scope_of_work', 'type' => 'textarea', 'full' => true],
            ['field' => 'notes', 'label_key' => 'notes', 'type' => 'textarea', 'full' => true],
        ];
    }
}
