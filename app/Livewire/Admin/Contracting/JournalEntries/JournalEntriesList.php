<?php

namespace App\Livewire\Admin\Contracting\JournalEntries;

use App\Livewire\Admin\Contracting\ContractingCrudComponent;
use App\Models\Tenant\Contracting\JournalEntry;

class JournalEntriesList extends ContractingCrudComponent
{
    protected string $modelClass = JournalEntry::class;
    protected string $permission = 'contracting_journal_entries';
    protected string $viewPath = 'contracting.journal-entries.journal-entries-list';
    protected string $titleKey = 'general.titles.contracting_journal_entries';
    protected string $entityKey = 'general.pages.contracting.journal_entries';
    protected string $icon = 'fa fa-book';

    protected function listColumns(): array
    {
        return [
            ['field' => 'reference', 'label_key' => 'reference'],
            ['field' => 'date', 'label_key' => 'date', 'type' => 'date'],
            ['field' => 'total_debit', 'label_key' => 'total_debit', 'type' => 'decimal'],
            ['field' => 'total_credit', 'label_key' => 'total_credit', 'type' => 'decimal'],
            ['field' => 'status', 'label_key' => 'status'],
        ];
    }

    protected function formFields(): array
    {
        return [
            ['field' => 'reference', 'label_key' => 'reference', 'type' => 'text', 'required' => true],
            ['field' => 'date', 'label_key' => 'date', 'type' => 'date', 'required' => true],
            ['field' => 'description', 'label_key' => 'description', 'type' => 'textarea', 'full' => true],
            ['field' => 'total_debit', 'label_key' => 'total_debit', 'type' => 'decimal'],
            ['field' => 'total_credit', 'label_key' => 'total_credit', 'type' => 'decimal'],
            ['field' => 'status', 'label_key' => 'status', 'type' => 'select', 'options' => ['draft' => 'general.pages.contracting.statuses.draft', 'posted' => 'general.pages.contracting.statuses.posted'], 'default' => 'draft'],
        ];
    }

    protected function statusActions(): array
    {
        return [
            ['action' => 'post', 'from' => ['draft'], 'to' => 'posted', 'permission_suffix' => 'post', 'timestamp_field' => 'posted_at', 'icon' => 'fa fa-upload', 'class' => 'violet', 'label_key' => 'post'],
        ];
    }
}
