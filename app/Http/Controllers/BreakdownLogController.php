<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBreakdownLogRequest;
use App\Http\Requests\UpdateBreakdownLogRequest;
use App\Models\BreakdownLog;
use App\Models\MasterUnit;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BreakdownLogExport;

use Illuminate\Support\Facades\Gate;

class BreakdownLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $logs = BreakdownLog::with(['unit', 'spareUnit', 'reporter', 'vendor'])
            ->latest()
            ->get();
            
        return view('breakdown_logs.index', compact('logs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $units = MasterUnit::all();
        $spareUnits = MasterUnit::where('status_operasional', 'Ready')->get();
        $vendors = Vendor::all();
        
        return view('breakdown_logs.create', compact('units', 'spareUnits', 'vendors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBreakdownLogRequest $request)
    {
        $validated = $request->validated();
        
        // Ensure user_id is set to the currently logged in user
        $validated['user_id'] = Auth::id() ?? 1; // Fallback to 1 for testing if no user logged in

        $log = BreakdownLog::create($validated);

        return redirect()->route('breakdown_logs.index')
            ->with('success', 'Tiket breakdown berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BreakdownLog $breakdownLog)
    {
        $breakdownLog->load(['unit', 'spareUnit', 'reporter', 'vendor']);
        return view('breakdown_logs.show', compact('breakdownLog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BreakdownLog $breakdownLog)
    {
        $units = MasterUnit::all();
        $spareUnits = MasterUnit::where('status_operasional', 'Ready')
            ->orWhere('id', $breakdownLog->spare_unit_id) // Allow keeping current spare unit
            ->get();
        $vendors = Vendor::all();
        $users = \App\Models\User::all();
            
        return view('breakdown_logs.edit', compact('breakdownLog', 'units', 'spareUnits', 'vendors', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBreakdownLogRequest $request, BreakdownLog $breakdownLog)
    {
        $validated = $request->validated();

        $breakdownLog->update($validated);

        return redirect()->route('breakdown_logs.index')
            ->with('success', 'Tiket breakdown berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BreakdownLog $breakdownLog)
    {
        $breakdownLog->delete();

        return redirect()->route('breakdown_logs.index')
            ->with('success', 'Tiket breakdown berhasil dihapus.');
    }

    /**
     * Export the breakdown logs to Excel.
     */
    public function exportExcel()
    {
        Gate::authorize('can-download');
        return Excel::download(new BreakdownLogExport, 'Laporan_Breakdown_Unit_' . date('Y-m-d') . '.xlsx');
    }
}
