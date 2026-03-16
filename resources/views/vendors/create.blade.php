@extends('layouts.admin')

@section('title', 'Tambah Vendor')
@section('page-title', 'Tambah Vendor Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header" style="background: linear-gradient(135deg,#1a1a2e,#0f3460); color:#fff;">
                <h3 class="card-title mt-1"><i class="fas fa-plus-circle mr-2"></i> Form Vendor Baru</h3>
            </div>
            <form action="{{ route('vendors.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama_vendor">Nama Vendor <span class="text-danger">*</span></label>
                        <input type="text" name="nama_vendor" id="nama_vendor" 
                               class="form-control @error('nama_vendor') is-invalid @enderror" 
                               value="{{ old('nama_vendor') }}" placeholder="Contoh: United Tractors" required>
                        @error('nama_vendor')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kontak">Kontak / No. HP</label>
                        <input type="text" name="kontak" id="kontak" 
                               class="form-control @error('kontak') is-invalid @enderror" 
                               value="{{ old('kontak') }}" placeholder="Contoh: 08123456789">
                        @error('kontak')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3" 
                                  class="form-control @error('keterangan') is-invalid @enderror" 
                                  placeholder="Tambahkan catatan singkat (opsional)">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer bg-white text-right">
                    <a href="{{ route('vendors.index') }}" class="btn btn-secondary px-4 mr-2">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">Simpan Vendor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
