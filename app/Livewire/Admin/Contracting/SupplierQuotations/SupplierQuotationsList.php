<?php

namespace App\Livewire\Admin\Contracting\SupplierQuotations;

use App\Livewire\Admin\Contracting\ContractingCrudComponent;
use App\Models\Tenant\Contracting\SupplierQuotation;

class SupplierQuotationsList extends ContractingCrudComponent
{
    protected string $modelClass = SupplierQuotation::class;
    protected string $permission = 'contracting_supplier_quotations';
    protected string $viewPath = 'contracting.supplier-quotations.supplier-quotations-list';
    protected string $titleKey = 'general.titles.contracting_supplier_quotations';
    protected string $entityKey = 'general.pages.contracting.supplier_quotations';
    protected string $icon = 'fa fa-file-invoice';

    protected function listColumns(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code'],
            ['field' => 'quotation_date', 'label_key' => 'quotation_date', 'type' => 'date'],
            ['field' => 'valid_until', 'label_key' => 'valid_until', 'type' => 'date'],
            ['field' => 'total_amount', 'label_key' => 'total_amount', 'type' => 'decimal'],
            ['field' => 'status', 'label_key' => 'status'],
        ];
    }

    protected function formFields(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code', 'type' => 'text', 'required' => true],
            ['field' => 'tender_id', 'label_key' => 'tender', 'type' => 'belongs_to', 'model' => \App\Models\Tenant\Contracting\Tender::class, 'display' => 'name'],
            ['field' => 'boq_id', 'label_key' => 'boqs', 'type' => 'belongs_to', 'model' => \App\Models\Tenant\Contracting\Boq::class, 'display' => 'title'],
            ['field' => 'quotation_date', 'label_key' => 'quotation_date', 'type' => 'date'],
            ['field' => 'valid_until', 'label_key' => 'valid_until', 'type' => 'date'],
            ['field' => 'total_amount', 'label_key' => 'total_amount', 'type' => 'decimal'],
            ['field' => 'status', 'label_key' => 'status', 'type' => 'select', 'options' => ['draft' => 'general.pages.contracting.statuses.draft', 'submitted' => 'general.pages.contracting.statuses.submitted', 'awarded' => 'general.pages.contracting.statuses.awarded', 'rejected' => 'general.pages.contracting.statuses.rejected'], 'default' => 'draft'],
            ['field' => 'notes', 'label_key' => 'notes', 'type' => 'textarea', 'full' => true],
        ];
    }
}
