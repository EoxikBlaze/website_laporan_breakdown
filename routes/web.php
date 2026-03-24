<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return redirect()->route('dashboard');
});

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BreakdownLogController;
use App\Http\Controllers\MasterUnitController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DatabaseImportController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Breakdown Logs
    Route::get('breakdown_logs/export', [BreakdownLogController::class, 'exportExcel'])->name('breakdown_logs.export');
    Route::resource('breakdown_logs', BreakdownLogController::class);
    
    // Master Units
    Route::resource('master_units', MasterUnitController::class);
    
    // Vendors
    Route::resource('vendors', VendorController::class);
    
    // User Management (super_admin only)
    Route::resource('users', UserController::class);
    
    // Universal Database Import (super_admin only)
    Route::resource('database_imports', DatabaseImportController::class)->only(['index', 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
