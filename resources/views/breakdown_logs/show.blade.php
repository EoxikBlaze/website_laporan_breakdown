@extends('layouts.admin')

@section('title', 'Detail Breakdown')
@section('page-title', 'Detail Laporan Breakdown')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header Card -->
    <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-8 py-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4 text-white">
                    <div class="p-3 bg-white/10 rounded-xl backdrop-blur-sm">
                        <i class="fas fa-file-alt text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold tracking-tight">Detail Laporan Breakdown</h3>
                        <p class="text-sm text-blue-100 opacity-80 mt-1">ID Unit: {{ $breakdownLog->unit->nomor_lambung }} • {{ $breakdownLog->unit->jenis_unit }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('breakdown_logs.index') }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl text-sm font-bold backdrop-blur-sm transition-all border border-white/10">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    @if(auth()->user()->role === 'super_admin' || auth()->user()->id === $breakdownLog->reported_by)
                        <a href="{{ route('breakdown_logs.edit', $breakdownLog->id) }}" class="px-5 py-2.5 bg-blue-500 hover:bg-blue-400 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-500/20 transition-all border border-blue-400/20">
                            <i class="fas fa-edit mr-2"></i> Edit Laporan
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-neutral-100 bg-neutral-50/50">
            <div class="p-6">
                <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest mb-1">Status Laporan</p>
                @if($breakdownLog->status == 'Open')
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-50 text-rose-600 border border-rose-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                        <span class="text-xs font-black uppercase">Open Pulse</span>
                    </div>
                @else
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span class="text-xs font-black uppercase">Resolved</span>
                    </div>
                @endif
            </div>
            <div class="p-6">
                <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest mb-1">Total Loss Time</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-xl font-black text-rose-600">{{ $breakdownLog->loss_time ?? '0 Menit' }}</span>
                </div>
            </div>
            <div class="p-6">
                <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest mb-1">Daily Impact</p>
                <div class="flex items-baseline gap-1 text-blue-600">
                    <span class="text-xl font-black">{{ $breakdownLog->loss_time_percentage ?? '0' }}</span>
                    <span class="text-[10px] font-bold uppercase">%</span>
                </div>
            </div>
            <div class="p-6">
                <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest mb-1">Pelapor</p>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-[10px] font-bold text-blue-600 uppercase">
                        {{ substr($breakdownLog->reporter->name ?? 'S', 0, 1) }}
                    </div>
                    <span class="text-sm font-bold text-neutral-700">{{ $breakdownLog->reporter->name ?? 'System' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-8">
                <h5 class="flex items-center gap-2 text-sm font-bold text-neutral-700 uppercase tracking-wider mb-6 pb-2 border-b border-neutral-100">
                    <i class="fas fa-info-circle text-blue-600"></i>
                    Informasi Kejadian
                </h5>
                
                <div class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider mb-2">Unit Utama</p>
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-neutral-50 rounded-lg text-neutral-400">
                                    <i class="fas fa-truck-pickup"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-neutral-800">{{ $breakdownLog->unit->nomor_lambung }}</p>
                                    <p class="text-[11px] text-neutral-500 font-medium">{{ $breakdownLog->unit->jenis_unit }}</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider mb-2">Unit Pengganti / Spare</p>
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-neutral-50 rounded-lg text-neutral-400">
                                    <i class="fas fa-exchange-alt"></i>
                                </div>
                                <div>
                                    @if($breakdownLog->spareUnit)
                                        <p class="text-sm font-bold text-neutral-800">{{ $breakdownLog->spareUnit->nomor_lambung }}</p>
                                        <p class="text-[11px] text-neutral-500 font-medium">{{ $breakdownLog->spareUnit->jenis_unit }}</p>
                                    @else
                                        <p class="text-sm font-bold text-neutral-400">Tidak Menggunakan Spare</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider mb-2">Detail Kerusakan & Tindakan</p>
                        <div class="p-6 bg-neutral-50 rounded-2xl border border-neutral-100">
                            <p class="text-neutral-700 text-sm leading-relaxed italic">
                                {!! nl2br(e($breakdownLog->keterangan ?? 'Tidak ada keterangan tambahan.')) !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Details -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-100 bg-neutral-50">
                    <h5 class="flex items-center gap-2 text-xs font-bold text-neutral-700 uppercase tracking-wider">
                        <i class="fas fa-clock text-blue-600"></i>
                        Timeline Laporan
                    </h5>
                </div>
                <div class="p-6">
                    <div class="relative space-y-8 before:absolute before:inset-0 before:left-[11px] before:w-[2px] before:bg-neutral-100">
                        <!-- Mulai BD -->
                        <div class="relative flex gap-4">
                            <div class="relative z-10 w-6 h-6 rounded-full bg-rose-50 border-2 border-rose-500 border-white flex items-center justify-center">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest">Mulai Breakdown</p>
                                <p class="text-sm font-bold text-neutral-700">{{ \Carbon\Carbon::parse($breakdownLog->waktu_awal_bd)->format('d F Y') }}</p>
                                <p class="text-xs text-rose-500 font-medium mt-0.5">Pukul {{ \Carbon\Carbon::parse($breakdownLog->waktu_awal_bd)->format('H:i') }} WIB</p>
                            </div>
                        </div>

                        <!-- Spare Datang -->
                        @if($breakdownLog->spare_unit_id)
                            <div class="relative flex gap-4">
                                <div class="relative z-10 w-6 h-6 rounded-full bg-blue-50 border-2 border-blue-500 border-white flex items-center justify-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest">Spare Datang</p>
                                    @if($breakdownLog->waktu_spare_datang)
                                        <p class="text-sm font-bold text-neutral-700">{{ \Carbon\Carbon::parse($breakdownLog->waktu_spare_datang)->format('d F Y') }}</p>
                                        <p class="text-xs text-blue-500 font-medium mt-0.5">Pukul {{ \Carbon\Carbon::parse($breakdownLog->waktu_spare_datang)->format('H:i') }} WIB</p>
                                    @else
                                        <p class="text-xs font-bold text-neutral-300 italic">Belum Tiba</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Selesai BD -->
                        <div class="relative flex gap-4">
                            <div class="relative z-10 w-6 h-6 rounded-full bg-emerald-50 border-2 border-emerald-500 border-white flex items-center justify-center">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest">Selesai / RFU</p>
                                @if($breakdownLog->waktu_akhir_bd)
                                    <p class="text-sm font-bold text-neutral-700">{{ \Carbon\Carbon::parse($breakdownLog->waktu_akhir_bd)->format('d F Y') }}</p>
                                    <p class="text-xs text-emerald-500 font-medium mt-0.5">Pukul {{ \Carbon\Carbon::parse($breakdownLog->waktu_akhir_bd)->format('H:i') }} WIB</p>
                                @else
                                    <p class="text-xs font-bold text-neutral-300 italic">Masih Dalam Pengerjaan</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Stats -->
            <div class="bg-blue-600 rounded-2xl p-6 text-white shadow-xl shadow-blue-600/20">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60 mb-4">Efisiensi Breakdown</p>
                <div class="space-y-4">
                    <div class="flex justify-between items-end border-b border-white/10 pb-3">
                        <span class="text-xs font-bold opacity-80">{{ $breakdownLog->spare_unit_id ? 'Efisiensi Spare' : 'Tanpa Spare' }}</span>
                        <span class="text-xl font-black">{{ $breakdownLog->lama_bd_tanpa_spare ?? $breakdownLog->loss_time ?? '0 Menit' }}</span>
                    </div>
                </div>
                <div class="mt-6 p-3 bg-white/10 rounded-xl">
                    <p class="text-[10px] leading-relaxed opacity-80 italic italic">
                        "Laporan ini dibuat otomatis untuk membantu pemantauan ketersediaan unit di lapangan."
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
