<?php

namespace App\Enums;

enum TransactionTypeEnum : string
{
    case PURCHASE_INVOICE = 'purchase_invoice';
    case PURCHASE_PAYMENT = 'purchase_payment';
    case PURCHASE_INVOICE_REFUND = 'purchase_invoice_refund';
    case PURCHASE_PAYMENT_REFUND = 'purchase_payment_refund';
    case SALE_INVOICE = 'sale_invoice';
    case SALE_PAYMENT = 'sale_payment';
    case SALE_INVOICE_REFUND = 'sale_invoice_refund';
    case SALE_PAYMENT_REFUND = 'sale_payment_refund';
    case EXPENSE = 'expense';
    case EXPENSE_REFUND = 'expense_refund';
    case STOCK_TRANSFER = 'stock_transfer';
    case STOCK_TRANSFER_REFUND = 'stock_transfer_refund';
    case STOCK_ADJUSTMENT = 'stock_adjustment';
    case STOCK_ADJUSTMENT_REFUND = 'stock_adjustment_refund';
    case OPENING_BALANCE = 'opening_balance';
    case CLOSING_BALANCE = 'closing_balance';
    case FIXED_ASSETS = 'fixed_assets';

    public function label(): string
    {
        return match($this) {
            self::PURCHASE_INVOICE => 'Purchase Invoice',
            self::PURCHASE_PAYMENT => 'Purchase Payment',
            self::PURCHASE_INVOICE_REFUND => 'Purchase Invoice Refund',
            self::PURCHASE_PAYMENT_REFUND => 'Purchase Payment Refund',
            self::SALE_INVOICE => 'Sale Invoice',
            self::SALE_PAYMENT => 'Sale Payment',
            self::SALE_INVOICE_REFUND => 'Sale Invoice Refund',
            self::SALE_PAYMENT_REFUND => 'Sale Payment Refund',
            self::EXPENSE => 'Expense',
            self::EXPENSE_REFUND => 'Expense Refund',
            self::STOCK_TRANSFER => 'Stock Transfer',
            self::STOCK_TRANSFER_REFUND => 'Stock Transfer Refund',
            self::STOCK_ADJUSTMENT => 'Stock Adjustment',
            self::STOCK_ADJUSTMENT_REFUND => 'Stock Adjustment Refund',
            self::OPENING_BALANCE => 'Opening Balance',
            self::CLOSING_BALANCE => 'Closing Balance',
            self::FIXED_ASSETS => 'Fixed Assets',
        };
    }
}
