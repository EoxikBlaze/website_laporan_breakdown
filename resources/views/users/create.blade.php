@extends('layouts.admin')
@section('title', 'Tambah User')
@section('page-title', 'Tambah User Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="rounded-2xl border border-neutral-200 bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-neutral-100 bg-neutral-50">
            <h2 class="text-lg font-semibold text-neutral-800">Informasi Akun</h2>
            <p class="text-sm text-neutral-500 mt-0.5">Isi data user baru di bawah ini</p>
        </div>
        <form action="{{ route('users.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-semibold text-neutral-700 mb-1.5">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    placeholder="Masukkan nama lengkap..."
                    class="w-full px-4 py-2.5 rounded-xl border border-neutral-200 text-sm text-neutral-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all @error('name') border-rose-300 bg-rose-50 @enderror">
                @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-neutral-700 mb-1.5">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    placeholder="nama@email.com"
                    class="w-full px-4 py-2.5 rounded-xl border border-neutral-200 text-sm text-neutral-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all @error('email') border-rose-300 bg-rose-50 @enderror">
                @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="role" class="block text-sm font-semibold text-neutral-700 mb-1.5">Level Akses</label>
                <select id="role" name="role" required
                    class="w-full px-4 py-2.5 rounded-xl border border-neutral-200 text-sm text-neutral-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all @error('role') border-rose-300 bg-rose-50 @enderror">
                    <option value="">-- Pilih Level Akses --</option>
                    <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin (Akses Penuh)</option>
                    <option value="operator" {{ old('role') === 'operator' ? 'selected' : '' }}>Operator (Penginput Laporan)</option>
                </select>
                @error('role') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                <p class="mt-1.5 text-xs text-neutral-400">Operator hanya dapat melakukan CRUD laporan dan tidak bisa mengunduh laporan.</p>
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-neutral-700 mb-1.5">Password</label>
                <input type="password" id="password" name="password" required
                    placeholder="Minimal 8 karakter"
                    class="w-full px-4 py-2.5 rounded-xl border border-neutral-200 text-sm text-neutral-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all @error('password') border-rose-300 bg-rose-50 @enderror">
                @error('password') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-neutral-700 mb-1.5">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    placeholder="Ulangi password..."
                    class="w-full px-4 py-2.5 rounded-xl border border-neutral-200 text-sm text-neutral-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-primary text-white font-semibold text-sm hover:bg-primary/90 transition-colors shadow-sm">
                    Simpan User
                </button>
                <a href="{{ route('users.index') }}"
                    class="px-5 py-2.5 rounded-xl bg-neutral-100 text-neutral-700 font-medium text-sm hover:bg-neutral-200 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
