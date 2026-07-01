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

            .dropdown-enter {
                transition: opacity 0.15s ease, transform 0.15s ease;
            }

            .dropdown-enter-start {
                opacity: 0;
                transform: scale(0.96) translateY(-4px);
            }

            .dropdown-enter-end {
                opacity: 1;
                transform: scale(1) translateY(0);
            }

            .dropdown-leave {
                transition: opacity 0.1s ease, transform 0.1s ease;
            }

            .dropdown-leave-start {
                opacity: 1;
                transform: scale(1) translateY(0);
            }

            .dropdown-leave-end {
                opacity: 0;
                transform: scale(0.96) translateY(-4px);
            }

            .overlay-enter {
                transition: opacity 0.25s ease;
            }

            .overlay-enter-start {
                opacity: 0;
            }

            .overlay-enter-end {
                opacity: 1;
            }

            .overlay-leave {
                transition: opacity 0.2s ease;
            }

            .overlay-leave-start {
                opacity: 1;
            }

            .overlay-leave-end {
                opacity: 0;
            }

            .sidebar-enter {
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .sidebar-enter-start {
                transform: translateX(-100%);
            }

            .sidebar-enter-end {
                transform: translateX(0);
            }

            .sidebar-leave {
                transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .sidebar-leave-start {
                transform: translateX(0);
            }

            .sidebar-leave-end {
                transform: translateX(-100%);
            }

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

            @media (max-width: 639px) {
                .notif-panel {
                    position: fixed !important;
                    inset: 0 !important;
                    width: 100% !important;
                    top: 0 !important;
                    right: 0 !important;
                    border-radius: 0 !important;
                    z-index: 9999 !important;
                    display: flex;
                    flex-direction: column;
                }

                .notif-body {
                    flex: 1;
                    overflow-y: auto;
                }
            }
        </style>
    @endpush
    <div id="page-loader">
        <x-loader.loader :overlay="false" size="lg" />
    </div>
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex bg-[#F9F4EE]">
        <div x-show="sidebarOpen" x-transition:enter="overlay-enter" x-transition:enter-start="overlay-enter-start"
            x-transition:enter-end="overlay-enter-end" x-transition:leave="overlay-leave"
            x-transition:leave-start="overlay-leave-start" x-transition:leave-end="overlay-leave-end" x-cloak
            @click="sidebarOpen = false" class="fixed inset-0 bg-[#0E1A2B]/60 z-30 lg:hidden"></div>
        <aside x-show="true" x-transition:enter="sidebar-enter" x-transition:enter-start="sidebar-enter-start"
            x-transition:enter-end="sidebar-enter-end" x-transition:leave="sidebar-leave"
            x-transition:leave-start="sidebar-leave-start" x-transition:leave-end="sidebar-leave-end"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="w-60 bg-[#0E1A2B] flex flex-col fixed top-0 left-0 bottom-0 z-40 transition-transform duration-300 lg:z-20">
            <div class="px-6 pt-5 pb-3 border-b border-white/[0.07]">
                <div class="flex items-center justify-between">
                    <a href="{{ route('dashboard') }}" class="block">
                        <img src="{{ asset('storage/app/logo.png') }}" alt="{{ config('app.name') }}"
                            class="h-12 sm:h-14 md:h-16 w-auto" style="filter: brightness(0) invert(1);">
                    </a>
                    <button @click="sidebarOpen = false"
                        class="lg:hidden text-white/40 hover:text-white/80 transition-colors p-1">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <span class="text-[10px] text-[#B89C6E] tracking-[0.22em] uppercase mt-1 block">Admin Portal</span>
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
                    <span
                        class="ml-auto bg-[#B89C6E] text-[#0E1A2B] text-[10px] font-semibold px-2 py-px rounded-full">4</span>
                </a>
                <a href="#"
                    class="nav-item flex items-center gap-3 px-6 py-2.5 text-[13px] text-white/55 hover:text-white hover:bg-white/5 transition-all no-underline">
                    <i data-lucide="clock" class="w-4 h-4 shrink-0"></i> Pending Requests
                    <span
                        class="ml-auto bg-[#B89C6E] text-[#0E1A2B] text-[10px] font-semibold px-2 py-px rounded-full">2</span>
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
                    A
                </div>
                <div>
                    <div class="text-[12px] text-white/70">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                    </div>
                    <div class="text-[10px] text-white/35">Admin</div>
                </div>
                <a href="{{ route('logout')}}" class="ml-auto text-white/30 hover:text-white/70 transition-colors">
                    <i data-lucide="log-out" class="w-[15px] h-[15px]"></i>
                </a>
            </div>
        </aside>
        <div class="flex-1 flex flex-col min-h-screen lg:ml-60">
            <header
                class="bg-white border-b border-[#e5ddd3] h-[60px] px-5 lg:px-7 flex items-center justify-between sticky top-0 z-20">

                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true"
                        class="lg:hidden w-[34px] h-[34px] flex items-center justify-center border border-[#e5ddd3] bg-[#F9F4EE] text-[#6b7280] hover:border-[#0E1A2B] hover:text-[#0E1A2B] transition-colors">
                        <i data-lucide="menu" class="w-4 h-4"></i>
                    </button>

                    <div>
                        <div class="font-['Cormorant_Garamond'] text-[19px] font-medium text-[#0E1A2B] leading-tight">
                            @yield('page-title', 'Dashboard')
                        </div>
                        <div class="text-[11px] text-[#6b7280] font-light mt-px hidden sm:block">
                            @yield('page-subtitle', '')
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">

                    <div x-data="{
                        open: false,
                        seen: false,
                        notifications: [
                            { id: 1, dot: '#B89C6E', text: 'Chidi Nwosu\'s booking <strong class=\'font-medium text-[#0E1A2B]\'>#BK-2039</strong> is awaiting confirmation.', time: '10 minutes ago', read: false },
                            { id: 2, dot: '#2d6a4f', text: 'Payment of <strong class=\'font-medium text-[#0E1A2B]\'>₦280,000</strong> received for booking <strong class=\'font-medium text-[#0E1A2B]\'>#BK-2040</strong>.', time: '2 hours ago', read: false }
                        ],
                        get unreadCount() { return this.notifications.filter(n => !n.read).length },
                        dismiss(id) { this.notifications = this.notifications.filter(n => n.id !== id) },
                        markAllRead() {
                            this.notifications.forEach(n => n.read = true);
                            this.seen = true
                        }
                    }" x-init="$watch('open', v => { if (v) seen = true })" class="relative"
                        @keydown.escape.window="open = false">
                        <button @click="open = !open"
                            :class="open ? 'border-[#B89C6E] text-[#0E1A2B]' :
                                'border-[#e5ddd3] text-[#6b7280] hover:border-[#B89C6E] hover:text-[#0E1A2B]'"
                            class="relative w-[34px] h-[34px] flex items-center justify-center border bg-[#F9F4EE] transition-colors"
                            aria-label="Notifications">
                            <i data-lucide="bell" class="w-[15px] h-[15px]"></i>
                            <span x-show="!seen && unreadCount > 0"
                                class="absolute top-[7px] right-[7px] w-[6px] h-[6px] rounded-full bg-[#B89C6E] border-[1.5px] border-white"></span>
                        </button>

                        <div x-show="open" x-cloak x-transition:enter="dropdown-enter"
                            x-transition:enter-start="dropdown-enter-start" x-transition:enter-end="dropdown-enter-end"
                            x-transition:leave="dropdown-leave" x-transition:leave-start="dropdown-leave-start"
                            x-transition:leave-end="dropdown-leave-end" @click.outside="open = false"
                            class="notif-panel absolute top-[calc(100%+8px)] right-0 w-[300px] bg-white border border-[#e5ddd3] shadow-[0_8px_24px_rgba(14,26,43,0.10),0_2px_6px_rgba(14,26,43,0.05)] z-50 origin-top-right">
                            <div class="flex items-center justify-between px-3.5 py-3 border-b border-[#e5ddd3] shrink-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-[12px] font-medium text-[#0E1A2B]">Notifications</span>
                                    <span x-show="unreadCount > 0"
                                        class="text-[10px] font-semibold bg-[#B89C6E] text-[#0E1A2B] px-1.5 py-px rounded-full"
                                        x-text="unreadCount"></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button @click="markAllRead()" x-show="unreadCount > 0"
                                        class="text-[10px] text-[#B89C6E] hover:text-[#0E1A2B] transition-colors font-medium">
                                        Mark all read
                                    </button>
                                    <button @click="open = false"
                                        class="sm:hidden text-[#6b7280] hover:text-[#0E1A2B] transition-colors p-0.5">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="notif-body">
                                <template x-if="notifications.length === 0">
                                    <div class="px-3.5 py-6 text-center text-[11px] text-[#6b7280]">No notifications</div>
                                </template>
                                <template x-for="notif in notifications" :key="notif.id">
                                    <div class="flex gap-2.5 px-3.5 py-2.5 border-b border-[#e5ddd3] hover:bg-[#F9F4EE] transition-colors cursor-default"
                                        :class="notif.read ? 'opacity-60' : ''">
                                        <span class="mt-[5px] w-1.5 h-1.5 rounded-full shrink-0"
                                            :style="'background:' + notif.dot"></span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[11.5px] text-[#1a1a1a] leading-snug" x-html="notif.text"></p>
                                            <p class="text-[10px] text-[#6b7280] mt-0.5" x-text="notif.time"></p>
                                        </div>
                                        <button @click.stop="dismiss(notif.id)"
                                            class="shrink-0 mt-0.5 text-[#6b7280] hover:text-[#0E1A2B] transition-colors p-0.5">
                                            <i data-lucide="x" class="w-3 h-3"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="w-px h-[22px] bg-[#e5ddd3] mx-0.5"></div>

                    <div x-data="{ open: false }" class="relative" @keydown.escape.window="open = false">
                        <button @click="open = !open"
                            :class="open ? 'border-[#e5ddd3] bg-[#F9F4EE]' :
                                'border-transparent hover:border-[#e5ddd3] hover:bg-[#F9F4EE]'"
                            class="flex items-center gap-2 px-1.5 py-1 border transition-colors">
                            <div
                                class="w-7 h-7 rounded-full bg-[#B89C6E] flex items-center justify-center text-[11px] font-semibold text-[#0E1A2B] shrink-0">
                                A
                            </div>
                            <span
                                class="hidden sm:block text-[12px] font-medium text-[#0E1A2B] whitespace-nowrap">{{ auth()->user()->first_name }}
                                {{ auth()->user()->last_name }}</span>
                            <i data-lucide="chevron-down" :class="open ? 'rotate-180' : ''"
                                class="hidden sm:block w-3 h-3 text-[#6b7280] transition-transform duration-200"></i>
                        </button>

                        <div x-show="open" x-cloak x-transition:enter="dropdown-enter"
                            x-transition:enter-start="dropdown-enter-start" x-transition:enter-end="dropdown-enter-end"
                            x-transition:leave="dropdown-leave" x-transition:leave-start="dropdown-leave-start"
                            x-transition:leave-end="dropdown-leave-end" @click.outside="open = false"
                            class="absolute top-[calc(100%+8px)] right-0 w-[176px] bg-white border border-[#e5ddd3] shadow-[0_8px_24px_rgba(14,26,43,0.10)] z-50 origin-top-right">
                            <div class="px-3.5 py-3 border-b border-[#e5ddd3]">
                                <div class="text-[12.5px] font-medium text-[#0E1A2B]">{{ auth()->user()->first_name }}
                                    {{ auth()->user()->last_name }}</div>
                                <div class="text-[10px] text-[#6b7280] mt-0.5">Admin</div>
                            </div>
                            <a href="#"
                                class="flex items-center gap-2.5 px-3.5 py-2.5 text-[12px] text-[#1a1a1a] hover:bg-[#F9F4EE] border-b border-[#e5ddd3] transition-colors no-underline">
                                <i data-lucide="user" class="w-3.5 h-3.5 text-[#6b7280] shrink-0"></i> Profile
                            </a>
                            <a href="#"
                                class="flex items-center gap-2.5 px-3.5 py-2.5 text-[12px] text-[#1a1a1a] hover:bg-[#F9F4EE] border-b border-[#e5ddd3] transition-colors no-underline">
                                <i data-lucide="settings" class="w-3.5 h-3.5 text-[#6b7280] shrink-0"></i> Settings
                            </a>
                            <a href="{{ route('logout')}}"
                                class="flex items-center gap-2.5 px-3.5 py-2.5 text-[12px] text-[#9b1c1c] hover:bg-[#F9F4EE] transition-colors no-underline">
                                <i data-lucide="log-out" class="w-3.5 h-3.5 text-[#9b1c1c] shrink-0"></i> Sign out
                            </a>
                        </div>
                    </div>

                </div>
            </header>

            @yield('admin-content')

        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('load', function() {
                const loader = document.getElementById('page-loader');
                const elapsed = performance.now();
                const minDisplay = 3500;
                const remaining = Math.max(0, minDisplay - elapsed);
                setTimeout(function() {
                    loader.classList.add('hidden');
                }, remaining);
            });
        </script>
    @endpush
@endsection
