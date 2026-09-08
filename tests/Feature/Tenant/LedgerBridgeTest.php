<?php

namespace Tests\Feature\Tenant;

use App\Enums\AccountTypeEnum;
use App\Exceptions\LedgerBridgeException;
use App\Models\Tenant\Account;
use App\Models\Tenant\Contracting\ChartOfAccount;
use App\Models\Tenant\Contracting\JournalEntry;
use App\Models\Tenant\Contracting\JournalEntryLine;
use App\Models\Tenant\Transaction;
use App\Repositories\TransactionRepository;
use App\Services\LedgerBridgeService;
use App\Services\TransactionService;
use Illuminate\Auth\GenericUser;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LedgerBridgeTest extends TestCase
{
    private const TEST_DATABASE = 'test_ledger_bridge';

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

        ChartOfAccount::create(['code' => '1010', 'name' => 'Cash at Hand', 'type' => 'asset']);
        ChartOfAccount::create(['code' => '3010', 'name' => 'Owner Account', 'type' => 'equity']);

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

    public function test_create_transaction_posts_a_balanced_journal_entry(): void
    {
        [$cash, $owner] = $this->makeAccounts();

        $transaction = $this->transactionService()->create([
            'type' => 'cash_deposit',
            'description' => 'Deposit',
            'lines' => [
                ['account_id' => $cash->id, 'type' => 'debit', 'amount' => 100],
                ['account_id' => $owner->id, 'type' => 'credit', 'amount' => 100],
            ],
        ]);

        $entry = JournalEntry::where('referenceable_type', Transaction::class)
            ->where('referenceable_id', $transaction->id)
            ->first();

        $this->assertNotNull($entry);
        $this->assertSame('posted', $entry->status);
        $this->assertSame('100.00', (string) $entry->total_debit);
        $this->assertSame('100.00', (string) $entry->total_credit);
        $this->assertSame(2, JournalEntryLine::where('journal_entry_id', $entry->id)->count());
    }

    public function test_posting_the_same_transaction_twice_is_idempotent(): void
    {
        [$cash, $owner] = $this->makeAccounts();
        $transaction = $this->transactionService()->create([
            'type' => 'cash_deposit',
            'lines' => [
                ['account_id' => $cash->id, 'type' => 'debit', 'amount' => 50],
                ['account_id' => $owner->id, 'type' => 'credit', 'amount' => 50],
            ],
        ]);

        (new LedgerBridgeService())->post($transaction);
        (new LedgerBridgeService())->post($transaction);

        $this->assertSame(
            1,
            JournalEntry::where('referenceable_type', Transaction::class)
                ->where('referenceable_id', $transaction->id)
                ->count()
        );
    }

    public function test_unmapped_account_type_throws_instead_of_posting_a_partial_entry(): void
    {
        $customer = Account::create(['name' => 'Some Customer', 'type' => AccountTypeEnum::CUSTOMER->value]);
        $owner = Account::create(['name' => 'Owner Account', 'type' => AccountTypeEnum::OWNER_ACCOUNT->value]);

        config(['tenant.account_coa_map' => ['owner_account' => '3010']]);

        $transaction = Transaction::create(['type' => 'cash_deposit', 'amount' => 20]);
        $transaction->lines()->create(['account_id' => $customer->id, 'type' => 'debit', 'amount' => 20]);
        $transaction->lines()->create(['account_id' => $owner->id, 'type' => 'credit', 'amount' => 20]);

        $this->expectException(LedgerBridgeException::class);
        (new LedgerBridgeService())->post($transaction);

        $this->assertSame(0, JournalEntry::count());
        $this->assertSame(0, JournalEntryLine::count());
    }

    public function test_reversal_posts_a_mirrored_journal_entry(): void
    {
        [$cash, $owner] = $this->makeAccounts();
        $service = $this->transactionService();

        $transaction = $service->create([
            'type' => 'cash_deposit',
            'lines' => [
                ['account_id' => $cash->id, 'type' => 'debit', 'amount' => 75],
                ['account_id' => $owner->id, 'type' => 'credit', 'amount' => 75],
            ],
        ]);

        $reversal = $service->reverse($transaction, 'test reversal');

        $reversalEntry = JournalEntry::where('referenceable_type', Transaction::class)
            ->where('referenceable_id', $reversal->id)
            ->first();

        $this->assertNotNull($reversalEntry);
        $this->assertSame('75.00', (string) $reversalEntry->total_debit);
        $this->assertSame('75.00', (string) $reversalEntry->total_credit);

        $cashLine = JournalEntryLine::where('journal_entry_id', $reversalEntry->id)
            ->whereHas('account', fn ($q) => $q->where('code', '1010'))
            ->first();
        $this->assertSame('0.00', (string) $cashLine->debit);
        $this->assertSame('75.00', (string) $cashLine->credit);
    }

    public function test_cost_center_and_project_carry_through_to_the_journal_entry_line(): void
    {
        [$cash, $owner] = $this->makeAccounts();

        $transaction = $this->transactionService()->create([
            'type' => 'cash_deposit',
            'lines' => [
                ['account_id' => $cash->id, 'type' => 'debit', 'amount' => 10, 'cost_center_id' => 5, 'project_id' => 9],
                ['account_id' => $owner->id, 'type' => 'credit', 'amount' => 10],
            ],
        ]);

        $entry = JournalEntry::where('referenceable_id', $transaction->id)->first();
        $cashLine = JournalEntryLine::where('journal_entry_id', $entry->id)
            ->whereHas('account', fn ($q) => $q->where('code', '1010'))
            ->first();

        $this->assertSame(5, $cashLine->cost_center_id);
        $this->assertSame(9, $cashLine->project_id);
    }
}
