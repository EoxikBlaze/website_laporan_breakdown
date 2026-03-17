<?php

namespace App\Http\Controllers;

use App\Models\MasterUnit;
use App\Models\Vendor;
use Illuminate\Http\Request;

class MasterUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        \Illuminate\Support\Facades\Gate::authorize('admin');
        
        $units = MasterUnit::with('vendor')->get();
        return view('master_units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        \Illuminate\Support\Facades\Gate::authorize('admin');
        $vendors = Vendor::all();
        return view('master_units.create', compact('vendors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Gate::authorize('admin');
        
        $validated = $request->validate([
            'nomor_lambung' => ['required', 'string', 'unique:master_units,nomor_lambung'],
            'jenis_unit' => ['required', 'string'],
            'status_operasional' => ['required', 'in:Ready,In Use,Breakdown'],
            'vendor_id' => ['nullable', 'exists:vendors,id'],
        ]);

        if (!auth()->user()->isSuperAdmin()) {
            $validated['vendor_id'] = auth()->user()->vendor_id;
        }

        MasterUnit::create($validated);

        return redirect()->route('master_units.index')
            ->with('success', 'Unit berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterUnit $masterUnit)
    {
        \Illuminate\Support\Facades\Gate::authorize('admin');
        $masterUnit->load('vendor');
        return view('master_units.show', compact('masterUnit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterUnit $masterUnit)
    {
        \Illuminate\Support\Facades\Gate::authorize('admin');
        $vendors = Vendor::all();
        return view('master_units.edit', compact('masterUnit', 'vendors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterUnit $masterUnit)
    {
        \Illuminate\Support\Facades\Gate::authorize('admin');
        
        $validated = $request->validate([
            'nomor_lambung' => ['required', 'string', 'unique:master_units,nomor_lambung,' . $masterUnit->id],
            'jenis_unit' => ['required', 'string'],
            'status_operasional' => ['required', 'in:Ready,In Use,Breakdown'],
            'vendor_id' => ['nullable', 'exists:vendors,id'],
        ]);

        if (!auth()->user()->isSuperAdmin()) {
            $validated['vendor_id'] = auth()->user()->vendor_id;
        }

        $masterUnit->update($validated);

        return redirect()->route('master_units.index')
            ->with('success', 'Unit berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterUnit $masterUnit)
    {
        \Illuminate\Support\Facades\Gate::authorize('admin');
        
        // Prevent deletion if unit has breakdown history
        if ($masterUnit->breakdowns()->exists() || $masterUnit->replacements()->exists()) {
            return back()->with('error', 'Unit tidak dapat dihapus karena memiliki riwayat breakdown.');
        }

        $masterUnit->delete();

        return redirect()->route('master_units.index')
            ->with('success', 'Unit berhasil dihapus.');
    }
}
