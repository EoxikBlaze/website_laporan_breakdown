<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite doesn't support MODIFY COLUMN, so we:
        // 1) Add a new temporary column
        // 2) Copy & remap data
        // 3) Drop old column
        // 4) Rename new column

        Schema::table('users', function (Blueprint $table) {
            $table->string('role_new')->default('operator')->after('email');
        });

        DB::table('users')->where('role', 'admin')->update(['role_new' => 'super_admin']);
        DB::table('users')->where('role', 'penginput')->update(['role_new' => 'operator']);
        // In case some already have the new values (e.g. re-running or manual changes):
        DB::table('users')->whereIn('role', ['super_admin', 'operator'])->eachById(function ($user) {
            DB::table('users')->where('id', $user->id)->update(['role_new' => $user->role]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('role_new', 'role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role_old')->default('penginput')->after('email');
        });

        DB::table('users')->where('role', 'super_admin')->update(['role_old' => 'admin']);
        DB::table('users')->where('role', 'operator')->update(['role_old' => 'penginput']);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('role_old', 'role');
        });
    }
};
