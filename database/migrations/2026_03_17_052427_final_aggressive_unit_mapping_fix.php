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
        // Moved mapping logic to MasterUnit model saving hook and MasterUnitSeeder
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
