<?php

namespace App\Enums;

enum BusinessTypeEnum: string
{
    // Retail & Trading
    case GENERAL_TRADE = 'general_trade';
    case SUPERMARKET = 'supermarket';
    case PHARMACY = 'pharmacy';
    case ELECTRONICS = 'electronics';
    case BUILDING_MATERIALS = 'building_materials';

    // Food & Beverage
    case RESTAURANT = 'restaurant';
    case BAKERY = 'bakery';
    case FOOD_IMPORT = 'food_import';

    // Services & Professional
    case ACCOUNTING_FIRM = 'accounting_firm';
    case HR_AGENCY = 'hr_agency';
    case REAL_ESTATE = 'real_estate';

    // Construction & Contracting
    case GENERAL_CONTRACTOR = 'general_contractor';
    case SUBCONTRACTOR = 'subcontractor';
    case ENGINEERING = 'engineering';
    case MEP_CONTRACTOR = 'mep_contractor';

    // Other
    case OTHER = 'other';

    public function category(): string
    {
        return match ($this) {
            self::GENERAL_TRADE, self::SUPERMARKET, self::PHARMACY, self::ELECTRONICS, self::BUILDING_MATERIALS => 'retail_trading',
            self::RESTAURANT, self::BAKERY, self::FOOD_IMPORT => 'food_beverage',
            self::ACCOUNTING_FIRM, self::HR_AGENCY, self::REAL_ESTATE => 'services_professional',
            self::GENERAL_CONTRACTOR, self::SUBCONTRACTOR, self::ENGINEERING, self::MEP_CONTRACTOR => 'construction_contracting',
            self::OTHER => 'other',
        };
    }

    public static function categoryLabel(string $category): string
    {
        return match ($category) {
            'retail_trading' => app()->getLocale() === 'ar' ? 'التجارة والبيع بالتجزئة' : 'Retail & Trading',
            'food_beverage' => app()->getLocale() === 'ar' ? 'الأغذية والمشروبات' : 'Food & Beverage',
            'services_professional' => app()->getLocale() === 'ar' ? 'الخدمات والاستشارات' : 'Services & Professional',
            'construction_contracting' => app()->getLocale() === 'ar' ? 'المقاولات والإنشاءات' : 'Construction & Contracting',
            default => app()->getLocale() === 'ar' ? 'أخرى' : 'Other',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::GENERAL_TRADE => 'mdi-warehouse',
            self::SUPERMARKET => 'mdi-cart-outline',
            self::PHARMACY => 'mdi-pill',
            self::ELECTRONICS => 'mdi-television-classic',
            self::BUILDING_MATERIALS => 'mdi-brick',
            self::RESTAURANT => 'mdi-silverware-fork-knife',
            self::BAKERY => 'mdi-bread-slice',
            self::FOOD_IMPORT => 'mdi-truck-outline',
            self::ACCOUNTING_FIRM => 'mdi-calculator-variant-outline',
            self::HR_AGENCY => 'mdi-account-tie-outline',
            self::REAL_ESTATE => 'mdi-home-city-outline',
            self::GENERAL_CONTRACTOR => 'mdi-hard-hat',
            self::SUBCONTRACTOR => 'mdi-tools',
            self::ENGINEERING => 'mdi-ruler-square-compass',
            self::MEP_CONTRACTOR => 'mdi-pipe-wrench',
            self::OTHER => 'mdi-dots-horizontal-circle-outline',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::GENERAL_TRADE => 'General Trade / Wholesale',
            self::SUPERMARKET => 'Supermarket & Grocery',
            self::PHARMACY => 'Pharmacy',
            self::ELECTRONICS => 'Electronics & Appliances',
            self::BUILDING_MATERIALS => 'Building Materials',
            self::RESTAURANT => 'Restaurant & Café',
            self::BAKERY => 'Bakery & Food Production',
            self::FOOD_IMPORT => 'Food Import & Distribution',
            self::ACCOUNTING_FIRM => 'Accounting & Consulting',
            self::HR_AGENCY => 'HR & Staffing Agency',
            self::REAL_ESTATE => 'Real Estate Management',
            self::GENERAL_CONTRACTOR => 'General Contractor',
            self::SUBCONTRACTOR => 'Subcontractor',
            self::ENGINEERING => 'Engineering Consultancy',
            self::MEP_CONTRACTOR => 'MEP Contractor',
            self::OTHER => 'Other',
        };
    }

    public function labelAr(): string
    {
        return match ($this) {
            self::GENERAL_TRADE => 'تجارة عامة / جملة',
            self::SUPERMARKET => 'سوبر ماركت وبقالة',
            self::PHARMACY => 'صيدلية',
            self::ELECTRONICS => 'إلكترونيات وأجهزة منزلية',
            self::BUILDING_MATERIALS => 'مواد بناء',
            self::RESTAURANT => 'مطعم وكافيه',
            self::BAKERY => 'مخبز وإنتاج غذائي',
            self::FOOD_IMPORT => 'استيراد وتوزيع أغذية',
            self::ACCOUNTING_FIRM => 'محاسبة واستشارات',
            self::HR_AGENCY => 'وكالة موارد بشرية وتوظيف',
            self::REAL_ESTATE => 'إدارة عقارات',
            self::GENERAL_CONTRACTOR => 'مقاول عام',
            self::SUBCONTRACTOR => 'مقاول من الباطن',
            self::ENGINEERING => 'استشارات هندسية',
            self::MEP_CONTRACTOR => 'مقاول ميكانيكا وكهرباء وسباكة',
            self::OTHER => 'أخرى',
        };
    }

    /**
     * @return ModulesEnum[]
     */
    public function recommendedModules(): array
    {
        $modules = match ($this) {
            self::HR_AGENCY, self::ACCOUNTING_FIRM,
            self::GENERAL_CONTRACTOR, self::SUBCONTRACTOR, self::ENGINEERING, self::MEP_CONTRACTOR => [ModulesEnum::POS, ModulesEnum::HRM],
            default => [ModulesEnum::POS],
        };

        // HRM is always available as a recommendation regardless of business type.
        if (!in_array(ModulesEnum::HRM, $modules, true)) {
            $modules[] = ModulesEnum::HRM;
        }

        return $modules;
    }
}
