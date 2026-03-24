<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BreakdownLogsImport;
use App\Imports\MasterUnitsImport;
use App\Imports\UsersImport;
use App\Imports\VendorsImport;

class DatabaseImportController extends Controller
{
    /**
     * Display the universal import dashboard UI.
     */
    public function index()
    {
        Gate::authorize('manage-users'); // Super Admin only
        
        return view('database_imports.index');
    }

    /**
     * Download the specific blank Excel template.
     */
    public function downloadTemplate($type)
    {
        Gate::authorize('manage-users');

        if (!in_array($type, ['breakdowns', 'units', 'users', 'vendors'])) {
            abort(404);
        }

        return Excel::download(new \App\Exports\DatabaseTemplateExport($type), "Template_Import_{$type}.xlsx");
    }

    /**
     * Handle the incoming Excel payload and map to the specific parser.
     */
    public function store(Request $request)
    {
        Gate::authorize('manage-users');

        $request->validate([
            'target_table' => 'required|string|in:breakdowns,units,users,vendors',
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            $file = $request->file('excel_file');

            switch ($request->target_table) {
                case 'breakdowns':
                    $import = new BreakdownLogsImport;
                    Excel::import($import, $file);
                    
                    if ($import->importedCount === 0 && $import->skippedCount > 0) {
                        $reasonDump = implode(", ", array_unique(array_slice($import->skippedReasons, 0, 3)));
                        return back()->with('error', "Import dibatalkan otomatis. {$import->skippedCount} baris terdeteksi cacat dan dilewati. Alasan: " . $reasonDump);
                    }

                    $successMessage = "Berhasil masuk: {$import->importedCount} laporan baru.";
                    if ($import->duplicateCount > 0) $successMessage .= " Dilewati: {$import->duplicateCount} data duplikat aman diabaikan.";
                    if ($import->skippedCount > 0) $successMessage .= " Dilewati: {$import->skippedCount} baris invalid.";
                    
                    break;
                case 'units':
                    Excel::import(new MasterUnitsImport, $file);
                    $successMessage = 'Data Master Unit berhasil di-import massal. Unit tanpa Vendor valid telah dilewati otomatis.';
                    break;
                case 'users':
                    Excel::import(new UsersImport, $file);
                    $successMessage = 'Data Akses User berhasil di-import massal. Akun Admin/Vendor tanpa Vendor valid telah dilewati otomatis.';
                    break;
                case 'vendors':
                    Excel::import(new VendorsImport, $file);
                    $successMessage = 'Data Profil Vendor berhasil di-import massal.';
                    break;
                default:
                    throw new \Exception('Tabel tujuan tidak dikenali oleh sistem.');
            }

            return back()->with('success', $successMessage);

        } catch (\Exception $e) {
            return back()->with('error', 'Proses Import Gagal: ' . $e->getMessage());
        }
    }
}
