@extends('layouts.admin')

@section('title', 'Laporan Breakdown')
@section('page-title', 'Daftar Laporan Breakdown')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-800 flex items-center justify-between border-b border-blue-700">
                <div>
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <i class="fas fa-history text-blue-200"></i>
                        Riwayat Breakdown
                    </h3>
                    <p class="text-[10px] text-blue-100 mt-0.5">Daftar laporan breakdown unit aktif dan riwayat</p>
                </div>
                <div class="flex items-center gap-2">
                    @if(auth()->check() && auth()->user()->isSuperAdmin())
                        <button type="button" data-toggle="modal" data-target="#importExcelModal" class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-500/20 text-emerald-100 border border-emerald-400/30 text-xs font-bold rounded-lg hover:bg-emerald-500/40 transition-colors shadow-sm">
                            <i class="fas fa-file-import"></i>
                            Import
                        </button>
                    @endif
                    <a href="{{ route('breakdown_logs.export') }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-500/20 text-white border border-blue-400/30 text-xs font-bold rounded-lg hover:bg-blue-500/40 transition-colors shadow-sm">
                        <i class="fas fa-file-excel"></i>
                        Export Excel
                    </a>
                    <a href="{{ route('breakdown_logs.create') }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white text-blue-700 text-xs font-bold rounded-lg hover:bg-blue-50 transition-colors shadow-sm">
                        <i class="fas fa-plus"></i>
                        Input Laporan
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="icon fas fa-check"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                @endif

                @php
                    $props = [
                        "logs" => $logs,
                        "canAdmin" => Gate::check("admin"),
                        "routes" => [
                            "show" => route("breakdown_logs.show", ":id"),
                            "edit" => route("breakdown_logs.edit", ":id"),
                            "destroy" => route("breakdown_logs.destroy", ":id")
                        ],
                        "csrfToken" => csrf_token()
                    ];
                @endphp
                <div 
                    data-react-component="BreakdownLogTable" 
                    data-props='@json($props)'>
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->check() && auth()->user()->isSuperAdmin())
<!-- Import Excel Modal -->
<div class="modal fade" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-2xl border-0 overflow-hidden shadow-2xl">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-800 flex items-center justify-between border-b border-blue-700">
                <h5 class="text-sm font-bold text-white flex items-center gap-2" id="importExcelModalLabel">
                    <i class="fas fa-file-import text-blue-200"></i> Import Data Excel (Massal)
                </h5>
                <button type="button" class="text-white/70 hover:text-white pb-1" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('breakdown_logs.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 bg-neutral-50/50">
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-5 flex gap-3 text-blue-800 text-sm">
                        <i class="fas fa-info-circle mt-0.5 text-blue-500"></i>
                        <div>
                            <p class="font-bold mb-1 tracking-tight">Perhatian Format Kolom Excel</p>
                            <p class="opacity-80">Pastikan file excel Anda berasal (atau meniru murni) bentuk <b>Sheet "Semua Data"</b> saat Anda melakukan Export Excel. Baris ke-4 ke bawah otomatis akan terbaca dan dijahit ke Database secara instan.</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Pilih File Spreadsheet (.xlsx)</label>
                        <input type="file" name="excel_file" accept=".xlsx, .xls, .csv" required class="w-full h-12 px-4 rounded-xl border border-neutral-200 bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm outline-none font-medium text-neutral-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>
                </div>
                <div class="px-6 py-4 bg-white border-t border-neutral-100 flex items-center justify-end gap-3">
                    <button type="button" class="px-5 py-2.5 rounded-xl border border-neutral-200 text-neutral-600 text-xs font-bold hover:bg-neutral-50 transition-colors" data-dismiss="modal">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 shadow-md shadow-blue-500/20 transition-all active:scale-[0.98]">Mulai Import Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection
