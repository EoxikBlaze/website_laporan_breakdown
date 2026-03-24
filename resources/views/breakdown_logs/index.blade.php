@extends('layouts.admin')

@section('title', 'Laporan Breakdown')
@section('page-title', 'Daftar Laporan Breakdown')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-800 flex items-center justify-between border-b border-blue-700">
                <div>
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <i class="fas fa-history text-blue-200"></i>
                        Riwayat Breakdown
                    </h3>
                    <p class="text-[10px] text-blue-100 mt-0.5">Daftar laporan breakdown unit aktif dan riwayat</p>
                </div>
                <div class="flex items-center gap-2">

                    <a href="{{ route('breakdown_logs.export') }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-500/20 text-white border border-blue-400/30 text-xs font-bold rounded-lg hover:bg-blue-500/40 transition-colors shadow-sm">
                        <i class="fas fa-file-excel"></i>
                        Export Excel
                    </a>
                    <a href="{{ route('breakdown_logs.create') }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white text-blue-700 text-xs font-bold rounded-lg hover:bg-blue-50 transition-colors shadow-sm">
                        <i class="fas fa-plus"></i>
                        Input Laporan
                    </a>
                </div>
            </div>
            <div class="p-6">
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
