<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\MasterUnit;
use App\Models\BreakdownLog;
use App\Models\Vendor;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            // Ensure we can see all data regardless of global scopes
            // Logic: Move units/logs from old IDs (1, 2) to new IDs (4, 5) which are linked to user accounts.
            
            // 1. Move units from V1 (Old CV Bina) -> V5 (Active CV Bina)
            DB::table('master_units')
                ->where('vendor_id', 1)
                ->update(['vendor_id' => 5]);

            // 2. Move units from V2 (Old PT Jejak) -> V4 (Active PT Jejak)
            DB::table('master_units')
                ->where('vendor_id', 2)
                ->update(['vendor_id' => 4]);

            // 3. Move logs from V1 -> V5
            DB::table('breakdown_logs')
                ->where('vendor_id', 1)
                ->update(['vendor_id' => 5]);

            // 4. Move logs from V2 -> V4
            DB::table('breakdown_logs')
                ->where('vendor_id', 2)
                ->update(['vendor_id' => 4]);

            // 5. Delete redundant Vendors (1, 2, 3)
            DB::table('vendors')
                ->whereIn('id', [1, 2, 3])
                ->delete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Data migrations are typically non-reversible in a simple way
    }
};
