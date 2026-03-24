@extends('layouts.admin')
@section('title', 'Tambah User')
@section('page-title', 'Tambah User Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-6 py-5">
            <div class="flex items-center gap-3 text-white">
                <div class="p-2 bg-white/10 rounded-lg">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <h3 class="font-bold">Tambah User Baru</h3>
                    <p class="text-xs text-blue-100 opacity-80">Daftarkan akun administrator atau operator baru</p>
                </div>
            </div>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label for="name" class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       placeholder="Masukkan nama lengkap..."
                       class="w-full px-4 py-2.5 rounded-xl border border-neutral-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none @error('name') border-rose-300 bg-rose-50 @enderror">
                @error('name') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="email" class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Email <span class="text-rose-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       placeholder="nama@email.com"
                       class="w-full px-4 py-2.5 rounded-xl border border-neutral-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none @error('email') border-rose-300 bg-rose-50 @enderror">
                @error('email') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="role" class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Level Akses <span class="text-rose-500">*</span></label>
                    <select name="role" id="role" class="w-full h-12 px-4 rounded-xl border border-neutral-200 bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm outline-none font-medium text-neutral-700 cursor-pointer" required>
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Pilih Level Akses --</option>
                        <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="vendor_admin" {{ old('role') == 'vendor_admin' ? 'selected' : '' }}>Admin Vendor</option>
                    </select>
                    @error('role') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2" id="vendor-container" style="display: {{ old('role') === 'vendor_admin' ? 'block' : 'none' }}">
                    <label for="vendor_id" class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Pilih Vendor <span class="text-rose-500">*</span></label>
                    <select name="vendor_id" id="vendor_id" class="w-full h-12 px-4 rounded-xl border border-neutral-200 bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm outline-none font-medium text-neutral-700 cursor-pointer">
                        <option value="" {{ old('vendor_id') ? '' : 'selected' }}>-- Pilih Vendor --</option>
                        @foreach($vendors as $v)
                            <option value="{{ $v->id }}" {{ old('vendor_id') == $v->id ? 'selected' : '' }}>{{ $v->nama_vendor }}</option>
                        @endforeach
                    </select>
                    @error('vendor_id') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="p-5 bg-neutral-50 rounded-2xl border border-neutral-100 space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-neutral-200">
                    <i class="fas fa-lock text-neutral-400 text-xs"></i>
                    <h5 class="text-[10px] font-black text-neutral-400 uppercase tracking-widest">Keamanan</h5>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="password" class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Password <span class="text-rose-500">*</span></label>
                        <input type="password" id="password" name="password" required placeholder="Minimal 8 karakter"
                               class="w-full px-4 py-2.5 rounded-xl border border-neutral-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none @error('password') border-rose-300 bg-rose-50 @enderror">
                        @error('password') <p class="text-[10px] text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Konfirmasi Password <span class="text-rose-500">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password..."
                               class="w-full px-4 py-2.5 rounded-xl border border-neutral-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-600/20 transition-all active:scale-[0.98]">
                    Simpan User
                </button>
                <a href="{{ route('users.index') }}" class="px-6 py-3 text-sm font-bold text-neutral-500 hover:text-neutral-700 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('role').addEventListener('change', function() {
        const vendorContainer = document.getElementById('vendor-container');
        if (this.value === 'vendor_admin') {
            vendorContainer.style.display = 'block';
            document.getElementById('vendor_id').setAttribute('required', 'required');
        } else {
            vendorContainer.style.display = 'none';
            document.getElementById('vendor_id').removeAttribute('required');
        }
    });
</script>
@endsection
