<?php

namespace App\Exports\Sheets;

use App\Models\Vendor;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class VendorSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private $currentRow = 1;

    public function query()
    {
        return Vendor::query()->latest('id');
    }

    public function title(): string
    {
        return 'Data Vendor';
    }

    public function headings(): array
    {
        return [
            ['Data Profil Vendor Mitra'],
            ['No', 'ID Database', 'Nama Vendor Terdaftar']
        ];
    }

    public function map($vendor): array
    {
        return [
            $this->currentRow++,
            $vendor->id,
            $vendor->nama_vendor
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:C1')->getAlignment()->setHorizontal('center');
        
        $sheet->getStyle('A2:C2')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFFFF'));
        $sheet->getStyle('A2:C2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4F81BD');
        
        if ($lastRow >= 2) {
            $sheet->getStyle("A2:C$lastRow")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        return [];
    }
}
