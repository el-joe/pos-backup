<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    private const REFERENCE_TYPE = 'App\\Models\\Tenant\\Sale';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            $cogsCount = DB::update(
                $this->buildUpdateSql('sale_invoice', 'sale_invoice_cogs'),
                [self::REFERENCE_TYPE, self::REFERENCE_TYPE]
            );

            $refundCogsCount = DB::update(
                $this->buildUpdateSql('sale_invoice_refund', 'sale_invoice_cogs_refund'),
                [self::REFERENCE_TYPE, self::REFERENCE_TYPE]
            );

            $total = $cogsCount + $refundCogsCount;

            Log::info("Repaired {$total} sale_invoice_cogs transactions");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::transaction(function () {
            DB::update("UPDATE transactions SET type = 'sale_invoice' WHERE type = 'sale_invoice_cogs'");
            DB::update("UPDATE transactions SET type = 'sale_invoice_refund' WHERE type = 'sale_invoice_cogs_refund'");
        });
    }

    /**
     * Build the raw SQL that reclassifies the COGS-side transaction of each
     * matched pair (sale_id + reference_type + reference_id, exactly 2 rows
     * of $fromType) into $toType, identified via its transaction_lines
     * pointing to a 'cogs' or 'inventory' account.
     */
    private function buildUpdateSql(string $fromType, string $toType): string
    {
        return <<<SQL
            UPDATE transactions t
            JOIN (
                SELECT DISTINCT tl.transaction_id
                FROM transaction_lines tl
                JOIN accounts a ON a.id = tl.account_id
                JOIN transactions tx ON tx.id = tl.transaction_id
                WHERE tx.type = '{$fromType}'
                  AND tx.reference_type = ?
                  AND a.type IN ('cogs', 'inventory')
                  AND tx.reference_id IN (
                      SELECT reference_id
                      FROM transactions
                      WHERE type = '{$fromType}'
                        AND reference_type = ?
                      GROUP BY reference_id
                      HAVING COUNT(*) = 2
                  )
            ) cogs ON cogs.transaction_id = t.id
            SET t.type = '{$toType}',
                t.description = REPLACE(t.description, 'Sale Payment', 'COGS Entry')
            WHERE t.type = '{$fromType}'
        SQL;
    }
};
