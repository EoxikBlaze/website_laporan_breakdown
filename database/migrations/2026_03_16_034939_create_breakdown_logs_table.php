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
        Schema::create('breakdown_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('master_units')->onDelete('cascade');
            $table->foreignId('spare_unit_id')->nullable()->constrained('master_units')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('waktu_awal_bd');
            $table->dateTime('waktu_akhir_bd')->nullable();
            $table->dateTime('waktu_spare_datang')->nullable();
            $table->enum('status', ['Open', 'Closed'])->default('Open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breakdown_logs');
    }
};
