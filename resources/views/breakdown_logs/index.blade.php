@extends('layouts.admin')

@section('title', 'Laporan Breakdown')
@section('page-title', 'Daftar Laporan Breakdown')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header" style="background: linear-gradient(135deg,#1a1a2e,#0f3460); color:#fff;">
                <h3 class="card-title mt-1"><i class="fas fa-history mr-2"></i> Riwayat Breakdown</h3>
                <div class="card-tools">
                    <a href="{{ route('breakdown_logs.export') }}" class="btn btn-sm btn-outline-success mr-2 shadow-sm" style="border-width: 2px;">
                        <i class="fas fa-file-excel mr-1"></i> Export Excel
                    </a>
                    <a href="{{ route('breakdown_logs.create') }}" class="btn btn-sm btn-success shadow-sm">
                        <i class="fas fa-plus mr-1"></i> Input Laporan
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="icon fas fa-check"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                @endif

                @php
                    $props = [
                        "logs" => $logs,
                        "canAdmin" => Gate::check("admin"),
                        "routes" => [
                            "show" => route("breakdown_logs.show", ":id"),
                            "edit" => route("breakdown_logs.edit", ":id"),
                            "destroy" => route("breakdown_logs.destroy", ":id")
                        ],
                        "csrfToken" => csrf_token()
                    ];
                @endphp
                <div 
                    data-react-component="BreakdownLogTable" 
                    data-props='@json($props)'>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
