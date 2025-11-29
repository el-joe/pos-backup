<?php

namespace App\Enums;

enum PlanFeaturesEnum : string
{
    case BRANCHES = 'branches';
    case ADMINS = 'admins';
    case PRODUCTS = 'products';
    case POS = 'pos';
    case INVENTORY = 'inventory';
    case SALES = 'sales';
    case PURCHASES = 'purchases';
    case DOUBLE_ENTRY_ACCOUNTING = 'double_entry_accounting';
    case BASIC_REPORTS = 'basic_reports';
    case ADVANCED_REPORTS = 'advanced_reports';
    case DISCOUNTS = 'discounts';
    case TAXES = 'taxes';
    case CUSTOMER_SUPPORT = 'customer_support';

    public function label(): string
    {
        return match($this) {
            self::BRANCHES => __('general.features.branches'),
            self::ADMINS => __('general.features.admins'),
            self::PRODUCTS => __('general.features.products'),
            self::POS => __('general.features.pos'),
            self::INVENTORY => __('general.features.inventory'),
            self::SALES => __('general.features.sales'),
            self::PURCHASES => __('general.features.purchases'),
            self::DOUBLE_ENTRY_ACCOUNTING => __('general.features.double_entry_accounting'),
            self::BASIC_REPORTS => __('general.features.basic_reports'),
            self::ADVANCED_REPORTS => __('general.features.advanced_reports'),
            self::DISCOUNTS => __('general.features.discounts'),
            self::TAXES => __('general.features.taxes'),
            self::CUSTOMER_SUPPORT => __('general.features.customer_support'),
        };
    }
}
