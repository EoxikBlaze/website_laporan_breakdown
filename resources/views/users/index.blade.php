@extends('layouts.admin')
@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-800 flex items-center justify-between border-b border-blue-700">
            <div>
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i class="fas fa-users text-blue-200"></i>
                    Manajemen User
                </h3>
                <p class="text-[10px] text-blue-100 mt-0.5">Kelola akun pengguna sistem</p>
            </div>
            <div>
                <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white text-blue-700 text-xs font-bold rounded-lg hover:bg-blue-50 transition-colors shadow-sm">
                    <i class="fas fa-plus"></i>
                    Tambah User
                </a>
            </div>
        </div>
        <div class="overflow-x-auto p-4">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neutral-100 bg-neutral-50">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-neutral-500 uppercase tracking-wider">#</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Nama</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Email</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Role</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse ($users as $index => $user)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-5 py-4 text-neutral-500">{{ $index + 1 }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-neutral-800">{{ $user->name }}</span>
                                @if($user->id === Auth::id())
                                    <span class="text-[10px] bg-primary/10 text-primary px-2 py-0.5 rounded-full font-medium">Anda</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-4 text-neutral-600">{{ $user->email }}</td>
                        <td class="px-5 py-4">
                            @if($user->role === 'super_admin')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-violet-100 text-violet-700">
                                    <i class="fas fa-shield-alt text-[10px]"></i> Super Admin
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-100 text-sky-700">
                                    <i class="fas fa-user text-[10px]"></i> Operator
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('users.edit', $user) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-700 text-xs font-medium hover:bg-amber-100 transition-colors">
                                    <i class="fas fa-edit text-[10px]"></i> Edit
                                </a>
                                @if($user->id !== Auth::id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 text-xs font-medium hover:bg-rose-100 transition-colors">
                                        <i class="fas fa-trash text-[10px]"></i> Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-neutral-400 italic text-sm">
                            Belum ada user. <a href="{{ route('users.create') }}" class="text-primary hover:underline">Tambah sekarang</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
