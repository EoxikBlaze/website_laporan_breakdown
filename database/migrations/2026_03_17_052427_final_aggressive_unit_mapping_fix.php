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
        $arbiUnits = [
            'ARBILV001', 'ARBILV003', 'ARBILV004', 'ARBILV005', 'ARBILV007',
            'ARBILV101', 'ARBILV102', 'ARBILV103', 'ARBILV121', 'ARBILV302',
            'ARBILV131', 'ARBILV106', 'ARBILV107', 'ARBILV301', 'ARBILV503',
            'ARBILV303', 'ARBIPB114', 'ARBILV505', 'ARBILV008', 'ARBILV009',
            'ARBILV601', 'ARBILV133', 'ARBILV304'
        ];

        $arjhUnits = [
            'ARJHLV006', 'ARJHPB104', 'ARJHPB108', 'ARJHPB109', 'ARJHPB110',
            'ARJHPB111', 'ARJHPB112', 'ARJHPB113', 'ARJHPB116', 'ARJHLV117',
            'ARJHLV118', 'ARJHLV119', 'ARJHPB126', 'ARJHPB129', 'ARJHPB130',
            'ARJHLV502', 'ARJHLV504', 'ARJHLVA01', 'ARJHLVA02', 'ARJHPB115'
        ];

        DB::transaction(function () use ($arbiUnits, $arjhUnits) {
            // Update ARBI -> Vendor 5 (CV. BINA INTI PERSADA)
            DB::table('master_units')
                ->whereIn('nomor_lambung', $arbiUnits)
                ->update(['vendor_id' => 5]);

            // Update ARJH -> Vendor 4 (PT. Jejak Hasanah)
            DB::table('master_units')
                ->whereIn('nomor_lambung', $arjhUnits)
                ->update(['vendor_id' => 4]);

            // Final catch-all for any missed ones by prefix
            DB::table('master_units')
                ->where('nomor_lambung', 'LIKE', 'ARBI%')
                ->whereNull('vendor_id')
                ->update(['vendor_id' => 5]);

            DB::table('master_units')
                ->where('nomor_lambung', 'LIKE', 'ARJH%')
                ->whereNull('vendor_id')
                ->update(['vendor_id' => 4]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
