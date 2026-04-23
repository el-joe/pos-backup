<?php

namespace App\Livewire\Admin\Contracting\Contracts;

use App\Livewire\Admin\Contracting\ContractingCrudComponent;
use App\Models\Tenant\Contracting\Contract;

class ContractsList extends ContractingCrudComponent
{
    protected string $modelClass = Contract::class;
    protected string $permission = 'contracting_contracts';
    protected string $viewPath = 'contracting.contracts.contracts-list';
    protected string $titleKey = 'general.titles.contracting_contracts';
    protected string $entityKey = 'general.pages.contracting.contracts';
    protected string $icon = 'fa fa-file-signature';

    protected function listColumns(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code'],
            ['field' => 'type', 'label_key' => 'type'],
            ['field' => 'start_date', 'label_key' => 'start_date', 'type' => 'date'],
            ['field' => 'total_amount', 'label_key' => 'total_amount', 'type' => 'decimal'],
            ['field' => 'status', 'label_key' => 'status'],
        ];
    }

    protected function formFields(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code', 'type' => 'text', 'required' => true],
            ['field' => 'project_id', 'label_key' => 'project', 'type' => 'belongs_to', 'model' => \App\Models\Tenant\Contracting\Project::class, 'display' => 'name', 'required' => true],
            ['field' => 'type', 'label_key' => 'type', 'type' => 'select', 'options' => ['owner' => 'general.pages.contracting.types.owner', 'subcontractor' => 'general.pages.contracting.types.subcontractor', 'supplier' => 'general.pages.contracting.types.supplier'], 'required' => true, 'default' => 'owner'],
            ['field' => 'start_date', 'label_key' => 'start_date', 'type' => 'date'],
            ['field' => 'end_date', 'label_key' => 'end_date', 'type' => 'date'],
            ['field' => 'total_amount', 'label_key' => 'total_amount', 'type' => 'decimal'],
            ['field' => 'retention_percentage', 'label_key' => 'retention_percentage', 'type' => 'decimal'],
            ['field' => 'advance_payment_amount', 'label_key' => 'advance_payment_amount', 'type' => 'decimal'],
            ['field' => 'taxes_percentage', 'label_key' => 'taxes_percentage', 'type' => 'decimal'],
            ['field' => 'status', 'label_key' => 'status', 'type' => 'select', 'options' => ['draft' => 'general.pages.contracting.statuses.draft', 'active' => 'general.pages.contracting.statuses.active', 'completed' => 'general.pages.contracting.statuses.completed', 'cancelled' => 'general.pages.contracting.statuses.cancelled'], 'default' => 'draft'],
            ['field' => 'notes', 'label_key' => 'notes', 'type' => 'textarea', 'full' => true],
        ];
    }
}
