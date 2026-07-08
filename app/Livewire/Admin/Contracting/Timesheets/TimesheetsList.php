<?php

namespace App\Livewire\Admin\Contracting\Timesheets;

use App\Livewire\Admin\Contracting\ContractingCrudComponent;
use App\Models\Tenant\Contracting\LaborTimesheet;

class TimesheetsList extends ContractingCrudComponent
{
    protected string $modelClass = LaborTimesheet::class;
    protected string $permission = 'contracting_timesheets';
    protected string $viewPath = 'contracting.timesheets.timesheets-list';
    protected string $titleKey = 'general.titles.contracting_timesheets';
    protected string $entityKey = 'general.pages.contracting.timesheets';
    protected string $icon = 'fa fa-clock';

    protected function listColumns(): array
    {
        return [
            ['field' => 'date', 'label_key' => 'date', 'type' => 'date'],
            ['field' => 'hours_worked', 'label_key' => 'hours_worked', 'type' => 'decimal'],
            ['field' => 'total_cost', 'label_key' => 'total_cost', 'type' => 'decimal'],
            ['field' => 'status', 'label_key' => 'status'],
        ];
    }

    protected function formFields(): array
    {
        return [
            ['field' => 'date', 'label_key' => 'date', 'type' => 'date', 'required' => true],
            ['field' => 'project_id', 'label_key' => 'project', 'type' => 'belongs_to', 'model' => \App\Models\Tenant\Contracting\Project::class, 'display' => 'name', 'required' => true],
            ['field' => 'worker_id', 'label_key' => 'worker', 'type' => 'belongs_to', 'model' => \App\Models\Tenant\Contracting\Worker::class, 'display' => 'name', 'required' => true],
            ['field' => 'hours_worked', 'label_key' => 'hours_worked', 'type' => 'decimal'],
            ['field' => 'overtime_hours', 'label_key' => 'overtime_hours', 'type' => 'decimal'],
            ['field' => 'hourly_rate', 'label_key' => 'hourly_rate', 'type' => 'decimal'],
            ['field' => 'total_cost', 'label_key' => 'total_cost', 'type' => 'decimal'],
            ['field' => 'status', 'label_key' => 'status', 'type' => 'select', 'options' => ['draft' => 'general.pages.contracting.statuses.draft', 'submitted' => 'general.pages.contracting.statuses.submitted', 'approved' => 'general.pages.contracting.statuses.approved'], 'default' => 'draft'],
            ['field' => 'notes', 'label_key' => 'notes', 'type' => 'textarea', 'full' => true],
        ];
    }

    protected function statusActions(): array
    {
        return [
            ['action' => 'submit', 'from' => ['draft'], 'to' => 'submitted', 'permission_suffix' => 'update', 'icon' => 'fa fa-paper-plane', 'class' => 'blue', 'label_key' => 'submit'],
            ['action' => 'approve', 'from' => ['submitted'], 'to' => 'approved', 'permission_suffix' => 'approve', 'icon' => 'fa fa-check', 'class' => 'emerald', 'label_key' => 'approve'],
        ];
    }
}
