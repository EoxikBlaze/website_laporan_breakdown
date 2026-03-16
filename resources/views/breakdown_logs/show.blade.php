@extends('layouts.admin')

@section('title', 'Detail Breakdown')
@section('page-title', 'Detail Laporan Breakdown')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm border-0">
            <div class="card-header" style="background: linear-gradient(135deg,#1a1a2e,#0f3460); color:#fff;">
                <h3 class="card-title mt-1"><i class="fas fa-info-circle mr-2"></i> Detail Laporan: {{ $breakdownLog->unit->nomor_lambung }}</h3>
                <div class="card-tools">
                    <a href="{{ route('breakdown_logs.index') }}" class="btn btn-sm btn-light shadow-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="text-primary border-bottom pb-2 mb-3">Informasi Unit</h5>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="150">Unit Rusak</th>
                                <td>: <strong>{{ $breakdownLog->unit->nomor_lambung }}</strong> ({{ $breakdownLog->unit->jenis_unit }})</td>
                            </tr>
                            <tr>
                                <th>Spare Unit</th>
                                <td>: {!! $breakdownLog->spareUnit ? '<strong>'.$breakdownLog->spareUnit->nomor_lambung.'</strong>' : '<span class="text-muted">Tanpa Spare</span>' !!}</td>
                            </tr>
                            <tr>
                                <th>Vendor</th>
                                <td>: {{ $breakdownLog->vendor->nama_vendor ?? 'Internal' }}</td>
                            </tr>
                            <tr>
                                <th>Pelapor</th>
                                <td>: <span class="badge badge-light">{{ $breakdownLog->reporter->name ?? 'System' }}</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-primary border-bottom pb-2 mb-3">Timeline & Status</h5>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="150">Status</th>
                                <td>: 
                                    @if($breakdownLog->status == 'Open')
                                        <span class="badge badge-danger">Open</span>
                                    @else
                                        <span class="badge badge-success">Closed</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Mulai BD</th>
                                <td>: {{ \Carbon\Carbon::parse($breakdownLog->waktu_awal_bd)->format('d F Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Selesai BD</th>
                                <td>: {{ $breakdownLog->waktu_akhir_bd ? \Carbon\Carbon::parse($breakdownLog->waktu_akhir_bd)->format('d F Y H:i') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Spare Datang</th>
                                <td>: {{ $breakdownLog->waktu_spare_datang ? \Carbon\Carbon::parse($breakdownLog->waktu_spare_datang)->format('d F Y H:i') : '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="text-primary border-bottom pb-2 mb-3">Kalkulasi Waktu</h5>
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded border shadow-sm">
                                    <h6 class="text-muted small uppercase font-weight-bold">Loss Time</h6>
                                    <h4 class="text-danger mb-0 font-weight-bold">{{ $breakdownLog->loss_time ?? '-' }}</h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded border shadow-sm">
                                    <h6 class="text-muted small uppercase font-weight-bold">Loss Time (%) Per Hari</h6>
                                    <h4 class="text-info mb-0 font-weight-bold">{{ $breakdownLog->loss_time_percentage ?? '-' }}</h4>
                                    <small class="text-muted">Dibandingkan 24 Jam</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded border shadow-sm">
                                    <h6 class="text-muted small uppercase font-weight-bold">BD Tanpa Spare</h6>
                                    <h4 class="text-warning mb-0 font-weight-bold">{{ $breakdownLog->lama_bd_tanpa_spare ?? '-' }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h5 class="text-primary border-bottom pb-2 mb-3">Keterangan / Detail Kerusakan</h5>
                        <div class="p-3 bg-light rounded border" style="min-height: 100px;">
                            {!! nl2br(e($breakdownLog->keterangan ?? 'Tidak ada keterangan tambahan.')) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white text-right">
                <a href="{{ route('breakdown_logs.edit', $breakdownLog->id) }}" class="btn btn-info px-4">
                    <i class="fas fa-edit mr-1"></i> Edit Laporan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
