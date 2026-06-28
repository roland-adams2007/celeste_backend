@extends('app')
@section('title', $title ?? 'Admin Panel')
@section('content')
    @push('styles')
        <style>
            .nav-active {
                border-left: 2px solid #B89C6E;
                background: rgba(184, 156, 110, 0.1);
                color: #fff;
            }

            .nav-item {
                border-left: 2px solid transparent;
            }

            .drawer-slide {
                transform: translateX(100%);
                transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .drawer-open .drawer-slide {
                transform: translateX(0);
            }

            /* ── Page-load loader ───────────────────────────────── */
            #page-loader {
                position: fixed;
                inset: 0;
                z-index: 99999;
                background: #F9F4EE;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: opacity 0.5s ease, visibility 0.5s ease;
            }

            #page-loader.hidden {
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
            }
        </style>
    @endpush

    {{-- Page-load loader --}}
    <div id="page-loader">
        <x-loader.loader :overlay="false" size="lg" />
    </div>

    <aside class="w-60 bg-[#0E1A2B] flex flex-col fixed top-0 left-0 bottom-0 z-20">
        <div class="px-6 pt-5 pb-3 border-b border-white/[0.07]">
            <a href="{{ route('dashboard') }}" class="block">
                <img src="{{ asset('storage/app/logo.png') }}" alt="{{ config('app.name') }}"
                    class="h-12 sm:h-14 md:h-16 w-auto" style="filter: brightness(0) invert(1);">
            </a>
            <span class="text-[10px] text-[#B89C6E] tracking-[0.22em] uppercase mt-0.5 block">Admin Portal</span>
        </div>
        <nav class="flex-1 py-5 overflow-y-auto">
            <div class="text-[9px] tracking-[0.2em] uppercase text-white/30 px-6 pt-4 pb-1.5">Overview</div>
            <a href="{{ route('dashboard') }}"
                class="nav-item flex items-center gap-3 px-6 py-2.5 text-[13px] text-white/55 hover:text-white hover:bg-white/5 transition-all no-underline {{ request()->routeIs('dashboard') ? 'nav-active' : '' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0"></i> Dashboard
            </a>
            <div class="text-[9px] tracking-[0.2em] uppercase text-white/30 px-6 pt-4 pb-1.5">Bookings</div>
            <a href="#"
                class="nav-item flex items-center gap-3 px-6 py-2.5 text-[13px] text-white/55 hover:text-white hover:bg-white/5 transition-all no-underline">
                <i data-lucide="calendar-check" class="w-4 h-4 shrink-0"></i> All Bookings
                <span class="ml-auto bg-[#B89C6E] text-[#0E1A2B] text-[10px] font-semibold px-2 py-px rounded-full">4</span>
            </a>
            <a href="#"
                class="nav-item flex items-center gap-3 px-6 py-2.5 text-[13px] text-white/55 hover:text-white hover:bg-white/5 transition-all no-underline">
                <i data-lucide="clock" class="w-4 h-4 shrink-0"></i> Pending Requests
                <span class="ml-auto bg-[#B89C6E] text-[#0E1A2B] text-[10px] font-semibold px-2 py-px rounded-full">2</span>
            </a>
            <a href="#"
                class="nav-item flex items-center gap-3 px-6 py-2.5 text-[13px] text-white/55 hover:text-white hover:bg-white/5 transition-all no-underline">
                <i data-lucide="log-in" class="w-4 h-4 shrink-0"></i> Check-ins Today
            </a>
            <a href="#"
                class="nav-item flex items-center gap-3 px-6 py-2.5 text-[13px] text-white/55 hover:text-white hover:bg-white/5 transition-all no-underline">
                <i data-lucide="log-out" class="w-4 h-4 shrink-0"></i> Check-outs Today
            </a>
            <div class="text-[9px] tracking-[0.2em] uppercase text-white/30 px-6 pt-4 pb-1.5">Property</div>
            <a href="{{ route('rooms.index') }}"
                class="nav-item flex items-center gap-3 px-6 py-2.5 text-[13px] text-white/55 hover:text-white hover:bg-white/5 transition-all no-underline {{ request()->routeIs('rooms.*') ? 'nav-active' : '' }}">
                <i data-lucide="bed-double" class="w-4 h-4 shrink-0"></i> Rooms &amp; Suites
            </a>
            <a href="#"
                class="nav-item flex items-center gap-3 px-6 py-2.5 text-[13px] text-white/55 hover:text-white hover:bg-white/5 transition-all no-underline">
                <i data-lucide="users" class="w-4 h-4 shrink-0"></i> Guests
            </a>
            <div class="text-[9px] tracking-[0.2em] uppercase text-white/30 px-6 pt-4 pb-1.5">Finance</div>
            <a href="#"
                class="nav-item flex items-center gap-3 px-6 py-2.5 text-[13px] text-white/55 hover:text-white hover:bg-white/5 transition-all no-underline">
                <i data-lucide="bar-chart-2" class="w-4 h-4 shrink-0"></i> Revenue
            </a>
            <a href="#"
                class="nav-item flex items-center gap-3 px-6 py-2.5 text-[13px] text-white/55 hover:text-white hover:bg-white/5 transition-all no-underline">
                <i data-lucide="receipt" class="w-4 h-4 shrink-0"></i> Invoices
            </a>
            <div class="text-[9px] tracking-[0.2em] uppercase text-white/30 px-6 pt-4 pb-1.5">Settings</div>
            <a href="#"
                class="nav-item flex items-center gap-3 px-6 py-2.5 text-[13px] text-white/55 hover:text-white hover:bg-white/5 transition-all no-underline">
                <i data-lucide="settings" class="w-4 h-4 shrink-0"></i> Settings
            </a>
        </nav>
        <div class="px-6 py-4 border-t border-white/[0.07] flex items-center gap-2.5">
            <div
                class="w-8 h-8 rounded-full bg-[#B89C6E] flex items-center justify-center text-[13px] font-semibold text-[#0E1A2B] shrink-0">
                A</div>
            <div>
                <div class="text-[12px] text-white/70">Admin</div>
                <div class="text-[10px] text-white/35">Front Desk Manager</div>
            </div>
            <a href="{{ route('login') }}"
                class="ml-auto text-white/30 hover:text-white/70 transition-colors cursor-pointer">
                <i data-lucide="log-out" class="w-[15px] h-[15px]"></i>
            </a>
        </div>
    </aside>

    @yield('admin-content')

    @push('scripts')
        <script>
            window.addEventListener('load', function () {
                const loader = document.getElementById('page-loader');
                // Let the logo animate at least once (3.5s) before fading out
                const elapsed = performance.now();
                const minDisplay = 3500;
                const remaining = Math.max(0, minDisplay - elapsed);

                setTimeout(function () {
                    loader.classList.add('hidden');
                }, remaining);
            });
        </script>
    @endpush
@endsection