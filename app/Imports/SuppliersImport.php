<?php

namespace App\Imports;

use App\Services\UserService;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SuppliersImport implements ToCollection, WithHeadingRow, WithValidation
{
    private $userService;

    public function __construct()
    {
        $this->userService = app(UserService::class);
    }

    public function collection($rows)
    {
        foreach($rows as $row) {
            // Check if supplier already exists
            if($this->userService->checkIfUserExistsIntoSameType($row['email'] ?? '', 'supplier') ||
               $this->userService->checkIfUserExistsIntoSameType($row['phone'] ?? '', 'supplier')) {
                continue; // Skip existing suppliers
            }

            $this->userService->save(null, [
                'name' => $row['name'],
                'email' => $row['email'] ?? null,
                'phone' => $row['phone'] ?? null,
                'address' => $row['address'] ?? null,
                'type' => 'supplier',
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
            'active' => 'nullable|in:YES,NO',
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
