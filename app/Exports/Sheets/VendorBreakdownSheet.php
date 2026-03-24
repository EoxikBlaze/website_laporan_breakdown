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

class VendorBreakdownSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, WithStyles, WithEvents, ShouldAutoSize
{
    private $vendorId;
    private $vendorName;
    private $currentRow = 1;

    public function __construct($vendorId, $vendorName)
    {
        $this->vendorId = $vendorId;
        $this->vendorName = $vendorName;
    }

    public function query()
    {
        return BreakdownLog::query()
            ->with(['unit', 'spareUnit'])
            ->where('vendor_id', $this->vendorId)
            ->latest('waktu_awal_bd');
    }

    public function title(): string
    {
        return $this->vendorName;
    }

    public function headings(): array
    {
        return [
            ['No', 'Unit', 'Status', 'Lama Unit BD', 'Keterangan', 'Waktu Breakdown', '', 'Spare', '', 'Loss Time', ''],
            ['', '', '', '', '', 'Awal', 'Akhir', 'Unit', 'Jam Datang', 'Interval', '%'],
            ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11']
        ];
    }

    public function map($log): array
    {
        $awal = Carbon::parse($log->waktu_awal_bd);
        $akhir = $log->waktu_akhir_bd ? Carbon::parse($log->waktu_akhir_bd) : null;
        
        return [
            $this->currentRow++,
            $log->unit->nomor_lambung,
            $log->status,
            $log->spare_unit_id ? ($log->lama_unit_breakdown ?? '-') : '-',
            $log->keterangan,
            $awal->format('d M Y H.i'),
            $akhir ? $akhir->format('d M Y H.i') : '-',
            $log->spareUnit->nomor_lambung ?? '-',
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
                $sheet->mergeCells('B1:B2'); // Unit
                $sheet->mergeCells('C1:C2'); // Status
                $sheet->mergeCells('D1:D2'); // Lama Unit BD
                $sheet->mergeCells('E1:E2'); // Keterangan
                $sheet->mergeCells('F1:G1'); // Waktu Breakdown
                $sheet->mergeCells('H1:I1'); // Spare
                $sheet->mergeCells('J1:K1'); // Loss Time

                // Styling headers
                $headerRange = 'A1:K2';
                $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($headerRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                
                // Color for row 1 (Blueish like screenshot)
                $sheet->getStyle('A1:K1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4F81BD');
                
                // Specific yellow color for Loss Time column in row 1
                $sheet->getStyle('J1:K1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
                $sheet->getStyle('J1:K1')->getFont()->getColor()->setARGB('FF000000'); // Black font for yellow background

                // Color for row 3 (Greenish reference numbers)
                $sheet->getStyle('A3:K3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF92D050');
                
                // Borders for the whole table (example for first 100 rows)
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A1:K$lastRow")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Set column widths for multiline keterangan
                $sheet->getColumnDimension('E')->setWidth(50);
                $sheet->getStyle("E1:E$lastRow")->getAlignment()->setWrapText(true);
            },
        ];
    }
}
