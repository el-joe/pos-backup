<?php

namespace App\Livewire\Admin\Contracting\PurchaseOrders;

use App\Livewire\Admin\Contracting\ContractingCrudComponent;
use App\Models\Tenant\Contracting\PurchaseOrder;

class PurchaseOrdersList extends ContractingCrudComponent
{
    protected string $modelClass = PurchaseOrder::class;
    protected string $permission = 'contracting_purchase_orders';
    protected string $viewPath = 'contracting.purchase-orders.purchase-orders-list';
    protected string $titleKey = 'general.titles.contracting_purchase_orders';
    protected string $entityKey = 'general.pages.contracting.purchase_orders';
    protected string $icon = 'fa fa-shopping-cart';

    protected function listColumns(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code'],
            ['field' => 'order_date', 'label_key' => 'order_date', 'type' => 'date'],
            ['field' => 'total_amount', 'label_key' => 'total_amount', 'type' => 'decimal'],
            ['field' => 'status', 'label_key' => 'status'],
        ];
    }

    protected function formFields(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code', 'type' => 'text', 'required' => true],
            ['field' => 'project_id', 'label_key' => 'project', 'type' => 'belongs_to', 'model' => \App\Models\Tenant\Contracting\Project::class, 'display' => 'name'],
            ['field' => 'purchase_request_id', 'label_key' => 'purchase_request', 'type' => 'belongs_to', 'model' => \App\Models\Tenant\Contracting\PurchaseRequest::class, 'display' => 'code'],
            ['field' => 'order_date', 'label_key' => 'order_date', 'type' => 'date'],
            ['field' => 'expected_delivery_date', 'label_key' => 'expected_delivery_date', 'type' => 'date'],
            ['field' => 'subtotal', 'label_key' => 'subtotal', 'type' => 'decimal'],
            ['field' => 'tax_amount', 'label_key' => 'tax_amount', 'type' => 'decimal'],
            ['field' => 'total_amount', 'label_key' => 'total_amount', 'type' => 'decimal'],
            ['field' => 'status', 'label_key' => 'status', 'type' => 'select', 'options' => ['draft' => 'general.pages.contracting.statuses.draft', 'ordered' => 'general.pages.contracting.statuses.ordered', 'partially_received' => 'general.pages.contracting.statuses.partially_received', 'received' => 'general.pages.contracting.statuses.received', 'closed' => 'general.pages.contracting.statuses.closed'], 'default' => 'draft'],
            ['field' => 'notes', 'label_key' => 'notes', 'type' => 'textarea', 'full' => true],
        ];
    }

    protected function statusActions(): array
    {
        return [
            ['action' => 'approve', 'from' => ['draft'], 'to' => 'ordered', 'permission_suffix' => 'approve', 'icon' => 'fa fa-check', 'class' => 'emerald', 'label_key' => 'approve'],
        ];
    }
}
