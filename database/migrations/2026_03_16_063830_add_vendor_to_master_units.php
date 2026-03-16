<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('master_units', function (Blueprint $table) {
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->onDelete('set null');
        });

        // Auto-link based on prefix
        $binaInti = DB::table('vendors')->where('nama_vendor', 'LIKE', '%BINA INTI%')->first();
        $jejakHasanah = DB::table('vendors')->where('nama_vendor', 'LIKE', '%JEJAK HASANAH%')->first();

        if ($binaInti) {
            DB::table('master_units')->where('nomor_lambung', 'LIKE', 'ARBI%')->update(['vendor_id' => $binaInti->id]);
        }
        if ($jejakHasanah) {
            DB::table('master_units')->where('nomor_lambung', 'LIKE', 'ARJH%')->update(['vendor_id' => $jejakHasanah->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_units', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);
            $table->dropColumn('vendor_id');
        });
    }
};
