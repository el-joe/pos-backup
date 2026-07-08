<?php

namespace App\Livewire\Admin\Contracting\EquipmentLogs;

use App\Livewire\Admin\Contracting\ContractingCrudComponent;
use App\Models\Tenant\Contracting\EquipmentLog;

class EquipmentLogsList extends ContractingCrudComponent
{
    protected string $modelClass = EquipmentLog::class;
    protected string $permission = 'contracting_equipment_logs';
    protected string $viewPath = 'contracting.equipment-logs.equipment-logs-list';
    protected string $titleKey = 'general.titles.contracting_equipment_logs';
    protected string $entityKey = 'general.pages.contracting.equipment_logs';
    protected string $icon = 'fa fa-tools';

    protected function listColumns(): array
    {
        return [
            ['field' => 'date', 'label_key' => 'date', 'type' => 'date'],
            ['field' => 'hours_used', 'label_key' => 'hours_used', 'type' => 'decimal'],
            ['field' => 'total_cost', 'label_key' => 'total_cost', 'type' => 'decimal'],
        ];
    }

    protected function formFields(): array
    {
        return [
            ['field' => 'date', 'label_key' => 'date', 'type' => 'date', 'required' => true],
            ['field' => 'equipment_id', 'label_key' => 'equipment', 'type' => 'belongs_to', 'model' => \App\Models\Tenant\Contracting\Equipment::class, 'display' => 'name', 'required' => true],
            ['field' => 'project_id', 'label_key' => 'project', 'type' => 'belongs_to', 'model' => \App\Models\Tenant\Contracting\Project::class, 'display' => 'name'],
            ['field' => 'hours_used', 'label_key' => 'hours_used', 'type' => 'decimal'],
            ['field' => 'cost_rate', 'label_key' => 'cost_rate', 'type' => 'decimal'],
            ['field' => 'total_cost', 'label_key' => 'total_cost', 'type' => 'decimal'],
            ['field' => 'operator', 'label_key' => 'operator', 'type' => 'text'],
            ['field' => 'notes', 'label_key' => 'notes', 'type' => 'textarea', 'full' => true],
        ];
    }
}
