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
        Schema::table('breakdown_logs', function (Blueprint $table) {
            $table->foreignId('vendor_id')->nullable()->after('spare_unit_id')->constrained('vendors')->onDelete('set null');
            $table->text('keterangan')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('breakdown_logs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('vendor_id');
            $table->dropColumn('keterangan');
        });
    }
};
