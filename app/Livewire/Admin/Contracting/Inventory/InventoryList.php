<?php

namespace App\Livewire\Admin\Contracting\Inventory;

use App\Livewire\Admin\Contracting\ContractingCrudComponent;
use App\Models\Tenant\Contracting\InventoryTransaction;

class InventoryList extends ContractingCrudComponent
{
    protected string $modelClass = InventoryTransaction::class;
    protected string $permission = 'contracting_inventory';
    protected string $viewPath = 'contracting.inventory.inventory-list';
    protected string $titleKey = 'general.titles.contracting_inventory';
    protected string $entityKey = 'general.pages.contracting.inventory';
    protected string $icon = 'fa fa-warehouse';

    protected function listColumns(): array
    {
        return [
            ['field' => 'transaction_date', 'label_key' => 'transaction_date', 'type' => 'date'],
            ['field' => 'type', 'label_key' => 'type'],
            ['field' => 'quantity', 'label_key' => 'quantity', 'type' => 'decimal'],
            ['field' => 'total_cost', 'label_key' => 'total_cost', 'type' => 'decimal'],
        ];
    }

    protected function formFields(): array
    {
        return [
            ['field' => 'transaction_date', 'label_key' => 'transaction_date', 'type' => 'date', 'required' => true],
            ['field' => 'warehouse_id', 'label_key' => 'warehouse', 'type' => 'belongs_to', 'model' => \App\Models\Tenant\Contracting\Warehouse::class, 'display' => 'name', 'required' => true],
            ['field' => 'item_id', 'label_key' => 'item', 'type' => 'belongs_to', 'model' => \App\Models\Tenant\Contracting\ConstructionItem::class, 'display' => 'name', 'required' => true],
            ['field' => 'project_id', 'label_key' => 'project', 'type' => 'belongs_to', 'model' => \App\Models\Tenant\Contracting\Project::class, 'display' => 'name'],
            ['field' => 'type', 'label_key' => 'type', 'type' => 'select', 'options' => ['in' => 'general.pages.contracting.types.in', 'out' => 'general.pages.contracting.types.out', 'transfer' => 'general.pages.contracting.types.transfer'], 'required' => true, 'default' => 'in'],
            ['field' => 'quantity', 'label_key' => 'quantity', 'type' => 'decimal', 'required' => true],
            ['field' => 'unit_cost', 'label_key' => 'unit_cost', 'type' => 'decimal'],
            ['field' => 'total_cost', 'label_key' => 'total_cost', 'type' => 'decimal'],
            ['field' => 'notes', 'label_key' => 'notes', 'type' => 'textarea', 'full' => true],
        ];
    }
}
