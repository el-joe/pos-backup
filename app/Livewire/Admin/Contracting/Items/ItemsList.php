<?php

namespace App\Livewire\Admin\Contracting\Items;

use App\Livewire\Admin\Contracting\ContractingCrudComponent;
use App\Models\Tenant\Contracting\ConstructionItem;

class ItemsList extends ContractingCrudComponent
{
    protected string $modelClass = ConstructionItem::class;
    protected string $permission = 'contracting_items';
    protected string $viewPath = 'contracting.items.items-list';
    protected string $titleKey = 'general.titles.contracting_items';
    protected string $entityKey = 'general.pages.contracting.items';
    protected string $icon = 'fa fa-cubes';

    protected function listColumns(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code'],
            ['field' => 'name', 'label_key' => 'name'],
            ['field' => 'unit', 'label_key' => 'unit'],
            ['field' => 'standard_cost', 'label_key' => 'standard_cost', 'type' => 'decimal'],
            ['field' => 'is_inventory_tracked', 'label_key' => 'is_inventory_tracked', 'type' => 'boolean'],
        ];
    }

    protected function formFields(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code', 'type' => 'text', 'required' => true],
            ['field' => 'name', 'label_key' => 'name', 'type' => 'text', 'required' => true],
            ['field' => 'unit', 'label_key' => 'unit', 'type' => 'text'],
            ['field' => 'standard_cost', 'label_key' => 'standard_cost', 'type' => 'decimal'],
            ['field' => 'is_inventory_tracked', 'label_key' => 'is_inventory_tracked', 'type' => 'boolean', 'default' => true],
            ['field' => 'description', 'label_key' => 'description', 'type' => 'textarea', 'full' => true],
        ];
    }
}
