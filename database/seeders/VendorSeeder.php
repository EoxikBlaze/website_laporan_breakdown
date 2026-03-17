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
            ['nama_vendor' => 'PT. Jejak Hasanah', 'kontak' => '-', 'keterangan' => 'Vendor baru'],
            ['nama_vendor' => 'CV. BINA INTI PERSADA', 'kontak' => '-', 'keterangan' => 'Vendor baru'],
        ];

        foreach ($vendors as $vendor) {
            Vendor::firstOrCreate(['nama_vendor' => $vendor['nama_vendor']], $vendor);
        }
    }
}
