<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UsersImport implements ToCollection, WithStartRow
{
    public function startRow(): int
    {
        return 3;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $email = trim((string)($row[3] ?? ''));
            $roleStr = trim((string)($row[4] ?? ''));
            $vendorName = trim((string)($row[5] ?? ''));

            if (!$email) continue;

            $role = ($roleStr === 'Super Admin') ? 'super_admin' : 'vendor_admin';
            $vendorId = null;

            if ($role === 'vendor_admin') {
                if (!$vendorName) continue; // Vendor admin lacks vendor string
                
                $vendor = Vendor::where('nama_vendor', $vendorName)->first();
                if (!$vendor) {
                    continue; // Skip silently if invalid vendor target string
                }
                $vendorId = $vendor->id;
            }

            // Update or Create the user
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => trim((string)($row[2] ?? 'User')),
                    'role' => $role,
                    'vendor_id' => $vendorId,
                    'password' => Hash::needsRehash(User::where('email', $email)->value('password') ?? '') 
                                    ? Hash::make('password123') 
                                    : (User::where('email', $email)->value('password') ?? Hash::make('password123')), // Only set default password if new
                ]
            );
        }
    }
}
