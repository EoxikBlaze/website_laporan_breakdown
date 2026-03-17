<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = [
            [
                'nama_vendor' => 'PT. Jejak Hasanah', 
                'kontak' => '-', 
                'keterangan' => 'Vendor baru',
                'email' => 'operator.jejak@gsportalaria.com'
            ],
            [
                'nama_vendor' => 'CV. BINA INTI PERSADA', 
                'kontak' => '-', 
                'keterangan' => 'Vendor baru',
                'email' => 'operator.binainti@gsportalaria.com'
            ],
        ];

        foreach ($vendors as $v) {
            $vendor = \App\Models\Vendor::updateOrCreate(
                ['nama_vendor' => $v['nama_vendor']], 
                ['kontak' => $v['kontak'], 'keterangan' => $v['keterangan']]
            );

            // Auto-create operator for each vendor if not exists
            \App\Models\User::updateOrCreate(
                ['email' => $v['email']],
                [
                    'name' => 'Operator ' . $v['nama_vendor'],
                    'password' => \Illuminate\Support\Facades\Hash::make('vendor123'),
                    'role' => 'operator',
                    'vendor_id' => $vendor->id,
                ]
            );
        }
    }
}
