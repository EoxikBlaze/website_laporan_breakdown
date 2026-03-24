@extends('layouts.admin')

@section('title', 'Edit Laporan')
@section('page-title', 'Edit Laporan Breakdown')

@push('styles')
@endpush

@section('content')
<div class="max-w-6xl mx-auto">
    <form action="{{ route('breakdown_logs.update', $breakdownLog->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Unit Information -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-6 py-4">
                        <div class="flex items-center gap-3 text-white">
                            <div class="p-2 bg-white/10 rounded-lg">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div>
                                <h3 class="font-bold">Edit Laporan</h3>
                                <p class="text-xs text-blue-100 opacity-80">Perbarui informasi breakdown unit {{ $breakdownLog->unit->nomor_lambung }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-6">
                        <div class="flex items-center gap-2 pb-2 border-b border-neutral-100">
                            <i class="fas fa-truck-pickup text-blue-600"></i>
                            <h5 class="text-sm font-bold text-neutral-700">Informasi Unit</h5>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Unit Rusak <span class="text-rose-500">*</span></label>
                                <div data-react-component="IosSelectPicker" 
                                     data-props="{{ json_encode([
                                         'name' => 'unit_id', 
                                         'id' => 'unit_id',
                                         'placeholder' => 'Pilih Unit Rusak...',
                                         'label' => 'Unit Rusak',
                                         'initialValue' => old('unit_id', $breakdownLog->unit_id),
                                         'required' => true,
                                         'options' => $units->map(function($u) { 
                                             return ['value' => $u->id, 'label' => $u->nomor_lambung . ' - ' . $u->jenis_unit]; 
                                         })->values()->toArray()
                                     ]) }}">
                                </div>
                                @error('unit_id') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Unit Pengganti (Opsional)</label>
                                <div data-react-component="IosSelectPicker" 
                                     data-props="{{ json_encode([
                                         'name' => 'spare_unit_id', 
                                         'id' => 'spare_unit_id',
                                         'placeholder' => 'Tidak Menggunakan Unit Pengganti',
                                         'label' => 'Unit Pengganti',
                                         'initialValue' => old('spare_unit_id', $breakdownLog->spare_unit_id),
                                         'options' => $spareUnits->map(function($s) { 
                                             return ['value' => $s->id, 'label' => $s->nomor_lambung . ' - ' . $s->jenis_unit]; 
                                         })->values()->toArray()
                                     ]) }}">
                                </div>
                                @error('spare_unit_id') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Keterangan / Detail Kerusakan</label>
                            <textarea name="keterangan" id="keterangan" rows="4" 
                                      class="w-full px-4 py-3 rounded-xl border border-neutral-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none resize-none"
                                      placeholder="Ceritakan detail kerusakan unit...">{{ old('keterangan', $breakdownLog->keterangan) }}</textarea>
                            @error('keterangan') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        @if(auth()->user()->isSuperAdmin())
                        <div class="space-y-2 border-t border-neutral-100 pt-4">
                            <label for="user_id" class="text-xs font-bold text-neutral-500 uppercase tracking-wider italic">Reporter (Superadmin Only)</label>
                            <div data-react-component="IosSelectPicker" 
                                 data-props="{{ json_encode([
                                     'name' => 'user_id', 
                                     'id' => 'user_id',
                                     'placeholder' => 'Pilih Reporter...',
                                     'label' => 'Ganti Reporter',
                                     'initialValue' => old('user_id', $breakdownLog->user_id),
                                     'options' => $users->map(function($usr) { 
                                         return ['value' => $usr->id, 'label' => $usr->name, 'description' => 'Role: ' . $usr->role]; 
                                     })->values()->toArray()
                                 ]) }}">
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Timeline & Status -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden sticky top-6">
                    <div class="bg-blue-50 px-6 py-4 border-b border-neutral-100">
                        <div class="flex items-center gap-2 text-blue-700">
                            <i class="fas fa-history"></i>
                            <h5 class="text-sm font-bold uppercase tracking-wider">Timeline & Status</h5>
                        </div>
                    </div>

                    <div class="p-6 space-y-5">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Mulai Breakdown <span class="text-rose-500">*</span></label>
                            <div data-react-component="IosDateTimePicker" 
                                 data-props="{{ json_encode(['name' => 'waktu_awal_bd', 'id' => 'waktu_awal_bd', 'initialValue' => \Carbon\Carbon::parse($breakdownLog->waktu_awal_bd)->format('Y-m-d\TH:i')]) }}">
                            </div>
                            @error('waktu_awal_bd') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div id="spare-time-container" class="space-y-2" style="display: none;">
                            <label class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Unit Pengganti Datang</label>
                            <div data-react-component="IosDateTimePicker" 
                                 data-props="{{ json_encode(['name' => 'waktu_spare_datang', 'id' => 'waktu_spare_datang', 'initialValue' => $breakdownLog->waktu_spare_datang ? \Carbon\Carbon::parse($breakdownLog->waktu_spare_datang)->format('Y-m-d\TH:i') : null]) }}">
                            </div>
                            @error('waktu_spare_datang') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Selesai Breakdown (Closed)</label>
                            <div data-react-component="IosDateTimePicker" 
                                 data-props="{{ json_encode(['name' => 'waktu_akhir_bd', 'id' => 'waktu_akhir_bd', 'initialValue' => $breakdownLog->waktu_akhir_bd ? \Carbon\Carbon::parse($breakdownLog->waktu_akhir_bd)->format('Y-m-d\TH:i') : null]) }}">
                            </div>
                            @error('waktu_akhir_bd') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

