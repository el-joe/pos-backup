<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Resources\Tenant\ExpenseResource;
use App\Models\Tenant\Expense;
use Illuminate\Http\Request;

class ExpensesApiController extends ApiController
{
    protected function permission(): string
    {
        return 'expenses.list,expenses.create,expenses.update,expenses.delete';
    }

    public function index(Request $request)
    {
        $expenses = Expense::with(['category', 'branch'])
            ->filter([
                'branch_id' => $request->query('branch_id'),
                'expense_category_id' => $request->query('expense_category_id'),
                'date_from' => $request->query('from_date'),
                'date_to' => $request->query('to_date'),
            ])
            ->orderByDesc('id')
            ->paginate($this->perPage($request));

        return $this->paginated($expenses, ExpenseResource::class);
    }

    public function show(int $id)
    {
        $expense = Expense::with(['category', 'branch'])->find($id);
        if (!$expense) {
            return $this->error('Not Found', 404);
        }

        return $this->success(new ExpenseResource($expense));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'nullable|exists:branches,id',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'tax_percentage' => 'nullable|numeric',
            'expense_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $validated['created_by'] = admin()->id ?? null;
        $expense = Expense::create($validated);

        return $this->success(new ExpenseResource($expense->load(['category', 'branch'])), 201);
    }

    public function update(Request $request, int $id)
    {
        $expense = Expense::find($id);
        if (!$expense) {
            return $this->error('Not Found', 404);
        }

        $validated = $request->validate([
            'branch_id' => 'nullable|exists:branches,id',
            'expense_category_id' => 'sometimes|required|exists:expense_categories,id',
            'amount' => 'sometimes|required|numeric|min:0',
            'tax_percentage' => 'nullable|numeric',
            'expense_date' => 'sometimes|required|date',
            'note' => 'nullable|string',
        ]);

        $expense->update($validated);

        return $this->success(new ExpenseResource($expense->load(['category', 'branch'])));
    }

    public function destroy(int $id)
    {
        $expense = Expense::find($id);
        if (!$expense) {
            return $this->error('Not Found', 404);
        }

        $expense->delete();

        return $this->success(null);
    }
}
