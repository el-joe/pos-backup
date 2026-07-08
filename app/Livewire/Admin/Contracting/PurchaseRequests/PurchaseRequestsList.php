<?php

namespace App\Livewire\Admin\Contracting\PurchaseRequests;

use App\Livewire\Admin\Contracting\ContractingCrudComponent;
use App\Models\Tenant\Contracting\PurchaseRequest;

class PurchaseRequestsList extends ContractingCrudComponent
{
    protected string $modelClass = PurchaseRequest::class;
    protected string $permission = 'contracting_purchase_requests';
    protected string $viewPath = 'contracting.purchase-requests.purchase-requests-list';
    protected string $titleKey = 'general.titles.contracting_purchase_requests';
    protected string $entityKey = 'general.pages.contracting.purchase_requests';
    protected string $icon = 'fa fa-clipboard-list';

    protected function listColumns(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code'],
            ['field' => 'required_date', 'label_key' => 'required_date', 'type' => 'date'],
            ['field' => 'status', 'label_key' => 'status'],
        ];
    }

    protected function formFields(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code', 'type' => 'text', 'required' => true],
            ['field' => 'project_id', 'label_key' => 'project', 'type' => 'belongs_to', 'model' => \App\Models\Tenant\Contracting\Project::class, 'display' => 'name'],
            ['field' => 'required_date', 'label_key' => 'required_date', 'type' => 'date'],
            ['field' => 'status', 'label_key' => 'status', 'type' => 'select', 'options' => ['draft' => 'general.pages.contracting.statuses.draft', 'submitted' => 'general.pages.contracting.statuses.submitted', 'approved' => 'general.pages.contracting.statuses.approved', 'rejected' => 'general.pages.contracting.statuses.rejected'], 'default' => 'draft'],
            ['field' => 'notes', 'label_key' => 'notes', 'type' => 'textarea', 'full' => true],
        ];
    }

    protected function statusActions(): array
    {
        return [
            ['action' => 'submit', 'from' => ['draft'], 'to' => 'submitted', 'permission_suffix' => 'update', 'icon' => 'fa fa-paper-plane', 'class' => 'blue', 'label_key' => 'submit'],
            ['action' => 'approve', 'from' => ['submitted'], 'to' => 'approved', 'permission_suffix' => 'approve', 'icon' => 'fa fa-check', 'class' => 'emerald', 'label_key' => 'approve'],
            ['action' => 'reject', 'from' => ['submitted'], 'to' => 'rejected', 'permission_suffix' => 'approve', 'icon' => 'fa fa-ban', 'class' => 'rose', 'label_key' => 'reject'],
        ];
    }
}
