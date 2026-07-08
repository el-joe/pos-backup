<?php

namespace App\Livewire\Admin\Contracting\Workers;

use App\Livewire\Admin\Contracting\ContractingCrudComponent;
use App\Models\Tenant\Contracting\Worker;

class WorkersList extends ContractingCrudComponent
{
    protected string $modelClass = Worker::class;
    protected string $permission = 'contracting_workers';
    protected string $viewPath = 'contracting.workers.workers-list';
    protected string $titleKey = 'general.titles.contracting_workers';
    protected string $entityKey = 'general.pages.contracting.workers';
    protected string $icon = 'fa fa-hard-hat';

    protected function listColumns(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code'],
            ['field' => 'name', 'label_key' => 'name'],
            ['field' => 'type', 'label_key' => 'type'],
            ['field' => 'daily_rate', 'label_key' => 'daily_rate', 'type' => 'decimal'],
            ['field' => 'is_active', 'label_key' => 'status', 'type' => 'boolean'],
        ];
    }

    protected function formFields(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code', 'type' => 'text', 'required' => true],
            ['field' => 'name', 'label_key' => 'name', 'type' => 'text', 'required' => true],
            ['field' => 'type', 'label_key' => 'type', 'type' => 'select', 'options' => ['staff' => 'general.pages.contracting.types.staff', 'labor' => 'general.pages.contracting.types.labor'], 'required' => true, 'default' => 'labor'],
            ['field' => 'national_id', 'label_key' => 'national_id', 'type' => 'text'],
            ['field' => 'phone', 'label_key' => 'phone', 'type' => 'text'],
            ['field' => 'daily_rate', 'label_key' => 'daily_rate', 'type' => 'decimal'],
            ['field' => 'monthly_salary', 'label_key' => 'monthly_salary', 'type' => 'decimal'],
            ['field' => 'hire_date', 'label_key' => 'hire_date', 'type' => 'date'],
            ['field' => 'is_active', 'label_key' => 'active', 'type' => 'boolean', 'default' => true],
        ];
    }
}
