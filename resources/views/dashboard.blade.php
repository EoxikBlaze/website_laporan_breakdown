@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6 pt-2">

    {{-- ── Stat Cards ──────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Loss Time Harian --}}
        <a href="{{ route('breakdown_logs.index') }}"
           class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 p-5 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 flex flex-col justify-between">
            <div class="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Loss Time Harian</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $stats['daily_loss_time'] }}</p>
                </div>
                <div class="h-11 w-11 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-clock text-slate-300 text-lg"></i>
                </div>
            </div>
            <div class="flex items-center gap-1 mt-4 text-slate-400 text-xs font-medium group-hover:text-white transition-colors">
                <span>Monitoring Total</span>
                <i class="fas fa-arrow-right text-[10px]"></i>
            </div>
        </a>

        {{-- Breakdown Aktif --}}
        <a href="{{ route('breakdown_logs.index') }}"
           class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500 to-rose-700 p-5 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 flex flex-col justify-between">
            <div class="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium text-rose-200 uppercase tracking-wider">Breakdown Aktif</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $stats['active_breakdowns'] }}</p>
                </div>
                <div class="h-11 w-11 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-tools text-white text-lg"></i>
                </div>
            </div>
            <div class="flex items-center gap-1 mt-4 text-rose-200 text-xs font-medium group-hover:text-white transition-colors">
                <span>Lihat Detail</span>
                <i class="fas fa-arrow-right text-[10px]"></i>
            </div>
        </a>

        {{-- Total Unit --}}
        <a href="{{ route('master_units.index') }}"
           class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 p-5 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 flex flex-col justify-between">
            <div class="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium text-emerald-200 uppercase tracking-wider">Total Unit</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $stats['total_units'] }}</p>
                </div>
                <div class="h-11 w-11 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-truck text-white text-lg"></i>
                </div>
            </div>
            <div class="flex items-center gap-1 mt-4 text-emerald-200 text-xs font-medium group-hover:text-white transition-colors">
                <span>Master Unit</span>
                <i class="fas fa-arrow-right text-[10px]"></i>
            </div>
        </a>

        {{-- Data Vendor --}}
        <a href="{{ route('vendors.index') }}"
           class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 p-5 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 flex flex-col justify-between">
            <div class="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium text-amber-100 uppercase tracking-wider">Data Vendor</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $stats['total_vendors'] }}</p>
                </div>
                <div class="h-11 w-11 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-building text-white text-lg"></i>
                </div>
            </div>
            <div class="flex items-center gap-1 mt-4 text-amber-100 text-xs font-medium group-hover:text-white transition-colors">
                <span>Lihat Vendor</span>
                <i class="fas fa-arrow-right text-[10px]"></i>
            </div>
        </a>
    </div>

    {{-- ── Laporan Terbaru ──────────────────────────────────────────────── --}}
    <div class="rounded-2xl bg-white border border-neutral-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-lg bg-slate-800 flex items-center justify-center">
                    <i class="fas fa-history text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-neutral-800">Laporan Breakdown Terbaru</h2>
                    <p class="text-xs text-neutral-400">Update real-time dari sistem</p>
                </div>
            </div>
            <a href="{{ route('breakdown_logs.index') }}"
               class="text-xs text-primary font-medium hover:underline flex items-center gap-1">
                Lihat Semua <i class="fas fa-external-link-alt text-[10px]"></i>
            </a>
        </div>
        <div>
            @php
                $latestProps = [
                    "reports"   => $latest_reports,
                    "showRoute" => route("breakdown_logs.show", ":id"),
                ];
            @endphp
            <div
                data-react-component="LatestReportsTable"
                data-props='@json($latestProps)'
            ></div>
        </div>
    </div>

</div>
@endsection
