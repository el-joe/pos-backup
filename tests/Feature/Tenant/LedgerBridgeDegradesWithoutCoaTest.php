<?php

namespace Tests\Feature\Tenant;

use App\Enums\AccountTypeEnum;
use App\Exceptions\LedgerBridgeException;
use App\Models\Tenant\Account;
use App\Models\Tenant\Contracting\ChartOfAccount;
use App\Models\Tenant\Contracting\JournalEntry;
use App\Models\Tenant\Contracting\JournalEntryLine;
use App\Models\Tenant\Transaction;
use App\Models\Tenant\TransactionLine;
use App\Repositories\TransactionRepository;
use App\Services\LedgerBridgeService;
use App\Services\TransactionService;
use Illuminate\Auth\GenericUser;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LedgerBridgeDegradesWithoutCoaTest extends TestCase
{
    private const TEST_DATABASE = 'test_ledger_bridge_no_coa';

    protected function setUp(): void
    {
        parent::setUp();

        DB::connection('mysql')->statement('CREATE DATABASE IF NOT EXISTS `'.self::TEST_DATABASE.'`');

        config(['database.connections.testing_tenant' => array_merge(
            config('database.connections.mysql'),
            ['database' => self::TEST_DATABASE]
        )]);
        config(['database.default' => 'testing_tenant']);
        DB::purge('testing_tenant');

        foreach (['journal_entry_lines', 'journal_entries', 'chart_of_accounts', 'transaction_lines', 'transactions', 'accounts'] as $table) {
            Schema::connection('testing_tenant')->dropIfExists($table);
        }

        Schema::connection('testing_tenant')->create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('type');
            $table->boolean('is_payment_capable')->default(false);
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('testing_tenant')->create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('description')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('note')->nullable();
            $table->string('type')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->unsignedBigInteger('reversed_by_transaction_id')->nullable();
            $table->string('reversal_reason')->nullable();
            $table->timestamps();
        });

        Schema::connection('testing_tenant')->create('transaction_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id')->index();
            $table->unsignedBigInteger('account_id')->index();
            $table->unsignedBigInteger('cost_center_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->enum('type', ['debit', 'credit']);
            $table->decimal('amount', 15, 2);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        // Deliberately created but left empty — simulates a tenant provisioned before
        // ChartOfAccountsSeeder ran automatically (prompt 19's real-world case).
        Schema::connection('testing_tenant')->create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('type');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('testing_tenant')->create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable();
            $table->string('referenceable_type')->nullable();
            $table->unsignedBigInteger('referenceable_id')->nullable();
            $table->date('date')->nullable();
            $table->string('description')->nullable();
            $table->string('status')->default('draft');
            $table->decimal('total_debit', 15, 2)->default(0);
            $table->decimal('total_credit', 15, 2)->default(0);
            $table->unsignedBigInteger('posted_by')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('testing_tenant')->create('journal_entry_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('journal_entry_id')->index();
            $table->unsignedBigInteger('account_id')->index();
            $table->unsignedBigInteger('cost_center_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Auth::guard('tenant_admin')->setUser(new GenericUser(['id' => 1]));
    }

    protected function tearDown(): void
    {
        foreach (['journal_entry_lines', 'journal_entries', 'chart_of_accounts', 'transaction_lines', 'transactions', 'accounts'] as $table) {
            Schema::connection('testing_tenant')->dropIfExists($table);
        }
        DB::purge('testing_tenant');

        parent::tearDown();
    }

    private function transactionService(): TransactionService
    {
        return new TransactionService(new TransactionRepository(new Transaction()), new LedgerBridgeService());
    }

    private function makeAccounts(): array
    {
        $cash = Account::create(['name' => 'Branch Cash', 'type' => AccountTypeEnum::BRANCH_CASH->value]);
        $owner = Account::create(['name' => 'Owner Account', 'type' => AccountTypeEnum::OWNER_ACCOUNT->value]);

        return [$cash, $owner];
    }

    public function test_a_sale_on_a_tenant_with_zero_coa_rows_still_commits_with_balanced_lines(): void
    {
        $this->assertSame(0, ChartOfAccount::count());

        [$cash, $owner] = $this->makeAccounts();

        $transaction = $this->transactionService()->create([
            'type' => 'cash_deposit',
            'description' => 'Sale on unseeded tenant',
            'lines' => [
                ['account_id' => $cash->id, 'type' => 'debit', 'amount' => 100],
                ['account_id' => $owner->id, 'type' => 'credit', 'amount' => 100],
            ],
        ]);

        $this->assertNotNull($transaction->id);
        $this->assertSame(2, TransactionLine::where('transaction_id', $transaction->id)->count());

        $debit = (float) TransactionLine::where('transaction_id', $transaction->id)->where('type', 'debit')->sum('amount');
        $credit = (float) TransactionLine::where('transaction_id', $transaction->id)->where('type', 'credit')->sum('amount');
        $this->assertSame($debit, $credit);

        // No chart of accounts means no journal projection — this is the intended degrade,
        // not a bug: the transaction/transaction_lines write (system A) is authoritative.
        $this->assertSame(0, JournalEntry::count());
        $this->assertSame(0, JournalEntryLine::count());
    }

    public function test_populated_coa_missing_a_specific_code_still_throws(): void
    {
        // A COA that exists but doesn't cover every mapped code — a real bug, must stay loud.
        ChartOfAccount::create(['code' => '1010', 'name' => 'Cash at Hand', 'type' => 'asset']);

        [$cash, $owner] = $this->makeAccounts();
        config(['tenant.account_coa_map' => [
            'branch_cash' => '1010',
            'owner_account' => '9999', // not seeded
        ]]);

        $transaction = Transaction::create(['type' => 'cash_deposit', 'amount' => 20]);
        $transaction->lines()->create(['account_id' => $cash->id, 'type' => 'debit', 'amount' => 20]);
        $transaction->lines()->create(['account_id' => $owner->id, 'type' => 'credit', 'amount' => 20]);

        $this->expectException(LedgerBridgeException::class);
        (new LedgerBridgeService())->post($transaction);
    }

    public function test_bridge_strict_false_degrades_a_mapping_failure_instead_of_throwing(): void
    {
        ChartOfAccount::create(['code' => '1010', 'name' => 'Cash at Hand', 'type' => 'asset']);

        [$cash, $owner] = $this->makeAccounts();
        config(['tenant.account_coa_map' => [
            'branch_cash' => '1010',
            'owner_account' => '9999', // not seeded — would normally throw
        ]]);
        config(['ledger.bridge_strict' => false]);

        $transaction = Transaction::create(['type' => 'cash_deposit', 'amount' => 20]);
        $transaction->lines()->create(['account_id' => $cash->id, 'type' => 'debit', 'amount' => 20]);
        $transaction->lines()->create(['account_id' => $owner->id, 'type' => 'credit', 'amount' => 20]);

        $result = (new LedgerBridgeService())->post($transaction);

        $this->assertNull($result);
        $this->assertSame(0, JournalEntry::count());
        $this->assertSame(2, TransactionLine::where('transaction_id', $transaction->id)->count());
    }
}
