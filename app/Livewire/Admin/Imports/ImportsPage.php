<?php

namespace App\Livewire\Admin\Imports;

use App\Enums\AuditLogActionEnum;
use App\Imports\BranchesImport;
use App\Imports\BrandsImport;
use App\Imports\CategoriesImport;
use App\Imports\ProductsImport;
use App\Imports\UnitsImport;
use App\Models\Tenant\AuditLog;
use App\Traits\LivewireOperations;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ImportsPage extends Component
{
    use WithFileUploads,LivewireOperations;

    public $branchesFile;
    public $productsFile;
    public $categoriesFile;
    public $brandsFile;
    public $unitsFile;

    public function importBranches()
    {
        $this->validate([
            'branchesFile' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new BranchesImport, $this->branchesFile->getRealPath());
            AuditLog::log(AuditLogActionEnum::IMPORT_BRANCHES);
            $this->alert('success', 'Branches imported successfully!');
            $this->reset('branchesFile');
        } catch (\Exception $e) {
            $this->alert('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function importProducts()
    {
        $this->validate([
            'productsFile' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new ProductsImport, $this->productsFile->getRealPath());
            AuditLog::log(AuditLogActionEnum::IMPORT_PRODUCTS);
            $this->alert('success', 'Products imported successfully!');
            $this->reset('productsFile');
        } catch (\Exception $e) {
            $this->alert('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function importCategories()
    {
        $this->validate([
            'categoriesFile' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new CategoriesImport, $this->categoriesFile->getRealPath());
            AuditLog::log(AuditLogActionEnum::IMPORT_CATEGORIES);
            $this->alert('success', 'Categories imported successfully!');
            $this->reset('categoriesFile');
        } catch (\Exception $e) {
            $this->alert('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function importBrands()
    {
        $this->validate([
            'brandsFile' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new BrandsImport, $this->brandsFile->getRealPath());
            AuditLog::log(AuditLogActionEnum::IMPORT_BRANDS);
            $this->alert('success', 'Brands imported successfully!');
            $this->reset('brandsFile');
        } catch (\Exception $e) {
            $this->alert('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function importUnits()
    {
        $this->validate([
            'unitsFile' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new UnitsImport, $this->unitsFile->getRealPath());
            AuditLog::log(AuditLogActionEnum::IMPORT_UNITS);
            $this->alert('success', 'Units imported successfully!');
            $this->reset('unitsFile');
        } catch (\Exception $e) {
            $this->alert('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function downloadTemplate($type)
    {
        $templates = [
            'branches' => [
                ['name', 'email', 'phone', 'address', 'website', 'active'],
                ['Main Branch', 'main@example.com', '1234567890', '123 Main St', 'https://example.com', 'YES/NO'],
            ],
            'products' => [
                ['name', 'description', 'sku', 'code', 'unit', 'brand', 'category', 'alert_qty', 'branch', 'weight', 'active', 'taxable'],
                ['Product Name', 'Product Description', 'SKU001', 'CODE001', 'Unit example', 'Brand example', 'Category example', '10', 'Branch example', '0.5 (optional)', 'YES/NO', 'YES/NO'],
            ],
            'categories' => [
                ['name', 'parent', 'icon', 'active'],
                ['Electronics', 'Parent Category (optional)', 'fa fa-laptop (optional)', 'YES/NO'],
            ],
            'brands' => [
                ['name', 'active'],
                ['Brand Name', 'YES/NO'],
            ],
            'units' => [
                ['name', 'parent', 'count', 'active'],
                ['Piece', 'Parent Unit (optional)', '1', 'YES/NO'],
            ],
        ];

        if (!isset($templates[$type])) {
            abort(404);
        }

        return response()->streamDownload(function () use ($templates, $type) {
            $handle = fopen('php://output', 'w');
            foreach ($templates[$type] as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, $type . '_template.csv', ['Content-Type' => 'text/csv']);
    }

    public function render()
    {
        return layoutView('imports.imports')
            ->title('Import Data');
    }
}
