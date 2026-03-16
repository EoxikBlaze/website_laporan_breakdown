<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterUnit;
use App\Models\Vendor;
use App\Models\BreakdownLog;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();
        
        // Calculate Loss Time Total Harian (Today's reports)
        $logsToday = BreakdownLog::where('waktu_awal_bd', '>=', $today)
            ->orWhere('waktu_akhir_bd', '>=', $today)
            ->get();

        $totalSeconds = 0;
        foreach ($logsToday as $log) {
            $awal = \Carbon\Carbon::parse($log->waktu_awal_bd);
            $akhir = $log->waktu_akhir_bd ? \Carbon\Carbon::parse($log->waktu_akhir_bd) : now();
            
            // Limit to today if started before today
            if ($awal->lt($today)) $awal = $today->copy();
            
            $totalSeconds += $awal->diffInSeconds($akhir);
        }

        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds / 60) % 60);
        $dailyLossTime = "{$hours}j {$minutes}m";

        $stats = [
            'total_units' => MasterUnit::count(),
            'total_vendors' => Vendor::count(),
            'active_breakdowns' => BreakdownLog::where('status', 'Open')->count(),
            'daily_loss_time' => $dailyLossTime,
        ];

        $latest_reports = BreakdownLog::with(['unit', 'vendor'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'latest_reports'));
    }
}
