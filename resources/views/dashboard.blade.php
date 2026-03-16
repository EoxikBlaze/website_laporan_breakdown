@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box shadow-sm" style="background: linear-gradient(135deg,#0f3460,#16213e); color:#fff; border-radius: 12px; overflow:hidden;">
                <div class="inner">
                    <h3>{{ $stats['daily_loss_time'] }}</h3>
                    <p>Loss Time Harian</p>
                </div>
                <div class="icon"><i class="fas fa-history"></i></div>
                <a href="{{ route('breakdown_logs.index') }}" class="small-box-footer" style="background: rgba(0,0,0,0.1);">Monitoring Total <i class="fas fa-arrow-circle-right ml-1"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box shadow-sm" style="background: linear-gradient(135deg,#e94560,#c73652); color:#fff; border-radius: 12px; overflow:hidden;">
                <div class="inner">
                    <h3>{{ $stats['active_breakdowns'] }}</h3>
                    <p>Breakdown Aktif</p>
                </div>
                <div class="icon"><i class="fas fa-tools"></i></div>
                <a href="{{ route('breakdown_logs.index', ['status' => 'Open']) }}" class="small-box-footer" style="background: rgba(0,0,0,0.1);">Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box shadow-sm" style="background: linear-gradient(135deg,#4e9f3d,#1e5128); color:#fff; border-radius: 12px; overflow:hidden;">
                <div class="inner">
                    <h3>{{ $stats['total_units'] }}</h3>
                    <p>Total Unit</p>
                </div>
                <div class="icon"><i class="fas fa-truck"></i></div>
                <a href="{{ route('master_units.index') }}" class="small-box-footer" style="background: rgba(0,0,0,0.1);">Master Unit <i class="fas fa-arrow-circle-right ml-1"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box shadow-sm" style="background: linear-gradient(135deg,#f9b208,#f98404); color:#fff; border-radius: 12px; overflow:hidden;">
                <div class="inner">
                    <h3>{{ $stats['total_vendors'] }}</h3>
                    <p>Data Vendor</p>
                </div>
                <div class="icon"><i class="fas fa-building"></i></div>
                <a href="{{ route('vendors.index') }}" class="small-box-footer" style="background: rgba(0,0,0,0.1);">Lihat Vendor <i class="fas fa-arrow-circle-right ml-1"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow:hidden;">
                <div class="card-header" style="background:linear-gradient(135deg,#1a1a2e,#0f3460); color:#fff; border:none;">
                    <h3 class="card-title"><i class="fas fa-history mr-2"></i> Laporan Breakdown Terbaru</h3>
                </div>
                <div class="card-body p-0">
                    @php
                        $latestProps = [
                            "reports" => $latest_reports,
                            "showRoute" => route("breakdown_logs.show", ":id")
                        ];
                    @endphp
                    <div 
                        data-react-component="LatestReportsTable" 
                        data-props='@json($latestProps)'>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
