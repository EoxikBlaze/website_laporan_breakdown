@extends('layouts.admin')

@section('title', 'Edit Unit')
@section('page-title', 'Edit Data Unit')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-6 py-5">
            <div class="flex items-center gap-3 text-white">
                <div class="p-2 bg-white/10 rounded-lg">
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <h3 class="font-bold">Edit Unit</h3>
                    <p class="text-xs text-blue-100 opacity-80">Perbarui informasi unit {{ $masterUnit->nomor_lambung }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('master_units.update', $masterUnit->id) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-2">
                <label for="nomor_lambung" class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Nomor Lambung <span class="text-rose-500">*</span></label>
                <input type="text" name="nomor_lambung" id="nomor_lambung" 
                       class="w-full px-4 py-2.5 rounded-xl border border-neutral-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none @error('nomor_lambung') border-rose-300 bg-rose-50 @enderror" 
                       value="{{ old('nomor_lambung', $masterUnit->nomor_lambung) }}" required>
                @error('nomor_lambung') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="jenis_unit" class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Jenis Unit <span class="text-rose-500">*</span></label>
                <input type="text" name="jenis_unit" id="jenis_unit" 
                       class="w-full px-4 py-2.5 rounded-xl border border-neutral-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none @error('jenis_unit') border-rose-300 bg-rose-50 @enderror" 
                       value="{{ old('jenis_unit', $masterUnit->jenis_unit) }}" required>
                @error('jenis_unit') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            @if(auth()->user()->isSuperAdmin())
            <div class="space-y-2 mt-6">
                <div data-react-component="IosSelectPicker" 
                     data-props="{{ json_encode([
                         'name' => 'vendor_id', 
                         'id' => 'vendor_id',
                         'placeholder' => '-- Tanpa Vendor --',
                         'label' => 'Vendor Mitra (Opsional)',
                         'initialValue' => old('vendor_id', $masterUnit->vendor_id),
                         'options' => $vendors->map(function($v) { 
                             return ['value' => $v->id, 'label' => $v->nama_vendor]; 
                         })->values()->toArray()
                     ]) }}">
                </div>
                @error('vendor_id') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
            </div>
            @endif


            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-600/20 transition-all active:scale-[0.98]">
                    Perbarui Unit
                </button>
                <a href="{{ route('master_units.index') }}" class="px-6 py-3 text-sm font-bold text-neutral-500 hover:text-neutral-700 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
