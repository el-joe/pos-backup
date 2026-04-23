<?php

namespace App\Livewire\Admin\Contracting\CostCenters;

use App\Livewire\Admin\Contracting\ContractingCrudComponent;
use App\Models\Tenant\Contracting\CostCenter;

class CostCentersList extends ContractingCrudComponent
{
    protected string $modelClass = CostCenter::class;
    protected string $permission = 'contracting_cost_centers';
    protected string $viewPath = 'contracting.cost-centers.cost-centers-list';
    protected string $titleKey = 'general.titles.contracting_cost_centers';
    protected string $entityKey = 'general.pages.contracting.cost_centers';
    protected string $icon = 'fa fa-coins';

    protected function listColumns(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code'],
            ['field' => 'name', 'label_key' => 'name'],
            ['field' => 'is_active', 'label_key' => 'status', 'type' => 'boolean'],
        ];
    }

    protected function formFields(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code', 'type' => 'text', 'required' => true],
            ['field' => 'name', 'label_key' => 'name', 'type' => 'text', 'required' => true],
            ['field' => 'parent_id', 'label_key' => 'parent', 'type' => 'belongs_to', 'model' => \App\Models\Tenant\Contracting\CostCenter::class, 'display' => 'name'],
            ['field' => 'is_active', 'label_key' => 'active', 'type' => 'boolean', 'default' => true],
        ];
    }
}
