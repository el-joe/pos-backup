<?php

namespace App\Livewire\Admin\Contracting\ChartOfAccounts;

use App\Livewire\Admin\Contracting\ContractingCrudComponent;
use App\Models\Tenant\Contracting\ChartOfAccount;

class ChartOfAccountsList extends ContractingCrudComponent
{
    protected string $modelClass = ChartOfAccount::class;
    protected string $permission = 'contracting_chart_of_accounts';
    protected string $viewPath = 'contracting.chart-of-accounts.chart-of-accounts-list';
    protected string $titleKey = 'general.titles.contracting_chart_of_accounts';
    protected string $entityKey = 'general.pages.contracting.chart_of_accounts';
    protected string $icon = 'fa fa-sitemap';

    protected function listColumns(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code'],
            ['field' => 'name', 'label_key' => 'name'],
            ['field' => 'type', 'label_key' => 'type'],
            ['field' => 'is_active', 'label_key' => 'status', 'type' => 'boolean'],
        ];
    }

    protected function formFields(): array
    {
        return [
            ['field' => 'code', 'label_key' => 'code', 'type' => 'text', 'required' => true],
            ['field' => 'name', 'label_key' => 'name', 'type' => 'text', 'required' => true],
            ['field' => 'type', 'label_key' => 'type', 'type' => 'select', 'options' => ['asset' => 'general.pages.contracting.types.asset', 'liability' => 'general.pages.contracting.types.liability', 'equity' => 'general.pages.contracting.types.equity', 'revenue' => 'general.pages.contracting.types.revenue', 'expense' => 'general.pages.contracting.types.expense'], 'required' => true, 'default' => 'asset'],
            ['field' => 'parent_id', 'label_key' => 'parent', 'type' => 'belongs_to', 'model' => \App\Models\Tenant\Contracting\ChartOfAccount::class, 'display' => 'name'],
            ['field' => 'is_active', 'label_key' => 'active', 'type' => 'boolean', 'default' => true],
            ['field' => 'notes', 'label_key' => 'notes', 'type' => 'textarea', 'full' => true],
        ];
    }
}
