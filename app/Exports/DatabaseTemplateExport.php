<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DatabaseTemplateExport implements FromArray, WithTitle, ShouldAutoSize, WithStyles
{
    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function array(): array
    {
        switch ($this->type) {
            case 'breakdowns': // Starts reading at row 4
                return [
                    ['TEMPLATE IMPORT LAPORAN BREAKDOWN (JANGAN UBAH SUSUNAN KOLOM)', '', '', '', '', '', '', '', '', '', '', ''],
                    ['No', 'Vendor', 'Unit', 'Keterangan', 'Waktu Awal', 'Waktu Akhir', 'Spare Unit', 'Jam Datang', 'Loss Time', '%', 'Status', 'Lama Unit BD'],
                    ['(Abaikan)', '(Wajib Valid)', '(Wajib Valid)', '(Bebas)', '(Format: 24 Mar 2026 14.00)', '(- jika belum)', '(- jika tidak ada)', '(-)', '(Otomatis)', '(Otomatis)', '(Otomatis)', '(Otomatis)'],
                    ['1', 'CV BINA S', 'ARPM-103', 'Contoh Keterangan Ban Bocor', '24 Mar 2026 08.00', '25 Mar 2026 12.00', 'ARPM-001', '-', '-', '-', '-', '-']
                ];
            case 'units': // Starts reading at row 3
                return [
                    ['TEMPLATE IMPORT DATA MASTER UNIT (JANGAN UBAH SUSUNAN KOLOM)', '', '', '', ''],
                    ['NO', 'KODE', 'NOMOR LAMBUNG', 'JENIS UNIT', 'NAMA VENDOR'],
                    ['1', '-', 'ARPM-103', 'Dump Truck', 'CV BINA S']
                ];
            case 'users': // Starts reading at row 3
                return [
                    ['TEMPLATE IMPORT DATA USER (JANGAN UBAH SUSUNAN KOLOM)', '', '', '', '', ''],
                    ['NO', 'KODE', 'NAMA LENGKAP', 'EMAIL', 'ROLE', 'VENDOR (Khusus role Vendor)'],
                    ['1', '-', 'Nama Contoh', 'contoh@gmail.com', 'vendor_admin', 'CV BINA S']
                ];
            case 'vendors': // Starts reading at row 3
                return [
                    ['TEMPLATE IMPORT DATA VENDOR (JANGAN UBAH SUSUNAN KOLOM)', '', '', '', ''],
                    ['NO', 'KODE', 'NAMA VENDOR', 'PIC', 'NO HP'],
                    ['1', '-', 'CV BINA S', '-', '-']
                ];
            default:
                return [];
        }
    }

    public function title(): string
    {
        return 'Template ' . ucfirst($this->type);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1:E1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD9534F'); // Red warning header
        
        $sheet->getStyle('A2:L2')->getFont()->setBold(true);
        $sheet->getStyle('A2:L2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4F81BD'); // Blue table header
        $sheet->getStyle('A2:L2')->getFont()->getColor()->setARGB('FFFFFFFF');
        
        $sheet->getStyle('A3:L3')->getFont()->setItalic(true); // Helper text row
        
        return [];
    }
}
