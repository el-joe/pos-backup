<?php

namespace Tests\Feature\Tenant;

use App\Exceptions\TransactionBalanceException;
use App\Repositories\TransactionRepository;
use App\Services\TransactionService;
use Mockery;
use Tests\TestCase;

class TransactionBalanceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_unbalanced_lines_throw_and_persist_nothing(): void
    {
        $repo = Mockery::mock(TransactionRepository::class);
        $repo->shouldNotReceive('create');

        $service = new TransactionService($repo, new \App\Services\LedgerBridgeService());

        $this->expectException(TransactionBalanceException::class);

        $service->create([
            'type' => 'expense',
            'lines' => [
                ['account_id' => 1, 'type' => 'debit', 'amount' => 100],
                ['account_id' => 2, 'type' => 'credit', 'amount' => 90],
            ],
        ]);
    }

    public function test_empty_lines_throw_and_persist_nothing(): void
    {
        $repo = Mockery::mock(TransactionRepository::class);
        $repo->shouldNotReceive('create');

        $service = new TransactionService($repo, new \App\Services\LedgerBridgeService());

        $this->expectException(TransactionBalanceException::class);

        $service->create([
            'type' => 'expense',
            'lines' => [],
        ]);
    }

    public function test_all_zero_amount_lines_throw_and_persist_nothing(): void
    {
        $repo = Mockery::mock(TransactionRepository::class);
        $repo->shouldNotReceive('create');

        $service = new TransactionService($repo, new \App\Services\LedgerBridgeService());

        $this->expectException(TransactionBalanceException::class);

        $service->create([
            'type' => 'expense',
            'lines' => [
                ['account_id' => 1, 'type' => 'debit', 'amount' => 0],
                ['account_id' => 2, 'type' => 'credit', 'amount' => 0],
            ],
        ]);
    }

    public function test_invalid_line_throws_and_persists_nothing(): void
    {
        $repo = Mockery::mock(TransactionRepository::class);
        $repo->shouldNotReceive('create');

        $service = new TransactionService($repo, new \App\Services\LedgerBridgeService());

        $this->expectException(TransactionBalanceException::class);

        $service->create([
            'type' => 'expense',
            'lines' => [
                false,
                ['account_id' => 1, 'type' => 'debit', 'amount' => 100],
            ],
        ]);
    }
}
