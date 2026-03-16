<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'General Service PT. PAMA SITE . ARIA')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- AdminLTE CSS (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        body {
            font-family: 'Source Sans Pro', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
    </style>

    @stack('styles')
    
    <!-- Vite Scripts -->
    @vite(['resources/js/app.tsx'])
</head>
@php
    $sidebarProps = [
        'user' => [
            'name' => Auth::user()->name ?? 'User',
            'email' => Auth::user()->email ?? '',
        ],
        'routes' => [
            'dashboard' => route('dashboard'),
            'units' => route('master_units.index'),
            'vendors' => route('vendors.index'),
            'breakdownCreate' => route('breakdown_logs.create'),
            'breakdownIndex' => route('breakdown_logs.index'),
            'users' => route('users.index'),
            'logout' => route('logout'),
        ],
        'csrfToken' => csrf_token(),
        'canAdmin' => Gate::check('admin'),
        'currentRoute' => request()->url(),
    ];
@endphp

<body class="bg-neutral-50 font-sans">
    <div class="flex flex-col md:flex-row h-screen w-full overflow-hidden">
        {{-- REACT SIDEBAR ISLAND --}}
        <div 
            data-react-component="AppSidebar" 
            data-props='@json($sidebarProps)'
            class="h-auto md:h-full"
        ></div>

        {{-- MAIN CONTENT --}}
        <div class="flex flex-col flex-1 h-full overflow-y-auto bg-neutral-100/50">
            <header class="bg-white/80 backdrop-blur-md sticky top-0 z-30 border-b border-neutral-200 px-6 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-neutral-800">
                        @yield('page-title', 'Dashboard')
                    </h1>
                </div>
                <div class="hidden md:flex items-center gap-4">
                    <span class="text-sm text-neutral-500 font-medium">GS Management System v1.0</span>
                </div>
            </header>

            <main class="flex-1 w-full max-w-7xl mx-auto p-4 md:p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-100 border border-emerald-200 text-emerald-800 flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
                        <i class="fas fa-check-circle h-5 w-5"></i>
                        <span class="font-medium text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-rose-100 border border-rose-200 text-rose-800 flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
                        <i class="fas fa-exclamation-circle h-5 w-5"></i>
                        <span class="font-medium text-sm">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="p-6 text-center text-sm text-neutral-400 border-t border-neutral-200">
                &copy; {{ date('Y') }} <span class="font-bold text-neutral-600">PT. Pamapersada Nusantara - GS ARIA</span>. All rights reserved.
            </footer>
        </div>
    </div>

<!-- Scripts (CDN) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

@stack('scripts')
</body>
</html>
