@extends('layouts.admin')

@section('title', 'Import Database')
@section('page-title', 'Universal Database Restore')

@section('content')
<div class="row">
    <div class="col-12 max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-emerald-600 to-emerald-800 flex items-center justify-between border-b border-emerald-700">
                <div>
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <i class="fas fa-database text-emerald-200"></i>
                        Restore Data via Excel
                    </h3>
                    <p class="text-xs text-emerald-100 mt-1">Fasilitas eksklusif Super Admin untuk mengembalikan data massal ke sistem.</p>
                </div>
            </div>
            
            <form action="{{ route('database_imports.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 md:p-8 bg-neutral-50/50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Target Table Selection -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Pilih Tabel Tujuan</label>
                            <select name="target_table" id="target_table" required class="w-full h-12 px-4 rounded-xl border border-neutral-200 bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm outline-none font-medium text-neutral-700 cursor-pointer">
                                <option value="" disabled selected>-- Pilih Tabel Output --</option>
                                <option value="breakdowns">Laporan Breakdown</option>
                                <option value="units">Data Master Unit</option>
                                <option value="users">Data Akses User</option>
                                <option value="vendors">Data Profil Vendor</option>
                            </select>
                            <div class="mt-2 text-right opacity-0 transition-opacity duration-300" id="template_container">
                                <a id="download_template_btn" href="#" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1.5 justify-end w-fit ml-auto bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg border border-blue-200">
                                    <i class="fas fa-file-excel"></i> Download Template Kosong
                                </a>
                            </div>
                        </div>

                        <!-- File Upload -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Pilih File Spreadsheet (.xlsx)</label>
                            <input type="file" name="excel_file" accept=".xlsx, .xls, .csv" required class="w-full h-12 px-4 rounded-xl border border-neutral-200 bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm outline-none font-medium text-neutral-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-white border-t border-neutral-100 flex items-center justify-end gap-3">
                    <button type="submit" class="px-6 py-3 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700 shadow-md shadow-emerald-500/20 transition-all active:scale-[0.98] flex items-center gap-2">
                        <i class="fas fa-cloud-upload-alt"></i>
                        Mulai Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const $select = $('#target_table');
        const $container = $('#template_container');
        const $btn = $('#download_template_btn');
        
        $select.on('change', function() {
            const type = $(this).val();
            if (type) {
                // Dynamically build URL and fade in
                $btn.attr('href', `{{ url('database_imports/template') }}/${type}`);
                $container.removeClass('opacity-0 pointer-events-none fade-out').addClass('opacity-100');
                
                // Also trigger click on the hyperlink if user clicks download
                $btn.off('click').on('click', function(e) {
                    // Bypass the global loading overlay specifically for this download
                    $('#global-loader').addClass('opacity-0 pointer-events-none').removeClass('opacity-100');
                });
            } else {
                $container.removeClass('opacity-100').addClass('opacity-0 pointer-events-none');
            }
        });
        
        // Trigger once on load in case browser pre-filled
        if ($select.val()) {
            $select.trigger('change');
        }
    });
</script>
@endpush
@endsection
