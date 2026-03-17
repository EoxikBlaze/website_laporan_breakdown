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
        // 1. Seed Vendors FIRST so they exist for user foreign keys
        $this->call(VendorSeeder::class);

        // 2. Admin Super Admin
        User::updateOrCreate(
            ['email' => 'admin@gsportalaria.up.railway.app'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('persada123789'),
                'role' => 'super_admin',
            ]
        );

        // 3. PAMA Super Admin
        User::updateOrCreate(
            ['email' => 'pama@gsportalaria.up.railway.app'],
            [
                'name' => 'PAMA Admin',
                'password' => Hash::make('persada123789'),
                'role' => 'super_admin',
            ]
        );

        // 4. Dynamic Vendor Admin Lookup
        $vendorBina = \App\Models\Vendor::where('nama_vendor', 'CV. BINA INTI PERSADA')->first();
        if ($vendorBina) {
            User::updateOrCreate(
                ['email' => 'cv.bina@gsportalaria.up.railway.app'],
                [
                    'name' => 'Admin CV Bina',
                    'password' => Hash::make('persada123789'),
                    'role' => 'vendor_admin',
                    'vendor_id' => $vendorBina->id,
                ]
            );
        }

        $vendorJejak = \App\Models\Vendor::where('nama_vendor', 'PT. Jejak Hasanah')->first();
        if ($vendorJejak) {
            User::updateOrCreate(
                ['email' => 'pt.jejak@gsportalaria.up.railway.app'],
                [
                    'name' => 'Admin PT Jejak',
                    'password' => Hash::make('persada123789'),
                    'role' => 'vendor_admin',
                    'vendor_id' => $vendorJejak->id,
                ]
            );
        }

        // 5. General Operator
        User::updateOrCreate(
            ['email' => 'operator@gsportalaria.up.railway.app'],
            [
                'name' => 'Operator Lapangan',
                'password' => Hash::make('tiadahari489'),
                'role' => 'operator',
            ]
        );

        // 6. Seed Master Units
        $this->call(MasterUnitSeeder::class);
    }
}
