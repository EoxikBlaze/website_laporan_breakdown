<?php

namespace App\Exports;

use App\Exports\Sheets\VendorBreakdownSheet;
use App\Exports\Sheets\AllDataBreakdownSheet;
use App\Models\Vendor;
use App\Models\BreakdownLog;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BreakdownLogExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        
        // 1. Master Sheet: Semua Data (Includes ALL authorized records for the user)
        $sheets[] = new AllDataBreakdownSheet();
        
        // 2. Vendor Specific Sheets
        $vendors = Vendor::whereHas('breakdownLogs')->get();
        
        foreach ($vendors as $vendor) {
            $sheets[] = new VendorBreakdownSheet($vendor->id, $vendor->nama_vendor);
        }
        
        // Check for logs with no vendor (Internal)
        $hasInternalLogs = BreakdownLog::whereNull('vendor_id')->exists();
        if ($hasInternalLogs) {
            $sheets[] = new VendorBreakdownSheet(null, 'Internal');
        }

        return $sheets;
    }
}
