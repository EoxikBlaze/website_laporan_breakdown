@extends('layouts.admin')

@section('title', 'Daftar Unit')
@section('page-title', 'Master Data Unit')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header" style="background: linear-gradient(135deg,#1a1a2e,#0f3460); color:#fff;">
                <h3 class="card-title mt-1"><i class="fas fa-truck mr-2"></i> Data Unit</h3>
                <div class="card-tools">
                    <a href="{{ route('master_units.create') }}" class="btn btn-sm btn-success shadow-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Unit
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

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="icon fas fa-ban"></i> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                @endif

                @php
                    $props = [
                        "units" => $units,
                        "editRoute" => route("master_units.edit", ":id"),
                        "deleteRoute" => route("master_units.destroy", ":id"),
                        "csrfToken" => csrf_token()
                    ];
                @endphp
                <div 
                    data-react-component="MasterUnitTable" 
                    data-props='@json($props)'>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
