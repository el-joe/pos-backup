<?php

namespace App\Console\Commands;

use App\Enums\AccountTypeEnum;
use App\Enums\TenantSettingEnum;
use App\Models\Tenant;
use App\Models\Tenant\Admin;
use App\Models\Tenant\Branch;
use App\Models\Tenant\ExpenseCategory;
use App\Models\Tenant\Setting;
use App\Services\BranchService;
use App\Services\PaymentMethodService;
use App\Models\Tenant\Account;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TenantCreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tenant-create-admin {request}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = json_decode($this->argument('request'),true);
        // get arguments
        $admin = Admin::create($data);

        $this->setPermissions();
        $this->defaultPaymentMethods();
        $this->defaultBranch();
        $this->defaultSettings();
        $this->setExpenseCategories();
    }

    function defaultBranch() {
        $branchService = app(BranchService::class);

        $branchData = [
            'name' => 'Main Branch',
            'email' => tenant()->email,
            'phone' => tenant()->phone,
            'address' => tenant()->address,
            'active' => true,
        ];

        $branch = $branchService->save(null, $branchData);

        // Seed default check accounts for this branch
        if($branch) {
            Account::defaultForPaymentMethodSlug('Checks Under Collection', AccountTypeEnum::CHECKS_UNDER_COLLECTION->value, $branch->id, 'check');
            Account::defaultForPaymentMethodSlug('Issued Checks', AccountTypeEnum::ISSUED_CHECKS->value, $branch->id, 'check');
        }
    }

    function defaultPaymentMethods() {
        $paymentMethodService = app(PaymentMethodService::class);

        $paymentMethods = [
            ['name' => 'Cash' , 'slug' => 'cash', 'active' => true],
            ['name' => 'Bank Transfer', 'slug' => 'bank-transfer', 'active' => true],
            ['name' => 'Check', 'slug' => 'check', 'active' => true],
        ];

        foreach ($paymentMethods as $method) {
            $paymentMethodService->save(null, $method);
        }
    }

    function setPermissions(){
        $permissions = defaultPermissionsList();

        $permissionsData = [];

        foreach ($permissions as $key => $value) {
            foreach($value as $permission){
                $data = ['name' => $key.'.'.$permission, 'guard_name' => 'tenant_admin'];
                $permissionsData[] = $data;
            }
        }

        Permission::upsert($permissionsData, ['name', 'guard_name']);
    }

    function defaultSettings() {
        $data = [
            [
                'title' => 'settings.business_name',
                'key' => 'business_name',
                'value' => tenant('name') ?? '',
                'type' => TenantSettingEnum::STRING->value,
                'group' => 'business',
            ],
            [
                'title' => 'settings.business_email',
                'key' => 'business_email',
                'value' => tenant('email') ?? '',
                'type' => TenantSettingEnum::EMAIL->value,
                'group' => 'business',
            ],
            [
                'title' => 'settings.business_phone',
                'key' => 'business_phone',
                'value' => tenant('phone') ?? '',
                'type' => TenantSettingEnum::STRING->value,
                'group' => 'business',
            ],
            [
                'title' => 'settings.business_address',
                'key' => 'business_address',
                'value' => tenant('address') ?? '',
                'type' => TenantSettingEnum::STRING->value,
                'group' => 'business',
            ],
            [
                'title' => 'settings.default_currency',
                'key' => 'currency_id',
                'value' => tenant('currency_id') ?? 1,
                'type' => TenantSettingEnum::SELECT->value,
                'group' => 'business',
                'options' => null
            ],
            [
                'title' => 'settings.default_country',
                'key' => 'country_id',
                'value' => tenant('country_id') ?? 1,
                'type' => TenantSettingEnum::SELECT->value,
                'group' => 'business',
                'options' => null
            ],
            [
                'title' => 'settings.tax_number',
                'key' => 'tax_number',
                'value' => tenant('tax_number') ?? '',
                'type' => TenantSettingEnum::STRING->value,
                'group' => 'business',
            ],
            [
                'title' => 'settings.logo',
                'key' => 'logo',
                'value' => '',
                'type' => TenantSettingEnum::FILE->value,
                'group' => 'business',
                'options' => null
            ],
            [
                'title' => 'settings.date_format',
                'key' => 'date_format',
                'value' => 'Y-m-d',
                'type' => TenantSettingEnum::SELECT->value,
                'group' => 'business',
                'options' => json_encode([
                    'Y-m-d' => 'Y-m-d',
                    'd-m-Y' => 'd-m-Y',
                    'm-d-Y' => 'm-d-Y',
                    'd/m/Y' => 'd/m/Y',
                    'm/d/Y' => 'm/d/Y',
                    'Y/m/d' => 'Y/m/d',
                ])
            ],
            [
                'title' => 'settings.time_format',
                'key' => 'time_format',
                'value' => 'H:i',
                'type' => TenantSettingEnum::SELECT->value,
                'group' => 'business',
                'options' => json_encode([
                    'H:i' => '24-hour (14:00)',
                    'h:i A' => '12-hour (02:00 PM)',
                ])
            ],
            [
                'title' => 'settings.currency_precision',
                'key' => 'currency_precision',
                'value' => '2',
                'type' => TenantSettingEnum::SELECT->value,
                'group' => 'business',
                'options' => json_encode([
                    '0' => '0',
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ])
            ],
            [
                'title' => 'settings.qty_precision',
                'key' => 'qty_precision',
                'value' => '2',
                'type' => TenantSettingEnum::SELECT->value,
                'group' => 'business',
                'options' => json_encode([
                    '0' => '0',
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ])
            ],
            [
                'title' => 'settings.enable_brands',
                'key' => 'enable_brands',
                'value' => '1',
                'type' => TenantSettingEnum::BOOLEAN->value,
                'group' => 'product',
                'options' => null
            ],
            [
                'title' => 'settings.enable_categories',
                'key' => 'enable_categories',
                'value' => '1',
                'type' => TenantSettingEnum::BOOLEAN->value,
                'group' => 'product',
                'options' => null
            ],
            // [
            //     'title' => 'settings.default_tax',
            //     'key' => 'default_tax',
            //     'value' => null,
            //     'type' => TenantSettingEnum::SELECT->value,
            //     'group' => 'pos',
            //     'options' => null
            // ],
            // [
            //     'title' => 'settings.theme_color',
            //     'key' => 'theme_color',
            //     'value' => '',
            //     'type' => TenantSettingEnum::SELECT->value,
            //     'group' => 'system',
            //     'options' => json_encode([
            //         'theme-pink' => 'Pink',
            //         'theme-red' => 'Red',
            //         'theme-warning' => 'Warning',
            //         'theme-yellow' => 'Yellow',
            //         'theme-lime' => 'Lime',
            //         'theme-green' => 'Green',
            //         '' => 'Teal',
            //         'theme-info' => 'Info',
            //         'theme-primary' => 'Primary',
            //         'theme-purple' => 'Purple',
            //         'theme-indigo' => 'Indigo',
            //         'theme-gray-200' => 'Gray 200',
            //     ])
            // ],
            [
                'title' => 'settings.default_language',
                'key' => 'default_language',
                'value' => 'en',
                'type' => TenantSettingEnum::SELECT->value,
                'group' => 'system',
                'options' => null
            ],
            // [
            //     'title' => 'settings.theme_mode',
            //     'key' => 'theme_mode',
            //     'value' => 'light',
            //     'type' => TenantSettingEnum::SELECT->value,
            //     'group' => 'system',
            //     'options' => json_encode([
            //         'light' => 'Light',
            //         'dark' => 'Dark',
            //     ])
            // ]
        ];

        foreach ($data as $item) {
            Setting::updateOrCreate(
                ['key' => $item['key']],
                $item
            );
        }
    }

    function setExpenseCategories() {
        foreach (AccountTypeEnum::defaultExpensesAccounts() as $parentAccountType => $childAccounts) {
            $parentAccount = AccountTypeEnum::from($parentAccountType);
            // create expense category
            $parent = ExpenseCategory::create([
                'name' => $parentAccount->label(),
                'ar_name' => $parentAccount->expensesAccountsTranslation(),
                'parent_id' => null,
                'active' => true,
                'default' => true,
                'key' => $parentAccount->value,
            ]);

            $en = $childAccounts['en'];
            $ar = $childAccounts['ar'];
            foreach ($en as $index => $childAccountName) {
                ExpenseCategory::create([
                    'name' => $childAccountName,
                    'ar_name' => $ar[$index],
                    'parent_id' => $parent->id,
                    'active' => true,
                    'default' => true,
                    'key' => $parentAccount->value,
                ]);
            }
        }
    }
}
