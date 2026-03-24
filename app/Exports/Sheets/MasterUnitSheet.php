<?php

namespace App\Exports\Sheets;

use App\Models\MasterUnit;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class MasterUnitSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private $currentRow = 1;

    public function query()
    {
        // Eloquent handles the global vendor scope automatically depending on the logged-in user
        return MasterUnit::query()->with('vendor')->latest('id');
    }

    public function title(): string
    {
        return 'Data Unit';
    }

    public function headings(): array
    {
        return [
            ['Data Master Unit'],
            ['No', 'ID', 'Nomor Lambung', 'Jenis Unit', 'Vendor Terkait']
        ];
    }

    public function map($unit): array
    {
        return [
            $this->currentRow++,
            $unit->id,
            $unit->nomor_lambung,
            $unit->jenis_unit,
            $unit->vendor ? $unit->vendor->nama_vendor : 'Internal'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1:E1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal('center');
        
        $sheet->getStyle('A2:E2')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFFFF'));
        $sheet->getStyle('A2:E2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4F81BD');
        
        if ($lastRow >= 2) {
            $sheet->getStyle("A2:E$lastRow")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        return [];
    }
}
