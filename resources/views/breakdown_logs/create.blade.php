@extends('layouts.admin')

@section('title', 'Input Breakdown')
@section('page-title', 'Input Laporan Breakdown Baru')

@push('styles')
<!-- Select2 CDN -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css" rel="stylesheet" />
<style>
    .select2-container--bootstrap4 .select2-selection--single { height: calc(1.5em + .75rem + 2px) !important; }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
        {{-- Form Header --}}
        <div class="px-6 py-4 bg-gradient-to-r from-blue-700 to-blue-900 flex items-center justify-between border-b border-blue-800">
            <div>
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i class="fas fa-file-medical text-blue-200"></i>
                    Konfigurasi Laporan Breakdown
                </h3>
                <p class="text-[10px] text-blue-100 mt-0.5">Lengkapi detail kerusakan dan unit pengganti</p>
            </div>
        </div>

        <form action="{{ route('breakdown_logs.store') }}" method="POST">
            @csrf
            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    {{-- Section: Informasi Unit --}}
                    <div class="space-y-6">
                        <div class="flex items-center gap-2 pb-2 border-b border-neutral-100">
                            <div class="h-8 w-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                                <i class="fas fa-truck-pickup text-sm"></i>
                            </div>
                            <h5 class="text-sm font-bold text-neutral-800 uppercase tracking-tight">Informasi Unit</h5>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="unit_id" class="block text-xs font-bold text-neutral-700 uppercase tracking-wide">
                                Unit Rusak <span class="text-rose-500">*</span>
                            </label>
                            <select name="unit_id" id="unit_id" class="select2 w-full h-10 rounded-lg border-neutral-300 bg-white" required>
                                <option value="">Pilih Unit Rusak...</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->nomor_lambung }} - {{ $unit->jenis_unit }} ({{ $unit->status_operasional }})
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_id') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="spare_unit_id" class="block text-xs font-bold text-neutral-700 uppercase tracking-wide">
                                Unit Pengganti (Spare)
                            </label>
                            <select name="spare_unit_id" id="spare_unit_id" class="select2 w-full h-10 rounded-lg border-neutral-300 bg-white">
                                <option value="">Tidak Menggunakan Spare</option>
                                @foreach($spareUnits as $spare)
                                    <option value="{{ $spare->id }}" {{ old('spare_unit_id') == $spare->id ? 'selected' : '' }}>
                                        {{ $spare->nomor_lambung }} - {{ $spare->jenis_unit }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="flex items-start gap-1.5 mt-1.5 opacity-70">
                                <i class="fas fa-info-circle text-[10px] text-blue-500 mt-0.5"></i>
                                <span class="text-[10px] text-neutral-500 leading-tight">Hanya unit dengan status <strong>Ready</strong> yang ditampilkan.</span>
                            </div>
                            @error('spare_unit_id') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Section: Timeline & Status --}}
                    <div class="space-y-6">
                        <div class="flex items-center gap-2 pb-2 border-b border-neutral-100">
                            <div class="h-8 w-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                                <i class="fas fa-clock text-sm"></i>
                            </div>
                            <h5 class="text-sm font-bold text-neutral-800 uppercase tracking-tight">Timeline & Kontrol</h5>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="waktu_awal_bd" class="block text-xs font-bold text-neutral-700 uppercase tracking-wide">
                                Waktu Awal Breakdown <span class="text-rose-500">*</span>
                            </label>
                            <div data-react-component="IosDateTimePicker" 
                                 data-props="{{ json_encode(['name' => 'waktu_awal_bd', 'id' => 'waktu_awal_bd', 'initialValue' => old('waktu_awal_bd', date('Y-m-d\TH:i'))]) }}">
                            </div>
                            @error('waktu_awal_bd') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2" id="spare-time-container" style="display: none;">
                            <label for="waktu_spare_datang" class="block text-xs font-bold text-neutral-700 uppercase tracking-wide">
                                Waktu Spare Datang
                            </label>
                            <div data-react-component="IosDateTimePicker" 
                                 data-props="{{ json_encode(['name' => 'waktu_spare_datang', 'id' => 'waktu_spare_datang', 'initialValue' => old('waktu_spare_datang')]) }}">
                            </div>
                            @error('waktu_spare_datang') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="waktu_akhir_bd" class="block text-xs font-bold text-neutral-700 uppercase tracking-wide">
                                Waktu Selesai Breakdown (Closed)
                            </label>
                            <div data-react-component="IosDateTimePicker" 
                                 data-props="{{ json_encode(['name' => 'waktu_akhir_bd', 'id' => 'waktu_akhir_bd', 'initialValue' => old('waktu_akhir_bd')]) }}">
                            </div>
                            @error('waktu_akhir_bd') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-neutral-100">
                    <div class="space-y-2">
                        <label for="keterangan" class="block text-xs font-bold text-neutral-700 uppercase tracking-wide">
                            Keterangan / Detail Kerusakan
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="3" 
                                  class="w-full rounded-xl border-neutral-300 focus:ring-primary/20 p-3 text-sm placeholder:text-neutral-400" 
                                  placeholder="Ceritakan detail kerusakan unit secara lengkap...">{{ old('keterangan') }}</textarea>
                        @error('keterangan') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="px-8 py-5 bg-neutral-50 border-t border-neutral-200 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 font-semibold">
                <a href="{{ route('breakdown_logs.index') }}" 
                   class="w-full sm:w-auto text-center px-6 py-2.5 rounded-xl text-neutral-500 hover:bg-neutral-200/50 transition-colors text-sm">
                    Batal
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto px-8 py-2.5 rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-600/20 hover:bg-blue-700 active:scale-[0.98] transition-all text-sm">
                    Simpan Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<!-- Select2 JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

        // Conditional visibility for spare arrival time
        function toggleSpareTime() {
            if ($('#spare_unit_id').val()) {
                $('#spare-time-container').slideDown();
            } else {
                $('#spare-time-container').slideUp();
            }
        }

        $('#spare_unit_id').on('change', toggleSpareTime);
        toggleSpareTime(); // Run on load

    });
</script>
@endpush
