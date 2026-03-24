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
                    Excel::import(new BreakdownLogsImport, $file);
                    $successMessage = 'Data Laporan Breakdown berhasil di-import massal. Baris tanpa Vendor/Unit valid telah dilewati secara otomatis untuk mencegah error.';
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
