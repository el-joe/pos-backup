<?php

namespace App\Imports;

use App\Services\UserService;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomersImport implements ToCollection, WithHeadingRow, WithValidation
{
    private $userService;

    public function __construct()
    {
        $this->userService = app(UserService::class);
    }

    public function collection($rows)
    {
        foreach($rows as $row) {
            // Check if customer already exists
            if($this->userService->checkIfUserExistsIntoSameType($row['email'] ?? '', 'customer') ||
               $this->userService->checkIfUserExistsIntoSameType($row['phone'] ?? '', 'customer')) {
                continue; // Skip existing customers
            }

            $this->userService->save(null, [
                'name' => $row['name'],
                'email' => $row['email'] ?? null,
                'phone' => $row['phone'] ?? null,
                'address' => $row['address'] ?? null,
                'sales_threshold' => $row['sales_threshold'] ?? 0,
                'type' => 'customer',
                'active' => ($row['active'] ?? 'NO') == 'YES',
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'sales_threshold' => 'nullable|numeric|min:0',
            'active' => 'nullable|in:YES,NO',
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
