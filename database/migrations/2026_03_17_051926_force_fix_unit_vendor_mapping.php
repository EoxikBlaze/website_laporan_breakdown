<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            // Force assign CV BINA INTI PERSADA (ID 5) for units starting with ARBI
            DB::table('master_units')
                ->where('nomor_lambung', 'LIKE', 'ARBI%')
                ->whereNull('vendor_id')
                ->update(['vendor_id' => 5]);

            // Force assign PT JEJAK HASANAH (ID 4) for units starting with ARJH
            DB::table('master_units')
                ->where('nomor_lambung', 'LIKE', 'ARJH%')
                ->whereNull('vendor_id')
                ->update(['vendor_id' => 4]);
            
            // Clean up any other potential orphans that should have been mapped
            // (Case insensitive check just to be safe)
            DB::table('master_units')
                ->where('nomor_lambung', 'LIKE', 'arbi%')
                ->whereNull('vendor_id')
                ->update(['vendor_id' => 5]);

            DB::table('master_units')
                ->where('nomor_lambung', 'LIKE', 'arjh%')
                ->whereNull('vendor_id')
                ->update(['vendor_id' => 4]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy rollback for data migrations
    }
};
