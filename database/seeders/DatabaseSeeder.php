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
        // 1. Admin Super Admin
        User::updateOrCreate(
            ['email' => 'admin@gsportalaria.up.railway.app'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('persada123789'),
                'role' => 'super_admin',
            ]
        );

        // 2. PAMA Super Admin
        User::updateOrCreate(
            ['email' => 'pama@gsportalaria.up.railway.app'],
            [
                'name' => 'PAMA Admin',
                'password' => Hash::make('persada123789'),
                'role' => 'super_admin',
            ]
        );

        // 3. User CV Bina (Vendor Admin)
        User::updateOrCreate(
            ['email' => 'cv.bina@gsportalaria.up.railway.app'],
            [
                'name' => 'Admin CV Bina',
                'password' => Hash::make('persada123789'),
                'role' => 'vendor_admin',
                'vendor_id' => 5,
            ]
        );

        // 4. User PT Jejak (Vendor Admin)
        User::updateOrCreate(
            ['email' => 'pt.jejak@gsportalaria.up.railway.app'],
            [
                'name' => 'Admin PT Jejak',
                'password' => Hash::make('persada123789'),
                'role' => 'vendor_admin',
                'vendor_id' => 4,
            ]
        );

        // 5. General Operator
        User::updateOrCreate(
            ['email' => 'operator@gsportalaria.up.railway.app'],
            [
                'name' => 'Operator Lapangan',
                'password' => Hash::make('tiadahari489'),
                'role' => 'operator',
            ]
        );

        // Seed Vendors
        $this->call(VendorSeeder::class);

        // Seed Master Units
        $this->call(MasterUnitSeeder::class);
    }
}
