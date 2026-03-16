@extends('layouts.admin')

@section('title', 'Edit Unit')
@section('page-title', 'Edit Data Unit')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header" style="background: linear-gradient(135deg,#1a1a2e,#0f3460); color:#fff;">
                <h3 class="card-title mt-1"><i class="fas fa-edit mr-2"></i> Form Edit Unit: {{ $masterUnit->nomor_lambung }}</h3>
            </div>
            <form action="{{ route('master_units.update', $masterUnit->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="nomor_lambung">Nomor Lambung</label>
                        <input type="text" name="nomor_lambung" id="nomor_lambung" 
                               class="form-control @error('nomor_lambung') is-invalid @enderror" 
                               value="{{ old('nomor_lambung', $masterUnit->nomor_lambung) }}" required>
                        @error('nomor_lambung')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jenis_unit">Jenis Unit</label>
                        <input type="text" name="jenis_unit" id="jenis_unit" 
                               class="form-control @error('jenis_unit') is-invalid @enderror" 
                               value="{{ old('jenis_unit', $masterUnit->jenis_unit) }}" required>
                        @error('jenis_unit')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status_operasional">Status Operasional</label>
                        <select name="status_operasional" id="status_operasional" class="form-control" required>
                            <option value="Ready" {{ old('status_operasional', $masterUnit->status_operasional) == 'Ready' ? 'selected' : '' }}>Ready</option>
                            <option value="In Use" {{ old('status_operasional', $masterUnit->status_operasional) == 'In Use' ? 'selected' : '' }}>In Use</option>
                            <option value="Breakdown" {{ old('status_operasional', $masterUnit->status_operasional) == 'Breakdown' ? 'selected' : '' }}>Breakdown</option>
                        </select>
                        @error('status_operasional')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="vendor_id">Vendor / Bengkel (Opsional)</label>
                        <select name="vendor_id" id="vendor_id" class="form-control select2">
                            <option value="">-- Tanpa Vendor --</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id', $masterUnit->vendor_id) == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->nama_vendor }}
                                </option>
                            @endforeach
                        </select>
                        @error('vendor_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    @if($masterUnit->status_operasional !== 'Ready')
                        <div class="callout callout-info">
                            <h5><i class="fas fa-info mr-2"></i> Perhatian</h5>
                            <p>Mengubah status secara manual dapat mempengaruhi alur otomatisasi breakdown log. Gunakan dengan bijak.</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white text-right">
                    <a href="{{ route('master_units.index') }}" class="btn btn-secondary px-4 mr-2">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">Perbarui Unit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
