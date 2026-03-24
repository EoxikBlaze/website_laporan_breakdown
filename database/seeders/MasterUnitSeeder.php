<?php

namespace Database\Seeders;

use App\Models\MasterUnit;
use Illuminate\Database\Seeder;

class MasterUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            'ARBILV001', 'ARBILV003', 'ARBILV004', 'ARBILV005', 'ARBILV007',
            'ARBILV101', 'ARBILV102', 'ARBILV103', 'ARBILV121', 'ARBILV302',
            'ARBILV131', 'ARBILV106', 'ARBILV107', 'ARBILV301', 'ARBILV503',
            'ARBILV303', 'ARBIPB114', 'ARBILV505', 'ARBILV008', 'ARBILV009',
            'ARBILV601', 'ARBILV133', 'ARBILV304', 'ARJHLV006', 'ARJHPB104',
            'ARJHPB108', 'ARJHPB109', 'ARJHPB110', 'ARJHPB111', 'ARJHPB112',
            'ARJHPB113', 'ARJHPB116', 'ARJHLV117', 'ARJHLV118', 'ARJHLV119',
            'ARJHPB126', 'ARJHPB129', 'ARJHPB130', 'ARJHLV502', 'ARJHLV504',
            'ARJHLVA01', 'ARJHLVA02', 'ARJHPB115'
        ];

        foreach ($units as $nomor) {
            MasterUnit::updateOrCreate(
                ['nomor_lambung' => $nomor],
                [
                    'jenis_unit' => '-'
                ]
            );
        }
    }
}
