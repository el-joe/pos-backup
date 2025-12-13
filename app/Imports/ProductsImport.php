<?php

namespace App\Imports;

use App\Models\Subscription;
use App\Models\Tenant\Branch;
use App\Models\Tenant\Brand;
use App\Models\Tenant\Category;
use App\Models\Tenant\Product;
use App\Models\Tenant\Unit;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection($rows)
    {
        $currentSubscription = Subscription::currentTenantSubscriptions()->first();
        $limit = $currentSubscription?->plan?->features['products']['limit'] ?? 999999;
        $totalProducts = Product::count() + count($rows);

        if($totalProducts > $limit){
            throw new \Exception("Product limit exceeded. Your current plan allows a maximum of {$limit} products.");
        }

        foreach($rows as $row) {
            $unitId = Unit::where('name', $row['unit'] ?? '')->first()?->id;
            $brandId = Brand::where('name', $row['brand'] ?? '')->first()?->id;
            $branchId = Branch::where('name', $row['branch'] ?? '')->first()?->id;
            $categoryId = Category::where('name', $row['category'] ?? '')->first()?->id;

            Product::create([
                'name' => $row['name'],
                'description' => $row['description'],
                'sku' => $row['sku'],
                'code' => $row['code'],
                'unit_id' => $unitId,
                'brand_id' => $brandId,
                'branch_id' => $branchId,
                'category_id' => $categoryId,
                'weight' => $row['weight'] ?? null,
                'alert_qty' => $row['alert_qty'],
                'active' => ($row['active'] ?? 'NO') == 'YES',
                'taxable' => ($row['taxable'] ?? 'NO') == 'YES',
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'sku' => 'required|string|max:255',
            'code' => 'required|string|max:100',
            'unit' => 'required|exists:units,name',
            'brand' => 'required|exists:brands,name',
            'branch' => 'nullable|exists:branches,name',
            'category' => 'required|exists:categories,name',
            'weight' => 'nullable|numeric',
            'alert_qty' => 'required|integer',
            'active' => 'nullable|in:YES,NO',
            'taxable' => 'nullable|in:YES,NO',
        ];
    }
}
