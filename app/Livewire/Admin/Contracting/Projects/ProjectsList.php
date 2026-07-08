<?php

namespace App\Livewire\Admin\Contracting\Projects;

use App\Livewire\Admin\Contracting\ContractingCrudComponent;
use App\Models\Tenant\Contracting\Project;

class ProjectsList extends ContractingCrudComponent
{
    protected string $modelClass = Project::class;
    protected string $permission = 'contracting_projects';
    protected string $viewPath = 'contracting.projects.projects-list';
    protected string $titleKey = 'general.titles.contracting_projects';
    protected string $entityKey = 'general.pages.contracting.projects';
    protected string $icon = 'fa fa-project-diagram';

    protected function listColumns(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code'],
            ['field' => 'name', 'label_key' => 'name'],
            ['field' => 'start_date', 'label_key' => 'start_date', 'type' => 'date'],
            ['field' => 'end_date', 'label_key' => 'end_date', 'type' => 'date'],
            ['field' => 'total_contract_value', 'label_key' => 'total_contract_value', 'type' => 'decimal'],
            ['field' => 'status', 'label_key' => 'status'],
        ];
    }

    protected function formFields(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code', 'type' => 'text', 'required' => true],
            ['field' => 'name', 'label_key' => 'name', 'type' => 'text', 'required' => true],
            ['field' => 'cost_center_id', 'label_key' => 'cost_center', 'type' => 'belongs_to', 'model' => \App\Models\Tenant\Contracting\CostCenter::class, 'display' => 'name'],
            ['field' => 'tender_id', 'label_key' => 'tender', 'type' => 'belongs_to', 'model' => \App\Models\Tenant\Contracting\Tender::class, 'display' => 'name'],
            ['field' => 'start_date', 'label_key' => 'start_date', 'type' => 'date'],
            ['field' => 'end_date', 'label_key' => 'end_date', 'type' => 'date'],
            ['field' => 'total_budget', 'label_key' => 'total_budget', 'type' => 'decimal'],
            ['field' => 'total_contract_value', 'label_key' => 'total_contract_value', 'type' => 'decimal'],
            ['field' => 'status', 'label_key' => 'status', 'type' => 'select', 'options' => ['draft' => 'general.pages.contracting.statuses.draft', 'active' => 'general.pages.contracting.statuses.active', 'paused' => 'general.pages.contracting.statuses.paused', 'completed' => 'general.pages.contracting.statuses.completed', 'cancelled' => 'general.pages.contracting.statuses.cancelled'], 'default' => 'draft'],
            ['field' => 'location', 'label_key' => 'location', 'type' => 'text'],
            ['field' => 'description', 'label_key' => 'description', 'type' => 'textarea', 'full' => true],
        ];
    }
}
