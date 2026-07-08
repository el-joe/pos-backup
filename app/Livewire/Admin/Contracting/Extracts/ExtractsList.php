<?php

namespace App\Livewire\Admin\Contracting\Extracts;

use App\Livewire\Admin\Contracting\ContractingCrudComponent;
use App\Models\Tenant\Contracting\Extract;

class ExtractsList extends ContractingCrudComponent
{
    protected string $modelClass = Extract::class;
    protected string $permission = 'contracting_extracts';
    protected string $viewPath = 'contracting.extracts.extracts-list';
    protected string $titleKey = 'general.titles.contracting_extracts';
    protected string $entityKey = 'general.pages.contracting.extracts';
    protected string $icon = 'fa fa-file-invoice-dollar';

    protected function listColumns(): array
    {
        return [
            ['field' => 'extract_number', 'label_key' => 'extract_number'],
            ['field' => 'type', 'label_key' => 'type'],
            ['field' => 'period_end', 'label_key' => 'period_end', 'type' => 'date'],
            ['field' => 'net_amount', 'label_key' => 'net_amount', 'type' => 'decimal'],
            ['field' => 'status', 'label_key' => 'status'],
        ];
    }

    protected function formFields(): array
    {
        return [
            ['field' => 'extract_number', 'label_key' => 'extract_number', 'type' => 'text', 'required' => true],
            ['field' => 'contract_id', 'label_key' => 'contract', 'type' => 'belongs_to', 'model' => \App\Models\Tenant\Contracting\Contract::class, 'display' => 'code', 'required' => true],
            ['field' => 'type', 'label_key' => 'type', 'type' => 'select', 'options' => ['interim' => 'general.pages.contracting.types.interim', 'advance' => 'general.pages.contracting.types.advance', 'final' => 'general.pages.contracting.types.final'], 'default' => 'interim'],
            ['field' => 'period_start', 'label_key' => 'period_start', 'type' => 'date'],
            ['field' => 'period_end', 'label_key' => 'period_end', 'type' => 'date'],
            ['field' => 'gross_amount', 'label_key' => 'gross_amount', 'type' => 'decimal'],
            ['field' => 'deductions_amount', 'label_key' => 'deductions_amount', 'type' => 'decimal'],
            ['field' => 'net_amount', 'label_key' => 'net_amount', 'type' => 'decimal'],
            ['field' => 'status', 'label_key' => 'status', 'type' => 'select', 'options' => ['draft' => 'general.pages.contracting.statuses.draft', 'submitted' => 'general.pages.contracting.statuses.submitted', 'approved' => 'general.pages.contracting.statuses.approved', 'posted' => 'general.pages.contracting.statuses.posted', 'paid' => 'general.pages.contracting.statuses.paid'], 'default' => 'draft'],
            ['field' => 'notes', 'label_key' => 'notes', 'type' => 'textarea', 'full' => true],
        ];
    }

    protected function statusActions(): array
    {
        return [
            ['action' => 'submit', 'from' => ['draft'], 'to' => 'submitted', 'permission_suffix' => 'update', 'icon' => 'fa fa-paper-plane', 'class' => 'blue', 'label_key' => 'submit'],
            ['action' => 'approve', 'from' => ['submitted'], 'to' => 'approved', 'permission_suffix' => 'approve', 'timestamp_field' => 'approved_at', 'icon' => 'fa fa-check', 'class' => 'emerald', 'label_key' => 'approve'],
            ['action' => 'post', 'from' => ['approved'], 'to' => 'posted', 'permission_suffix' => 'post', 'icon' => 'fa fa-upload', 'class' => 'violet', 'label_key' => 'post'],
            ['action' => 'pay', 'from' => ['posted'], 'to' => 'paid', 'permission_suffix' => 'update', 'timestamp_field' => 'paid_at', 'icon' => 'fa fa-money-bill-wave', 'class' => 'teal', 'label_key' => 'pay'],
        ];
    }
}
