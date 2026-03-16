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
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm border-0">
            <div class="card-header" style="background: linear-gradient(135deg,#1a1a2e,#0f3460); color:#fff;">
                <h3 class="card-title mt-1"><i class="fas fa-file-medical mr-2"></i> Form Input Laporan</h3>
            </div>
            <form action="{{ route('breakdown_logs.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 border-right">
                            <h5 class="text-muted mb-4"><i class="fas fa-truck-pickup mr-2"></i> Informasi Unit</h5>
                            
                            <div class="form-group">
                                <label for="unit_id">Unit Rusak <span class="text-danger">*</span></label>
                                <select name="unit_id" id="unit_id" class="form-control select2" required>
                                    <option value="">Pilih Unit Rusak...</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->nomor_lambung }} - {{ $unit->jenis_unit }} ({{ $unit->status_operasional }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit_id') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="spare_unit_id">Unit Pengganti (Spare Unit)</label>
                                <select name="spare_unit_id" id="spare_unit_id" class="form-control select2">
                                    <option value="">Tidak Menggunakan Spare</option>
                                    @foreach($spareUnits as $spare)
                                        <option value="{{ $spare->id }}" {{ old('spare_unit_id') == $spare->id ? 'selected' : '' }}>
                                            {{ $spare->nomor_lambung }} - {{ $spare->jenis_unit }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hanya unit dengan status <strong>Ready</strong> yang ditampilkan.</small>
                                @error('spare_unit_id') <br><span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                        </div>

                        <div class="col-md-6 pl-md-4">
                            <h5 class="text-muted mb-4"><i class="fas fa-clock mr-2"></i> Timeline & Status</h5>
                            
                            <!-- Timeline Fields ... -->
                            <div class="form-group">
                                <label for="waktu_awal_bd">Waktu Awal Breakdown <span class="text-danger">*</span></label>
                                <div data-react-component="IosDateTimePicker" 
                                     data-props="{{ json_encode(['name' => 'waktu_awal_bd', 'initialValue' => old('waktu_awal_bd', date('Y-m-d\TH:i'))]) }}">
                                </div>
                                @error('waktu_awal_bd') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group" id="spare-time-container" style="display: none;">
                                <label for="waktu_spare_datang">Waktu Spare Datang</label>
                                <div data-react-component="IosDateTimePicker" 
                                     data-props="{{ json_encode(['name' => 'waktu_spare_datang', 'initialValue' => old('waktu_spare_datang')]) }}">
                                </div>
                                @error('waktu_spare_datang') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="status">Status Laporan</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="Open" {{ old('status') == 'Open' ? 'selected' : '' }}>Open</option>
                                    <option value="Closed" {{ old('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mt-3">
                                <label for="keterangan">Keterangan / Detail Kerusakan</label>
                                <textarea name="keterangan" id="keterangan" rows="3" 
                                          class="form-control @error('keterangan') is-invalid @enderror" 
                                          placeholder="Ceritakan detail kerusakan unit...">{{ old('keterangan') }}</textarea>
                                @error('keterangan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white text-right">
                    <a href="{{ route('breakdown_logs.index') }}" class="btn btn-secondary px-4 mr-2">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">Simpan Laporan</button>
                </div>
            </form>
        </div>
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
