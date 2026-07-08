<?php

namespace App\Livewire\Admin\Contracting\Boqs;

use App\Livewire\Admin\Contracting\ContractingCrudComponent;
use App\Models\Tenant\Contracting\Boq;

class BoqsList extends ContractingCrudComponent
{
    protected string $modelClass = Boq::class;
    protected string $permission = 'contracting_boqs';
    protected string $viewPath = 'contracting.boqs.boqs-list';
    protected string $titleKey = 'general.titles.contracting_boqs';
    protected string $entityKey = 'general.pages.contracting.boqs';
    protected string $icon = 'fa fa-list-ol';

    protected function listColumns(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code'],
            ['field' => 'title', 'label_key' => 'title'],
            ['field' => 'type', 'label_key' => 'type'],
            ['field' => 'total_value', 'label_key' => 'total_value', 'type' => 'decimal'],
        ];
    }

    protected function formFields(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code', 'type' => 'text', 'required' => true],
            ['field' => 'title', 'label_key' => 'title', 'type' => 'text', 'required' => true],
            ['field' => 'tender_id', 'label_key' => 'tender', 'type' => 'belongs_to', 'model' => \App\Models\Tenant\Contracting\Tender::class, 'display' => 'name'],
            ['field' => 'project_id', 'label_key' => 'project', 'type' => 'belongs_to', 'model' => \App\Models\Tenant\Contracting\Project::class, 'display' => 'name'],
            ['field' => 'type', 'label_key' => 'type', 'type' => 'select', 'options' => ['construction' => 'general.pages.contracting.types.construction', 'supply' => 'general.pages.contracting.types.supply', 'services' => 'general.pages.contracting.types.services'], 'default' => 'construction'],
            ['field' => 'total_value', 'label_key' => 'total_value', 'type' => 'decimal'],
            ['field' => 'notes', 'label_key' => 'notes', 'type' => 'textarea', 'full' => true],
        ];
    }
}
