<?php

namespace App\Exports;

use App\Exports\Sheets\VendorBreakdownSheet;
use App\Models\Vendor;
use App\Models\BreakdownLog;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BreakdownLogExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        
        // Get all vendors that have logs
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
