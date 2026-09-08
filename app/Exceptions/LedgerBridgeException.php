<?php

namespace App\Exceptions;

use Exception;

class LedgerBridgeException extends Exception
{
    public static function unmappedAccountType(?string $accountType, int $accountId, int $transactionId): self
    {
        return new self("LedgerBridge: no COA mapping for account type '{$accountType}' (account_id: {$accountId}, transaction_id: {$transactionId}).");
    }

    public static function missingCoaAccount(string $coaCode, int $transactionId): self
    {
        return new self("LedgerBridge: COA code '{$coaCode}' not found or inactive (transaction_id: {$transactionId}).");
    }

    public static function unbalanced(int $transactionId, float $debit, float $credit): self
    {
        return new self("LedgerBridge: refusing to post unbalanced journal entry for transaction_id {$transactionId} (debit: {$debit}, credit: {$credit}).");
    }
}
