<?php

namespace App\Imports;

use App\Models\Vendor;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class VendorsImport implements ToCollection, WithStartRow
{
    public function startRow(): int
    {
        return 3;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $vendorName = trim((string)($row[2] ?? ''));

            if (!$vendorName) continue;

            Vendor::firstOrCreate([
                'nama_vendor' => $vendorName
            ]);
        }
    }
}
