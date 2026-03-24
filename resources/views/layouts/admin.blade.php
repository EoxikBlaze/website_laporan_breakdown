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
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; }
        
        /* Select2 Tailwind Override */
        .select2-container--default .select2-selection--single {
            height: 3rem !important; /* h-12 */
            border-radius: 0.75rem !important; /* xl */
            border: 1px solid #e5e7eb !important; /* neutral-200 */
            background-color: #ffffff !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--default.select2-container--open .select2-selection--single,
        .select2-container--default .select2-selection--single:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
            outline: none !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #374151 !important;
            font-weight: 500 !important;
            font-size: 0.875rem !important;
            padding-left: 1rem !important;
            padding-right: 2.5rem !important;
            line-height: normal !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            right: 0.5rem !important;
        }
        .select2-dropdown {
            border: 1px solid #e5e7eb !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            background-color: #ffffff !important;
            margin-top: 4px !important;
            overflow: hidden !important;
            z-index: 1050 !important;
        }
        .select2-search--dropdown .select2-search__field {
            border: 1px solid #e5e7eb !important;
            border-radius: 0.5rem !important;
            padding: 0.5rem 0.75rem !important;
            outline: none !important;
        }
        .select2-search--dropdown .select2-search__field:focus {
            border-color: #3b82f6 !important;
        }
        .select2-results__option {
            padding: 0.5rem 1rem !important;
            font-size: 0.875rem !important;
        }
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #eff6ff !important;
            color: #1d4ed8 !important;
        }
        .select2-container--default .select2-results__option--selected {
            background-color: #f3f4f6 !important;
        }
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
        'csrfToken'        => csrf_token(),
        'canManageUnits'   => Gate::check('manage-units'),
        'canManageVendors' => Gate::check('manage-vendors'),
        'canManageUsers'   => Gate::check('manage-users'),
        'currentRoute'     => request()->url(),
    ];
@endphp

<body class="bg-neutral-50 antialiased relative">
    <!-- Global Loading Overlay -->
    <div id="global-loader" class="fixed inset-0 z-[9999] bg-white/80 backdrop-blur-md flex flex-col items-center justify-center transition-all duration-500 opacity-100">
        <div class="relative flex items-center justify-center w-16 h-16">
            <div class="absolute inset-0 rounded-full border-[4px] border-blue-100"></div>
            <div class="absolute inset-0 rounded-full border-[4px] border-blue-600 border-t-transparent animate-spin"></div>
            <div class="h-6 w-6 bg-blue-600 rounded-full flex items-center justify-center text-white text-[10px] shadow-lg z-10 animate-pulse">
                <i class="fas fa-truck-pickup"></i>
            </div>
        </div>
        <p class="mt-4 text-xs font-bold text-blue-800 tracking-widest uppercase animate-pulse">Memproses Data...</p>
    </div>

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

    <!-- jQuery & Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Global Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const loader = document.getElementById('global-loader');
            
            function showLoader() {
                loader.classList.remove('opacity-0', 'pointer-events-none');
                loader.classList.add('opacity-100');
            }

            function hideLoader() {
                loader.classList.remove('opacity-100');
                loader.classList.add('opacity-0', 'pointer-events-none');
                document.querySelectorAll('form button[type="submit"]').forEach(btn => btn.disabled = false);
            }

            // Hide loader 1 second after DOM loads to allow React islands to hydrate smoothly
            setTimeout(() => {
                hideLoader();
            }, 1000);

            // Show on form submit
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', (e) => {
                    if (form.checkValidity()) {
                        showLoader();
                        // Disable buttons slightly after to allow form to submit
                        setTimeout(() => {
                            const btns = form.querySelectorAll('button[type="submit"], input[type="submit"]');
                            btns.forEach(btn => btn.disabled = true);
                        }, 50);
                    }
                });
            });

            // Show on regular links
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a');
                if (!link) return;

                const href = link.getAttribute('href');
                const target = link.getAttribute('target');
                const isDownload = link.hasAttribute('download');
                const hasOnClick = link.hasAttribute('onclick');
                
                // Allow specific exports or routes to bypass loader if needed
                if (href && href.includes('/export')) return;

                if (href && !href.startsWith('#') && !href.startsWith('javascript:') && target !== '_blank' && !isDownload && !hasOnClick) {
                    if (href !== window.location.href) {
                        showLoader();
                    }
                }
            });

            // Hide if navigating Back/Forward from cache
            window.addEventListener('pageshow', (e) => {
                if (e.persisted) hideLoader();
            });

            // Initialize Select2 natively on all standard selects
            $('select').select2({
                width: '100%',
                minimumResultsForSearch: 3 // Only show search box if there are more than 3 options
            });
            
            // Re-trigger Select2 change events for specific component logic
            $('select').on('change', function() {
                $(this).trigger('input');
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
