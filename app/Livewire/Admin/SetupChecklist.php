<?php

namespace App\Livewire\Admin;

use App\Enums\TenantSettingEnum;
use App\Models\Tenant\Branch;
use App\Models\Tenant\PaymentMethod;
use App\Models\Tenant\Product;
use App\Models\Tenant\Setting;
use Livewire\Component;

class SetupChecklist extends Component
{
    public bool $dismissed = false;
    public array $items = [];

    public function mount(): void
    {
        $this->dismissed = Setting::where('key', 'setup_checklist_dismissed')->whereNotNull('value')->exists();
        $this->buildItems();
    }

    protected function buildItems(): void
    {
        $this->items = [
            [
                'key' => 'business_name',
                'done' => Setting::where('key', 'business_name')->whereNotNull('value')->exists(),
            ],
            [
                'key' => 'logo',
                'done' => Setting::where('key', 'logo')->whereNotNull('value')->exists(),
            ],
            [
                'key' => 'branch',
                'done' => Branch::count() > 0,
            ],
            [
                'key' => 'product',
                'done' => Product::count() > 0,
            ],
            [
                'key' => 'payment_method',
                'done' => PaymentMethod::count() > 0,
            ],
            [
                'key' => 'tax_number',
                'done' => Setting::where('key', 'tax_number')->whereNotNull('value')->exists(),
            ],
        ];
    }

    public function getCompletedCountProperty(): int
    {
        return collect($this->items)->where('done', true)->count();
    }

    public function getTotalCountProperty(): int
    {
        return count($this->items);
    }

    public function getIsCompleteProperty(): bool
    {
        return $this->completedCount === $this->totalCount;
    }

    public function dismiss(): void
    {
        Setting::updateOrCreate(
            ['key' => 'setup_checklist_dismissed'],
            ['value' => '1', 'title' => 'setup_checklist_dismissed', 'type' => TenantSettingEnum::BOOLEAN]
        );

        $this->dismissed = true;
    }

    public function render()
    {
        $this->buildItems();

        return view('livewire.admin.setup-checklist');
    }
}
