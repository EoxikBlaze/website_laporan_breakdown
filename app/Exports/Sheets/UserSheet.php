<?php

namespace App\Exports\Sheets;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class UserSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private $currentRow = 1;

    public function query()
    {
        return User::query()->with('vendor')->latest('id');
    }

    public function title(): string
    {
        return 'Data Akses User';
    }

    public function headings(): array
    {
        return [
            ['Data Manajemen Akses User'],
            ['No', 'ID', 'Nama Lengkap', 'Alamat Email', 'Role (Akses)', 'Vendor / Instansi']
        ];
    }

    public function map($user): array
    {
        return [
            $this->currentRow++,
            $user->id,
            $user->name,
            $user->email,
            $user->role === 'super_admin' ? 'Super Admin' : 'Admin Vendor',
            $user->vendor ? $user->vendor->nama_vendor : 'GS Management (Internal)'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1:F1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal('center');
        
        $sheet->getStyle('A2:F2')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFFFF'));
        $sheet->getStyle('A2:F2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4F81BD');
        
        if ($lastRow >= 2) {
            $sheet->getStyle("A2:F$lastRow")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        return [];
    }
}
