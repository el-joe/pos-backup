<?php

namespace App\Livewire\Admin\Contracting\Equipment;

use App\Livewire\Admin\Contracting\ContractingCrudComponent;
use App\Models\Tenant\Contracting\Equipment;

class EquipmentList extends ContractingCrudComponent
{
    protected string $modelClass = Equipment::class;
    protected string $permission = 'contracting_equipment';
    protected string $viewPath = 'contracting.equipment.equipment-list';
    protected string $titleKey = 'general.titles.contracting_equipment';
    protected string $entityKey = 'general.pages.contracting.equipment';
    protected string $icon = 'fa fa-truck-monster';

    protected function listColumns(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code'],
            ['field' => 'name', 'label_key' => 'name'],
            ['field' => 'type', 'label_key' => 'type'],
            ['field' => 'daily_cost_rate', 'label_key' => 'daily_cost_rate', 'type' => 'decimal'],
            ['field' => 'is_active', 'label_key' => 'status', 'type' => 'boolean'],
        ];
    }

    protected function formFields(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code', 'type' => 'text', 'required' => true],
            ['field' => 'name', 'label_key' => 'name', 'type' => 'text', 'required' => true],
            ['field' => 'type', 'label_key' => 'type', 'type' => 'text'],
            ['field' => 'asset_tag', 'label_key' => 'asset_tag', 'type' => 'text'],
            ['field' => 'hourly_cost_rate', 'label_key' => 'hourly_cost_rate', 'type' => 'decimal'],
            ['field' => 'daily_cost_rate', 'label_key' => 'daily_cost_rate', 'type' => 'decimal'],
            ['field' => 'supplier_name', 'label_key' => 'supplier', 'type' => 'text'],
            ['field' => 'is_active', 'label_key' => 'active', 'type' => 'boolean', 'default' => true],
            ['field' => 'notes', 'label_key' => 'notes', 'type' => 'textarea', 'full' => true],
        ];
    }
}
