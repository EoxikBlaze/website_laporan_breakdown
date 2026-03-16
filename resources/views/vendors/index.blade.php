@extends('layouts.admin')

@section('title', 'Daftar Vendor')
@section('page-title', 'Master Data Vendor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header" style="background: linear-gradient(135deg,#1a1a2e,#0f3460); color:#fff;">
                <h3 class="card-title mt-1"><i class="fas fa-building mr-2"></i> Data Vendor</h3>
                <div class="card-tools">
                    <a href="{{ route('vendors.create') }}" class="btn btn-sm btn-success shadow-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Vendor
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
                        "vendors" => $vendors,
                        "routes" => [
                            "edit" => route("vendors.edit", ":id"),
                            "destroy" => route("vendors.destroy", ":id")
                        ],
                        "csrfToken" => csrf_token()
                    ];
                @endphp
                <div 
                    data-react-component="VendorTable" 
                    data-props='@json($props)'>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
