<?php

namespace App\Imports;

use App\Models\Tenant\Unit;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UnitsImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection($rows)
    {
        foreach($rows as $row){
            $parentId = Unit::where('name', $row['parent'] ?? '')->first()?->id;
            Unit::create([
                'name' => $row['name'],
                'parent_id' => $parentId,
                'count' => $row['count'] ?? 1,
                'active' => ($row['active'] ?? 'NO') == 'YES',
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'parent' => 'nullable|string|exists:units,name',
            'count' => 'required|numeric',
            'active' => 'nullable|in:YES,NO',
        ];
    }
}
