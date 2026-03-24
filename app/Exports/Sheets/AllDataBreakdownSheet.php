<?php

namespace App\Exports\Sheets;

use App\Models\BreakdownLog;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AllDataBreakdownSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, WithStyles, WithEvents, ShouldAutoSize
{
    private $currentRow = 1;

    public function query()
    {
        // Eloquent handles the global vendor scope automatically depending on the logged-in user
        return BreakdownLog::query()
            ->with(['unit', 'unit.vendor', 'spareUnit'])
            ->latest('waktu_awal_bd');
    }

    public function title(): string
    {
        return 'Semua Data';
    }

    public function headings(): array
    {
        return [
            ['No', 'Vendor', 'Unit', 'Status', 'Lama Unit BD', 'Keterangan', 'Waktu Breakdown', '', 'Spare', '', 'Loss Time', ''],
            ['', '', '', '', '', '', 'Awal', 'Akhir', 'Unit', 'Jam Datang', 'Interval', '%'],
            ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12']
        ];
    }

    public function map($log): array
    {
        $awal = Carbon::parse($log->waktu_awal_bd);
        $akhir = $log->waktu_akhir_bd ? Carbon::parse($log->waktu_akhir_bd) : null;
        
        $vendorName = $log->unit && $log->unit->vendor ? $log->unit->vendor->nama_vendor : ($log->vendor ? $log->vendor->nama_vendor : 'Internal');
        
        return [
            $this->currentRow++,
            $vendorName,
            $log->unit->nomor_lambung ?? '-',
            $log->status,
            $log->spare_unit_id ? ($log->lama_unit_breakdown ?? '-') : '-',
            $log->keterangan,
            $awal->format('d M Y H.i'),
            $akhir ? $akhir->format('d M Y H.i') : '-',
            $log->spare_unit_id ? ($log->spareUnit->nomor_lambung ?? '-') : '-',
            $log->waktu_spare_datang ? Carbon::parse($log->waktu_spare_datang)->format('d M Y H.i') : '-',
            $log->loss_time ?? '-',
            $log->loss_time_percentage ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']]],
            2 => ['font' => ['bold' => true, 'color' => ['rgb' => '000000']]],
            3 => ['font' => ['italic' => true], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Merging cells for header
                $sheet->mergeCells('A1:A2'); // No
                $sheet->mergeCells('B1:B2'); // Vendor
                $sheet->mergeCells('C1:C2'); // Unit
                $sheet->mergeCells('D1:D2'); // Status
                $sheet->mergeCells('E1:E2'); // Lama Unit BD
                $sheet->mergeCells('F1:F2'); // Keterangan
                $sheet->mergeCells('G1:H1'); // Waktu Breakdown
                $sheet->mergeCells('I1:J1'); // Spare
                $sheet->mergeCells('K1:L1'); // Loss Time

                // Styling headers
                $headerRange = 'A1:L2';
                $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($headerRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                
                // Color for row 1 (Blueish like screenshot)
                $sheet->getStyle('A1:L1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4F81BD');
                
                // Specific yellow color for Loss Time column in row 1
                $sheet->getStyle('K1:L1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
                $sheet->getStyle('K1:L1')->getFont()->getColor()->setARGB('FF000000'); // Black font for yellow background

                // Color for row 3 (Greenish reference numbers)
                $sheet->getStyle('A3:L3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF92D050');
                
                // Borders for the whole table
                $lastRow = $sheet->getHighestRow();
                if ($lastRow >= 1) {
                    $sheet->getStyle("A1:L$lastRow")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                }

                // Set column widths for multiline keterangan
                $sheet->getColumnDimension('F')->setWidth(50);
                $sheet->getStyle("F1:F$lastRow")->getAlignment()->setWrapText(true);
            },
        ];
    }
}
