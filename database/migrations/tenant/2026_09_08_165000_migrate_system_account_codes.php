<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Rewrites branch-scoped system account codes from the historical ad-hoc format
 * (e.g. "inventory", "issued_checks-41") to the deterministic format Account::default()
 * now searches by ("{type}-branch-{branch_id}"), so the next control-account lookup
 * resolves the existing row instead of creating a parallel one and stranding balances.
 *
 * Must run before the unique index on (code, branch_id) is added — dated earlier than
 * 2026_09_08_165538_add_unique_index_to_accounts — but is written to be order-independent
 * in case that index already exists (e.g. a dev environment migrated out of order).
 */
return new class extends Migration
{
    private const INDEX_NAME = 'accounts_code_branch_id_unique';

    public function up(): void
    {
        if ($this->indexExists('accounts', self::INDEX_NAME)) {
            Schema::table('accounts', function (Blueprint $table) {
                $table->dropUnique(self::INDEX_NAME);
            });
        }

        DB::transaction(function () {
            $this->collapseDuplicates();
            $this->rewriteCodes();
        });

        $duplicates = DB::table('accounts')
            ->whereNull('deleted_at')
            ->where('model_type', 'App\\Models\\Tenant\\Branch')
            ->whereNotNull('branch_id')
            ->select('type', 'branch_id', DB::raw('COUNT(*) as duplicate_count'), DB::raw('GROUP_CONCAT(id) as ids'))
            ->groupBy('type', 'branch_id')
            ->having('duplicate_count', '>', 1)
            ->get();

        if ($duplicates->isNotEmpty()) {
            $groups = $duplicates->map(fn ($d) => "type={$d->type} branch_id={$d->branch_id} ids=({$d->ids})")->implode('; ');
            throw new \RuntimeException(
                "Refusing to add unique index on accounts(code, branch_id): unresolved duplicate groups remain: {$groups}"
            );
        }

        Schema::table('accounts', function (Blueprint $table) {
            $table->unique(['code', 'branch_id'], self::INDEX_NAME);
        });
    }

    /**
     * Collapse branch-scoped system accounts that would collide once codes are
     * rewritten to "{type}-branch-{branch_id}". Survivor = lowest id; losers are
     * repointed and soft-deleted (their old code left intact) so they never occupy
     * the new deterministic code.
     */
    private function collapseDuplicates(): void
    {
        $groups = DB::table('accounts')
            ->whereNull('deleted_at')
            ->where('model_type', 'App\\Models\\Tenant\\Branch')
            ->whereNotNull('branch_id')
            ->orderBy('id')
            ->get()
            ->groupBy(fn ($a) => $a->type.'|'.$a->branch_id)
            ->filter(fn ($group) => $group->count() > 1);

        foreach ($groups as $group) {
            $survivor = $group->first();
            $losers = $group->slice(1);

            foreach ($losers as $loser) {
                DB::table('transaction_lines')->where('account_id', $loser->id)->update(['account_id' => $survivor->id]);
                DB::table('order_payments')->where('account_id', $loser->id)->update(['account_id' => $survivor->id]);
                DB::table('order_payments')->where('counterparty_account_id', $loser->id)->update(['counterparty_account_id' => $survivor->id]);
                DB::table('checks')->where('collected_account_id', $loser->id)->update(['collected_account_id' => $survivor->id]);
                DB::table('checks')->where('cleared_account_id', $loser->id)->update(['cleared_account_id' => $survivor->id]);

                DB::table('accounts')->where('id', $loser->id)->update(['deleted_at' => now()]);
            }
        }
    }

    /**
     * Match strictly on (model_type, type, branch_id) — never on the old code string,
     * since some historical codes never matched their type (e.g. id 16's code
     * "sale_discount" with type "sales_discount"). model_type=User accounts
     * (customer/supplier subsidiary accounts) are untouched: their codes are per-party
     * generated and Account::default() never resolves them by code.
     */
    private function rewriteCodes(): void
    {
        $accounts = DB::table('accounts')
            ->whereNull('deleted_at')
            ->where('model_type', 'App\\Models\\Tenant\\Branch')
            ->whereNotNull('branch_id')
            ->orderBy('id')
            ->get();

        foreach ($accounts as $account) {
            $newCode = "{$account->type}-branch-{$account->branch_id}";

            if ($newCode === $account->code) {
                continue;
            }

            $occupant = DB::table('accounts')
                ->where('code', $newCode)
                ->where('branch_id', $account->branch_id)
                ->whereNull('deleted_at')
                ->where('id', '!=', $account->id)
                ->first();

            if ($occupant) {
                throw new \RuntimeException(
                    "Cannot rewrite account #{$account->id} code to '{$newCode}': already occupied by account #{$occupant->id}."
                );
            }

            DB::table('accounts')->where('id', $account->id)->update(['code' => $newCode]);
        }
    }

    /**
     * Reverses the code rewrite only. Step 1's duplicate merge (repoints + soft
     * deletes) is NOT undone here — restore from a backup if a full rollback is
     * needed.
     */
    public function down(): void
    {
        if ($this->indexExists('accounts', self::INDEX_NAME)) {
            Schema::table('accounts', function (Blueprint $table) {
                $table->dropUnique(self::INDEX_NAME);
            });
        }

        DB::table('accounts')
            ->where('model_type', 'App\\Models\\Tenant\\Branch')
            ->whereNotNull('branch_id')
            ->orderBy('id')
            ->get()
            ->each(function ($account) {
                DB::table('accounts')->where('id', $account->id)->update(['code' => $account->type]);
            });
    }

    private function indexExists(string $table, string $indexName): bool
    {
        return collect(Schema::getIndexes($table))->contains('name', $indexName);
    }
};
