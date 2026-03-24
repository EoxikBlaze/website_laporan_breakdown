@extends('layouts.admin')

@section('title', 'Import Database')
@section('page-title', 'Universal Database Restore')

@section('content')
<div class="row">
    <div class="col-12 max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-emerald-600 to-emerald-800 flex items-center justify-between border-b border-emerald-700">
                <div>
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <i class="fas fa-database text-emerald-200"></i>
                        Restore Data via Excel
                    </h3>
                    <p class="text-xs text-emerald-100 mt-1">Fasilitas eksklusif Super Admin untuk mengembalikan data massal ke sistem.</p>
                </div>
            </div>
            
            <form action="{{ route('database_imports.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 md:p-8 bg-neutral-50/50">
                    
                    <!-- Explicit Download Banners / Bypass JS entirely for 100% reliability -->
                    <div class="bg-blue-50/40 p-5 border border-blue-100 rounded-xl mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h4 class="font-bold text-blue-800 text-sm flex items-center gap-2">
                                <i class="fas fa-cloud-download-alt text-blue-600"></i>
                                Download Template Kosong
                            </h4>
                            <p class="text-xs text-blue-600/80 mt-1 leading-relaxed">Wajib menggunakan susunan kolom dari *template* resmi untuk menghindari error format tanggal atau penolakan data massal.</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 shrink-0">
                            <a href="{{ url('database_imports/template/breakdowns') }}" class="px-3 py-2 bg-white border border-blue-200 text-blue-800 rounded-lg shadow-sm text-xs font-bold hover:bg-blue-50 transition-all flex items-center gap-1.5"><i class="fas fa-file-excel text-emerald-600"></i> Laporan Breakdown</a>
                            <a href="{{ url('database_imports/template/units') }}" class="px-3 py-2 bg-white border border-blue-200 text-blue-800 rounded-lg shadow-sm text-xs font-bold hover:bg-blue-50 transition-all flex items-center gap-1.5"><i class="fas fa-file-excel text-emerald-600"></i> Master Unit</a>
                            <a href="{{ url('database_imports/template/users') }}" class="px-3 py-2 bg-white border border-blue-200 text-blue-800 rounded-lg shadow-sm text-xs font-bold hover:bg-blue-50 transition-all flex items-center gap-1.5"><i class="fas fa-file-excel text-emerald-600"></i> Data User</a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Target Table Selection -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Pilih Tabel Tujuan</label>
                            <select name="target_table" id="target_table" required class="w-full h-[52px] px-4 rounded-xl border border-neutral-200 bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm outline-none font-medium text-neutral-700 cursor-pointer">
                                <option value="" disabled selected>-- Pilih Tabel Output --</option>
                                <option value="breakdowns">Laporan Breakdown</option>
                                <option value="units">Data Master Unit</option>
                                <option value="users">Data Akses User</option>
                                <option value="vendors">Data Profil Vendor</option>
                            </select>
                        </div>

                        <!-- File Upload Custom Layout -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Pilih File Spreadsheet (.xlsx)</label>
                            <label class="flex items-center w-full h-[52px] px-4 rounded-xl border border-neutral-200 bg-white hover:bg-neutral-50 transition-all cursor-pointer focus-within:ring-2 focus-within:ring-emerald-500/20 focus-within:border-emerald-500">
                                <div class="px-4 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-lg mr-4 shadow-sm border border-emerald-100/50">
                                    Pilih File
                                </div>
                                <span id="file-name" class="text-sm font-medium text-neutral-500 truncate mt-0.5">Tidak ada file yang dipilih</span>
                                <input type="file" name="excel_file" accept=".xlsx, .xls, .csv" required class="hidden" onchange="document.getElementById('file-name').innerText = this.files && this.files.length > 0 ? this.files[0].name : 'Tidak ada file yang dipilih'; document.getElementById('file-name').className = this.files && this.files.length > 0 ? 'text-sm font-semibold text-neutral-800 truncate mt-0.5' : 'text-sm font-medium text-neutral-500 truncate mt-0.5';">
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-white border-t border-neutral-100 flex items-center justify-end gap-3">
                    <button type="submit" onclick="document.getElementById('global-loader').classList.remove('opacity-0', 'pointer-events-none'); document.getElementById('global-loader').classList.add('opacity-100'); setTimeout(() => { this.disabled = true; }, 50);" class="px-6 py-3 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700 shadow-md shadow-emerald-500/20 transition-all active:scale-[0.98] flex items-center gap-2">
                        <i class="fas fa-cloud-upload-alt"></i>
                        Mulai Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