@if(auth()->user()->isAdmin())
                        <div class="space-y-2 border-t border-neutral-100 pt-4">
                            <label class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Status Laporan</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="status" value="Open" {{ old('status', $breakdownLog->status) == 'Open' ? 'checked' : '' }} class="peer hidden">
                                    <div class="flex items-center justify-center p-2 rounded-xl border border-neutral-200 text-xs font-bold text-neutral-400 group-hover:bg-neutral-50 peer-checked:border-rose-500 peer-checked:bg-rose-50 peer-checked:text-rose-600 transition-all">
                                        OPEN
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="status" value="Closed" {{ old('status', $breakdownLog->status) == 'Closed' ? 'checked' : '' }} class="peer hidden">
                                    <div class="flex items-center justify-center p-2 rounded-xl border border-neutral-200 text-xs font-bold text-neutral-400 group-hover:bg-neutral-50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-600 transition-all">
                                        CLOSED
                                    </div>
                                </label>
                            </div>
                        </div>
@endif

                        <div class="pt-4 flex flex-col gap-2">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-600/20 transition-all active:scale-[0.98]">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('breakdown_logs.index') }}" class="w-full text-center py-3 text-sm font-bold text-neutral-400 hover:text-neutral-600 transition-colors">
                                Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {

        // Automatic status closing when finish time is filled
        $(document).on('change', 'input[name="waktu_akhir_bd"]', function() {
            if ($(this).val()) {
                $('input[name="status"][value="Closed"]').prop('checked', true);
            } else {
                $('input[name="status"][value="Open"]').prop('checked', true);
            }
        });

        // Initial check for finish time to set status visually if needed
        if ($('input[name="waktu_akhir_bd"]').val()) {
            $('input[name="status"][value="Closed"]').prop('checked', true);
        }

        function toggleSpareTime() {
            if ($('#spare_unit_id').val()) {
                $('#spare-time-container').slideDown();
            } else {
                $('#spare-time-container').slideUp();
                // Clear spare time if no spare unit
                $('input[name="waktu_spare_datang"]').val('');
            }
        }

        $('#spare_unit_id').on('change', toggleSpareTime);
        toggleSpareTime(); // Run on load
    });
</script>
@endpush
