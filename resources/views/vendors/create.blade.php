@extends('layouts.admin')

@section('title', 'Tambah Vendor')
@section('page-title', 'Tambah Vendor Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-6 py-5">
            <div class="flex items-center gap-3 text-white">
                <div class="p-2 bg-white/10 rounded-lg">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div>
                    <h3 class="font-bold">Tambah Vendor Baru</h3>
                    <p class="text-xs text-blue-100 opacity-80">Daftarkan mitra vendor atau bengkel baru</p>
                </div>
            </div>
        </div>

        <form action="{{ route('vendors.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label for="nama_vendor" class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Nama Vendor <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_vendor" id="nama_vendor" 
                       class="w-full px-4 py-2.5 rounded-xl border border-neutral-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none @error('nama_vendor') border-rose-300 bg-rose-50 @enderror" 
                       value="{{ old('nama_vendor') }}" placeholder="Contoh: United Tractors" required>
                @error('nama_vendor') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="kontak" class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Kontak / No. HP</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-neutral-400">
                        <i class="fas fa-phone-alt text-xs"></i>
                    </div>
                    <input type="text" name="kontak" id="kontak" 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-neutral-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none @error('kontak') border-rose-300 bg-rose-50 @enderror" 
                           value="{{ old('kontak') }}" placeholder="Contoh: 08123456789">
                </div>
                @error('kontak') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="keterangan" class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="4" 
                          class="w-full px-4 py-3 rounded-xl border border-neutral-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none resize-none"
                          placeholder="Tambahkan catatan singkat (opsional)">{{ old('keterangan') }}</textarea>
                @error('keterangan') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-600/20 transition-all active:scale-[0.98]">
                    Simpan Vendor
                </button>
                <a href="{{ route('vendors.index') }}" class="px-6 py-3 text-sm font-bold text-neutral-500 hover:text-neutral-700 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
