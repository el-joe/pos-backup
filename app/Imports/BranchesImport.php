<?php

namespace App\Imports;

use App\Models\Subscription;
use App\Models\Tenant\Branch;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BranchesImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection($rows)
    {
        $currentSubscription = Subscription::currentTenantSubscriptions()->first();
        $limit = $currentSubscription?->plan?->featureValue('branches', 999999) ?? 999999;
        $totalBranches = Branch::count() + count($rows);

        if($totalBranches > $limit){
            throw new \Exception("Branch limit exceeded. Your current plan allows a maximum of {$limit} branches.");
        }
        foreach($rows as $row) {
            Branch::create([
                'name' => $row['name'],
                'email' => $row['email'] ?? null,
                'phone' => $row['phone'] ?? null,
                'address' => $row['address'] ?? null,
                'website' => $row['website'] ?? null,
                'tax_id' => $row['tax_id'] ?? null,
                'active' => ($row['active'] ?? 'NO') == 'YES',
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|max:50',
            'address' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
            'tax_id' => 'nullable|exists:taxes,id',
            'active' => 'nullable|in:YES,NO',
        ];
    }

    // chunk
    public function chunkSize(): int
    {
        return 100;
    }
}
