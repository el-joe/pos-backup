<?php

namespace App\Enums;

enum TransactionTypeEnum : string
{
    case PURCHASE_INVOICE = 'purchase_invoice';
    case PURCHASE_PAYMENT = 'purchase_payment';
    case SALE_INVOICE = 'sale_invoice';
    case SALE_PAYMENT = 'sale_payment';
}
