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
}
