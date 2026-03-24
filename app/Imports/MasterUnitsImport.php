<?php

namespace App\Imports;

use App\Models\MasterUnit;
use App\Models\Vendor;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MasterUnitsImport implements ToCollection, WithStartRow
{
    public function startRow(): int
    {
        return 3; // Skips header rows from MasterUnitSheet
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $nomorLambung = trim((string)($row[2] ?? ''));
            $vendorName = trim((string)($row[4] ?? ''));

            if (!$nomorLambung || !$vendorName) {
                continue; // Skip silently if missing strictly enforced mappings
            }

            $vendor = Vendor::where('nama_vendor', $vendorName)->first();
            $vendorId = $vendor ? $vendor->id : null;

            // Sync into database
            MasterUnit::updateOrCreate(
                ['nomor_lambung' => $nomorLambung], // Match exact
                [
                    'jenis_unit' => trim((string)($row[3] ?? '-')),
                    'vendor_id'  => $vendorId,
                ]
            );
        }
    }
}
