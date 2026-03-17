<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | GS PT. PAMA SITE ARIA</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; }
    </style>

    @stack('styles')

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
</head>

@php
    $sidebarProps = [
        'user' => [
            'name'  => Auth::user()->name  ?? 'User',
            'email' => Auth::user()->email ?? '',
            'role'  => Auth::user()->role  ?? 'operator',
        ],
        'routes' => [
            'dashboard'     => route('dashboard'),
            'units'         => route('master_units.index'),
            'vendors'       => route('vendors.index'),
            'breakdownCreate' => route('breakdown_logs.create'),
            'breakdownIndex'  => route('breakdown_logs.index'),
            'users'         => route('users.index'),
            'logout'        => route('logout'),
        ],
        'csrfToken'    => csrf_token(),
        'canAdmin'     => Gate::check('admin'),
        'currentRoute' => request()->url(),
    ];
@endphp

<body class="bg-neutral-50 antialiased">
    <div class="flex flex-col md:flex-row h-screen w-full overflow-hidden">

        {{-- ── React Sidebar Island ─── --}}
        <div
            data-react-component="AppSidebar"
            data-props='@json($sidebarProps)'
            class="shrink-0"
        ></div>

        {{-- ── Main Content ───────── --}}
        <div class="flex flex-col flex-1 h-full overflow-y-auto bg-neutral-100/60 min-w-0">

            {{-- Top Bar --}}
            <header class="sticky top-0 z-30 bg-blue-700 backdrop-blur-md border-b border-blue-800 px-5 md:px-8 py-4 flex items-center justify-between shadow-md">
                <div>
                    <h1 class="text-lg font-bold text-white leading-tight">
                        @yield('page-title', 'Dashboard')
                    </h1>
                    <p class="text-xs text-blue-100 mt-0.5 hidden md:block">GS Management System · PT. PAMA SITE ARIA</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden md:flex items-center gap-2 bg-white/10 rounded-xl px-3 py-2 border border-white/20">
                        <div class="h-7 w-7 rounded-full bg-white flex items-center justify-center text-blue-700 font-bold text-xs shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-white leading-none">{{ Auth::user()->name ?? 'User' }}</p>
                            <p class="text-[10px] text-blue-100 mt-0.5">
                                @if(Auth::user()->role === 'super_admin') Super Admin 
                                @elseif(Auth::user()->role === 'vendor_admin') Admin Vendor 
                                @else Operator @endif
                            </p>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Alerts --}}
            <div class="px-5 md:px-8 pt-5">
                @if(session('success'))
                <div class="mb-4 flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm shadow-xs">
                    <i class="fas fa-check-circle text-emerald-500"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                @endif
                @if(session('error'))
                <div class="mb-4 flex items-center gap-3 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm shadow-xs">
                    <i class="fas fa-exclamation-circle text-rose-500"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                @endif
            </div>

            {{-- Page Content --}}
            <main class="flex-1 w-full px-5 md:px-8 pb-8">
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="px-8 py-4 text-center text-xs text-neutral-400 border-t border-neutral-200 bg-white">
                &copy; {{ date('Y') }} <span class="font-semibold text-neutral-600">PT. Pamapersada Nusantara — GS ARIA</span>. All rights reserved.
            </footer>
        </div>
    </div>

    <!-- jQuery (Needed for legacy Select2 components) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @stack('scripts')
</body>
</html>
