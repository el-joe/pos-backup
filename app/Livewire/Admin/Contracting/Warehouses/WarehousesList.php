<?php

namespace App\Livewire\Admin\Contracting\Warehouses;

use App\Livewire\Admin\Contracting\ContractingCrudComponent;
use App\Models\Tenant\Contracting\Warehouse;

class WarehousesList extends ContractingCrudComponent
{
    protected string $modelClass = Warehouse::class;
    protected string $permission = 'contracting_warehouses';
    protected string $viewPath = 'contracting.warehouses.warehouses-list';
    protected string $titleKey = 'general.titles.contracting_warehouses';
    protected string $entityKey = 'general.pages.contracting.warehouses';
    protected string $icon = 'fa fa-building';

    protected function listColumns(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code'],
            ['field' => 'name', 'label_key' => 'name'],
            ['field' => 'location', 'label_key' => 'location'],
            ['field' => 'is_active', 'label_key' => 'status', 'type' => 'boolean'],
        ];
    }

    protected function formFields(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code', 'type' => 'text', 'required' => true],
            ['field' => 'name', 'label_key' => 'name', 'type' => 'text', 'required' => true],
            ['field' => 'location', 'label_key' => 'location', 'type' => 'text'],
            ['field' => 'is_active', 'label_key' => 'active', 'type' => 'boolean', 'default' => true],
        ];
    }
}
