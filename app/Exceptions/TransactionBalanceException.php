<?php

namespace App\Exceptions;

use Exception;

class TransactionBalanceException extends Exception
{
    public static function unbalanced(?string $type, mixed $referenceId, float $debit, float $credit): self
    {
        return new self(__('accounting.unbalanced_transaction', [
            'type' => $type ?? 'N/A',
            'reference' => $referenceId ?? 'N/A',
            'debit' => number_format($debit, 2),
            'credit' => number_format($credit, 2),
        ]));
    }

    public static function emptyLines(?string $type, mixed $referenceId): self
    {
        return new self(__('accounting.empty_lines', [
            'type' => $type ?? 'N/A',
            'reference' => $referenceId ?? 'N/A',
        ]));
    }

    public static function invalidLine(?string $type, mixed $referenceId): self
    {
        return new self(__('accounting.invalid_line', [
            'type' => $type ?? 'N/A',
            'reference' => $referenceId ?? 'N/A',
        ]));
    }
}
