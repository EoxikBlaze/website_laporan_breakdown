@extends('layouts.admin')

@section('title', 'Daftar Vendor')
@section('page-title', 'Master Data Vendor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-800 flex items-center justify-between border-b border-blue-700">
                <div>
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <i class="fas fa-building text-blue-200"></i>
                        Data Vendor
                    </h3>
                    <p class="text-[10px] text-blue-100 mt-0.5">Kelola informasi mitra/vendor</p>
                </div>
                <div>
                    <a href="{{ route('vendors.create') }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white text-blue-700 text-xs font-bold rounded-lg hover:bg-blue-50 transition-colors shadow-sm">
                        <i class="fas fa-plus"></i>
                        Tambah Vendor
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
