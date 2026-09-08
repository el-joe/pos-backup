<?php

namespace App\Services;

use App\Enums\AccountTypeEnum;
use App\Enums\Tenant\ExpenseTypeEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Tenant\Account;
use App\Models\Tenant\Expense;
use App\Models\Tenant\ExpenseAmortisationEntry;
use App\Models\Tenant\Transaction;
use App\Repositories\ExpenseRepository;
use Illuminate\Support\Facades\DB;

class ExpenseService
{
    public function __construct(private ExpenseRepository $repo,private PurchaseService $purchaseService,private TransactionService $transactionService) {}

    function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    function activeList($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter + [
            'active' => 1
        ], $perPage, $orderByDesc);
    }


    function find($id = null, $relations = [])
    {
        return $this->repo->find($id, $relations);
    }

    function save($id = null, $data) {
        return DB::transaction(function () use ($id, $data) {
            if($id) {
                $expense = $this->repo->find($id);
                if($expense && $this->hasPostedTransaction($expense)) {
                    throw new \RuntimeException('This expense is already posted and cannot be edited. Delete it and create a new one instead.');
                }
                if($expense) {
                    $expense->update($data);
                }
            }else{
                $expense = $this->repo->create($data);
            }

            if(!$expense) {
                return $expense;
            }

            if($expense->type->value == ExpenseTypeEnum::ACCRUED->value) {
                return $this->accrue($expense);
            }

            if($expense->type->value == ExpenseTypeEnum::PREPAID->value) {
                return $this->recognisePrepaid($expense, $data);
            }

            return $this->payExpense($expense->id, $expense->total, $data['payment_account'] ?? null);
        });
    }

    /**
     * Whether this expense already has a live (non-reversed) GL posting — used to block a
     * second posting on save() and to decide whether delete() may reverse anything.
     */
    protected function hasPostedTransaction(Expense $expense): bool
    {
        return Transaction::where('reference_type', Expense::class)
            ->where('reference_id', $expense->id)
            ->whereNull('reversed_by_transaction_id')
            ->exists();
    }

    /**
     * Recognises an accrual: DR expense account (+ VAT Receivable) / CR Accrued Expenses
     * (liability), posted at expense_date. The liability is booked tax-inclusive so
     * settleAccrual() can pay it off with a single line.
     */
    function accrue(Expense $expense) {
        if($expense->accrued_at) {
            return $expense;
        }

        $accruedAccount = Account::default('Accrued Expenses', AccountTypeEnum::ACCRUED_EXPENSES->value, $expense->branch_id);
        $expenseAccount = $this->resolveExpenseAccount($expense->branch_id, $expense->expense_category_id);

        $lines = [[
            'account_id' => $expenseAccount->id,
            'type' => 'debit',
            'amount' => $expense->amount,
        ]];

        if($expense->tax_percentage > 0) {
            $taxAmount = ($expense->amount * $expense->tax_percentage) / 100;
            $lines[] = $this->purchaseService->createVatReceivableLine([
                'branch_id' => $expense->branch_id,
                'tax_amount' => $taxAmount,
            ]);
        }

        $lines[] = [
            'account_id' => $accruedAccount->id,
            'type' => 'credit',
            'amount' => $expense->total,
        ];

        $this->transactionService->create([
            'date' => $expense->expense_date,
            'note' => 'Accrued Expense #'. $expense->id,
            'type' => TransactionTypeEnum::EXPENSE->value,
            'reference_type' => Expense::class,
            'reference_id' => $expense->id,
            'branch_id' => $expense->branch_id,
            'amount' => $expense->total,
            'lines' => $lines,
        ]);

        $expense->update(['accrued_at' => now()]);

        return $expense->refresh();
    }

    /**
     * Settles a previously accrued liability: DR Accrued Expenses / CR payment account,
     * for the full (tax-inclusive) amount booked by accrue().
     */
    function settleAccrual(Expense $expense, array $payment = []) {
        if(!$expense->accrued_at) {
            throw new \RuntimeException('This expense has not been accrued yet.');
        }

        if($expense->settled_at) {
            throw new \RuntimeException('This accrued expense has already been settled.');
        }

        $paymentAccount = !empty($payment['payment_account'])
            ? Account::assertPaymentCapable($payment['payment_account'])
            : Account::default('Branch Cash', AccountTypeEnum::BRANCH_CASH->value, $expense->branch_id);

        $accruedAccount = Account::default('Accrued Expenses', AccountTypeEnum::ACCRUED_EXPENSES->value, $expense->branch_id);
        $amount = $expense->total;

        $this->transactionService->create([
            'note' => 'Settle Accrued Expense #'. $expense->id,
            'type' => TransactionTypeEnum::EXPENSE->value,
            'reference_type' => Expense::class,
            'reference_id' => $expense->id,
            'branch_id' => $expense->branch_id,
            'amount' => $amount,
            'lines' => [
                ['account_id' => $accruedAccount->id, 'type' => 'debit', 'amount' => $amount],
                ['account_id' => $paymentAccount->id, 'type' => 'credit', 'amount' => $amount],
            ],
        ]);

        $expense->increment('total_paid', $amount);
        $expense->update(['settled_at' => now()]);

        $cashRegister = app(\App\Services\CashRegisterService::class)->getOpenedCashRegister();
        if ($cashRegister) {
            app(\App\Services\CashRegisterService::class)->increment(
                $cashRegister->id, 'total_expenses', $amount
            );
        }

        return $expense->refresh();
    }

    /**
     * Recognises a prepaid expense as an asset: DR Prepaid Expenses (+ VAT Receivable) /
     * CR payment account. No expense line is posted here — that happens period by period
     * in amortise().
     */
    function recognisePrepaid(Expense $expense, array $data = []) {
        if($expense->total_paid > 0) {
            return $expense;
        }

        $prepaidAccount = Account::default('Prepaid Expenses', AccountTypeEnum::PREPAID_ASSET->value, $expense->branch_id);
        $paymentAccount = !empty($data['payment_account'])
            ? Account::assertPaymentCapable($data['payment_account'])
            : Account::default('Branch Cash', AccountTypeEnum::BRANCH_CASH->value, $expense->branch_id);

        $lines = [[
            'account_id' => $prepaidAccount->id,
            'type' => 'debit',
            'amount' => $expense->amount,
        ]];

        if($expense->tax_percentage > 0) {
            $taxAmount = ($expense->amount * $expense->tax_percentage) / 100;
            $lines[] = $this->purchaseService->createVatReceivableLine([
                'branch_id' => $expense->branch_id,
                'tax_amount' => $taxAmount,
            ]);
        }

        $lines[] = [
            'account_id' => $paymentAccount->id,
            'type' => 'credit',
            'amount' => $expense->total,
        ];

        $this->transactionService->create([
            'date' => $expense->expense_date,
            'note' => 'Prepaid Expense #'. $expense->id,
            'type' => TransactionTypeEnum::EXPENSE->value,
            'reference_type' => Expense::class,
            'reference_id' => $expense->id,
            'branch_id' => $expense->branch_id,
            'amount' => $expense->total,
            'lines' => $lines,
        ]);

        $expense->increment('total_paid', $expense->total);

        $cashRegister = app(\App\Services\CashRegisterService::class)->getOpenedCashRegister();
        if ($cashRegister) {
            app(\App\Services\CashRegisterService::class)->increment(
                $cashRegister->id, 'total_expenses', $expense->total
            );
        }

        return $expense->refresh();
    }

    /**
     * Posts current-period amortisation for every prepaid expense with an amortisation
     * schedule: DR expense account / CR Prepaid Expenses, capped so total amortised never
     * exceeds the amount recognised as prepaid. Idempotent per expense per period via the
     * expense_amortisation_entries unique (expense_id, period_year, period_month) index.
     *
     * @return array<int, array{expense_id:int,branch_id:?int,period:string,status:string,amount:float}>
     */
    function amortise(int $year, int $month, bool $dryRun = true): array
    {
        $period = sprintf('%04d-%02d', $year, $month);
        $report = [];

        $expenses = Expense::where('type', ExpenseTypeEnum::PREPAID->value)
            ->whereNotNull('amortisation_start_date')
            ->whereNotNull('amortisation_months')
            ->where('amortisation_months', '>', 0)
            ->get();

        foreach ($expenses as $expense) {
            $row = [
                'expense_id' => $expense->id,
                'branch_id' => $expense->branch_id,
                'period' => $period,
                'status' => 'skipped',
                'amount' => 0.0,
            ];

            $periodStart = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
            if($periodStart->lt($expense->amortisation_start_date->copy()->startOfMonth())) {
                $row['status'] = 'skipped: before amortisation start';
                $report[] = $row;
                continue;
            }

            $alreadyPosted = ExpenseAmortisationEntry::where('expense_id', $expense->id)
                ->where('period_year', $year)
                ->where('period_month', $month)
                ->exists();

            if($alreadyPosted) {
                $row['status'] = 'skipped: already posted for period';
                $report[] = $row;
                continue;
            }

            $totalAmortised = (float) ExpenseAmortisationEntry::where('expense_id', $expense->id)->sum('amount');
            $remaining = round((float) $expense->amount - $totalAmortised, 2);

            if($remaining <= 0) {
                $row['status'] = 'skipped: fully amortised';
                $report[] = $row;
                continue;
            }

            $monthlyCharge = round((float) $expense->amount / $expense->amortisation_months, 2);
            $charge = min($remaining, $monthlyCharge);

            $row['amount'] = $charge;

            if($dryRun) {
                $row['status'] = 'would post';
                $report[] = $row;
                continue;
            }

            $this->postAmortisation($expense, $year, $month, $charge);
            $row['status'] = 'posted';
            $report[] = $row;
        }

        return $report;
    }

    protected function postAmortisation(Expense $expense, int $year, int $month, float $charge): void
    {
        DB::transaction(function () use ($expense, $year, $month, $charge) {
            $prepaidAccount = Account::default('Prepaid Expenses', AccountTypeEnum::PREPAID_ASSET->value, $expense->branch_id);
            $expenseAccount = $this->resolveExpenseAccount($expense->branch_id, $expense->expense_category_id);

            $transaction = $this->transactionService->create([
                'date' => \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth(),
                'note' => 'Amortise Prepaid Expense #'. $expense->id .' - '. sprintf('%04d-%02d', $year, $month),
                'type' => TransactionTypeEnum::EXPENSE->value,
                'reference_type' => Expense::class,
                'reference_id' => $expense->id,
                'branch_id' => $expense->branch_id,
                'amount' => $charge,
                'lines' => [
                    ['account_id' => $expenseAccount->id, 'type' => 'debit', 'amount' => $charge],
                    ['account_id' => $prepaidAccount->id, 'type' => 'credit', 'amount' => $charge],
                ],
            ]);

            ExpenseAmortisationEntry::create([
                'expense_id' => $expense->id,
                'branch_id' => $expense->branch_id,
                'transaction_id' => $transaction->id,
                'period_year' => $year,
                'period_month' => $month,
                'amount' => $charge,
            ]);
        });
    }

    function payExpense($expenseId, $amount = 0, $paymentAccountId = null) {
        return DB::transaction(function () use ($expenseId, $amount, $paymentAccountId) {
            $expense = $this->repo->find($expenseId);
            if(!$expense) {
                return false;
            }

            if($expense->type->value == ExpenseTypeEnum::ACCRUED->value) {
                return $this->settleAccrual($expense, ['payment_account' => $paymentAccountId]);
            }

            if($this->hasPostedTransaction($expense)) {
                throw new \RuntimeException('This expense is already posted.');
            }

            $newAmount = $amount > 0 ? $amount : $expense->total;
            $remaining = round((float) $expense->total - (float) $expense->total_paid, 2);
            if($newAmount > $remaining) {
                $newAmount = $remaining;
            }
            if($newAmount <= 0) {
                return $expense;
            }

            // $newAmount is tax-inclusive (it's capped against expense->total); expenseLines()
            // expects a pre-tax base and computes its own VAT line, so back the base out here —
            // otherwise tax gets applied twice (once here, once inside expenseLines()).
            $baseAmount = (float) $expense->total > 0
                ? round((float) $expense->amount * ($newAmount / (float) $expense->total), 2)
                : $newAmount;

            $paymentData = [
                'note'=>'Expense #'. $expense->id,
                'type' => TransactionTypeEnum::EXPENSE->value,
                'reference_type' => Expense::class,
                'reference_id' => $expense->id,
                'branch_id' => $expense->branch_id,
                'amount' => $newAmount,
                'lines' => $this->expenseLines([
                    'branch_id' => $expense->branch_id,
                    'expense_category_id' => $expense->expense_category_id,
                    'amount'=> $baseAmount,
                    'tax_percentage' => $expense->tax_percentage,
                    'payment_account' => $paymentAccountId,
                ])
            ];

            $this->transactionService->create($paymentData);

            $expense->increment('total_paid', $newAmount);

            $cashRegister = app(\App\Services\CashRegisterService::class)->getOpenedCashRegister();
            if ($cashRegister) {
                app(\App\Services\CashRegisterService::class)->increment(
                    $cashRegister->id, 'total_expenses', $newAmount
                );
            }

            return $expense->refresh();
        });
    }

    function delete($id) {
        return DB::transaction(function () use ($id) {
            $expense = $this->repo->find($id);
            if(!$expense) {
                return false;
            }

            $transactions = Transaction::where('reference_type', Expense::class)
                ->where('reference_id', $expense->id)
                ->get();

            if($transactions->isEmpty()) {
                throw new \RuntimeException('Cannot delete an expense with no posted transaction.');
            }

            if($transactions->whereNull('reversed_by_transaction_id')->isEmpty()) {
                throw new \RuntimeException('This expense has already been reversed.');
            }

            // Expense::booted()'s deleting hook reverses every un-reversed transaction
            // referencing this expense line-for-line (including any VAT line) via
            // TransactionService::reverse() — never rebuild the reversal from total_paid here.
            $expense->delete();

            return true;
        });
    }

    function expenseLines($data,$reverse = false) {
        $total = $data['amount'];
        $expenseAccount = $this->resolveExpenseAccount(
            branchId: $data['branch_id'],
            expenseCategoryId: $data['expense_category_id'] ?? null,
        );

        $lines[] = [
            'account_id' => $expenseAccount->id,
            'type' => $reverse ? 'credit' : 'debit',
            'amount' => $total,
        ];

        if(isset($data['tax_percentage']) && $data['tax_percentage'] > 0) {
            $taxAmount = ($data['amount'] * $data['tax_percentage']) / 100;
            $lines[] = $this->purchaseService->createVatReceivableLine([
                'branch_id' => $data['branch_id'],
                'tax_amount'=> $taxAmount
            ],$reverse);

            $total += $taxAmount;
        }

        $paymentAccount = !empty($data['payment_account'])
            ? Account::assertPaymentCapable($data['payment_account'])
            : Account::default('Branch Cash', AccountTypeEnum::BRANCH_CASH->value, $data['branch_id']);

        $lines[] = [
            'account_id' => $paymentAccount->id,
            'type' => $reverse ? 'debit' : 'credit',
            'amount' => $total,
        ];

        return $lines;
    }

    protected function resolveExpenseAccount(int $branchId, ?int $expenseCategoryId = null): Account
    {
        return ExpenseAccountResolver::resolve($branchId, $expenseCategoryId);
    }
}
