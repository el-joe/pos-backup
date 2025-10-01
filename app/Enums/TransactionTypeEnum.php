<?php

namespace App\Enums;

enum TransactionTypeEnum : string
{
    case PURCHASE_INVOICE = 'purchase_invoice';
    case PURCHASE_PAYMENT = 'purchase_payment';
    case PURCHASE_REFUND = 'purchase_refund';
    case SALE_INVOICE = 'sale_invoice';
    case SALE_PAYMENT = 'sale_payment';
    case SALE_REFUND = 'sale_refund';

    public function label(): string
    {
        return match($this) {
            self::PURCHASE_INVOICE => 'Purchase Invoice',
            self::PURCHASE_PAYMENT => 'Purchase Payment',
            self::PURCHASE_REFUND => 'Purchase Refund',
            self::SALE_INVOICE => 'Sale Invoice',
            self::SALE_PAYMENT => 'Sale Payment',
            self::SALE_REFUND => 'Sale Refund',
        };
    }
}
