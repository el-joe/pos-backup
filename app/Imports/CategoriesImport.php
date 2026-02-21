<?php

namespace App\Imports;

use App\Models\Tenant\Category;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CategoriesImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection($rows)
    {
        foreach($rows as $row){
            $parentId = Category::where('name', $row['parent'] ?? '')->first()?->id;
            Category::create([
                'name' => $row['name'],
                'parent_id' => $parentId,
                'icon' => $row['icon'] ?? null,
                'active' => ($row['active'] ?? 'NO') == 'YES',
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'parent' => 'nullable|string|exists:categories,name',
            'icon' => 'nullable|string|max:255',
            'active' => 'nullable|in:YES,NO',
        ];
    }
}
