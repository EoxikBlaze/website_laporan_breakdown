<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Super Admin
        User::updateOrCreate(
            ['email' => 'admin@pama.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        // Backup Super Admin
        User::updateOrCreate(
            ['email' => 'pama@gmail.com'],
            [
                'name' => 'PAMA Admin',
                'password' => Hash::make('pama1234'),
                'role' => 'super_admin',
            ]
        );

        // Create Operator
        User::updateOrCreate(
            ['email' => 'operator@pama.com'],
            [
                'name' => 'Petugas Operator',
                'password' => Hash::make('password'),
                'role' => 'operator',
            ]
        );

        // Seed Master Units
        $this->call(MasterUnitSeeder::class);
    }
}
