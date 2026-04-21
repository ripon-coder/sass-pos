<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — {{ config('app.name', 'POS SaaS') }}</title>
    <meta name="description" content="Multi-Tenant POS SaaS Admin Panel">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full" x-data="appShell()" x-init="init()">

{{-- App Shell --}}
<div class="flex h-full">

    {{-- ========== SIDEBAR ========== --}}
    <aside
        id="sidebar"
        class="fixed inset-y-0 left-0 z-30 flex flex-col bg-white border-r border-slate-200 transition-all duration-200 ease-in-out"
        :class="sidebarOpen ? 'w-60' : 'w-16'"
    >
        {{-- Brand / Logo --}}
        <div class="flex items-center gap-3 px-4 py-4 border-b border-slate-200 min-h-[60px]">
            <div class="flex-shrink-0 w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.943-7.143a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
            </div>
            <span class="font-semibold text-slate-800 text-sm tracking-tight truncate transition-opacity duration-200" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">POS SaaS</span>
        </div>

        {{-- Tenant Switcher --}}
        <div class="px-3 py-3 border-b border-slate-100" x-show="sidebarOpen" x-cloak>
            <div x-data="{ open: false }" class="relative">
                <button
                    @click="open = !open"
                    class="w-full flex items-center gap-2 px-3 py-2 rounded-md bg-slate-50 border border-slate-200 text-left hover:bg-slate-100 transition-colors"
                    id="tenant-switcher-btn"
                >
                    <div class="w-6 h-6 rounded bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-semibold text-blue-600">A</span>
                    </div>
                    <div class="overflow-hidden flex-1">
                        <p class="text-xs font-semibold text-slate-800 truncate">Acme Store</p>
                        <p class="text-[11px] text-slate-500 truncate">acme.possaas.com</p>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0 transition-transform" :class="open && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div
                    x-show="open" @click.outside="open = false"
                    x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="absolute top-full left-0 right-0 mt-1 bg-white border border-slate-200 rounded-md shadow-lg z-50 py-1"
                    x-cloak
                >
                    <div class="px-2 py-1.5 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Switch Store</div>
                    <button class="w-full flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-left">
                        <div class="w-6 h-6 rounded bg-blue-100 flex items-center justify-center">
                            <span class="text-xs font-semibold text-blue-600">A</span>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-800">Acme Store</p>
                            <p class="text-[11px] text-slate-500">acme.possaas.com</p>
                        </div>
                    </button>
                    <button class="w-full flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-left">
                        <div class="w-6 h-6 rounded bg-violet-100 flex items-center justify-center">
                            <span class="text-xs font-semibold text-violet-600">B</span>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-800">Beta Market</p>
                            <p class="text-[11px] text-slate-500">beta.possaas.com</p>
                        </div>
                    </button>
                    <div class="border-t border-slate-100 mt-1 pt-1">
                        <button class="w-full flex items-center gap-2 px-3 py-2 hover:bg-slate-50 text-left text-xs font-medium text-blue-600">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Add new store
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto overflow-x-hidden px-2 py-3 space-y-0.5">

            <div class="px-2 py-1.5" x-show="sidebarOpen" x-cloak>
                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Main</p>
            </div>

            <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" title="Dashboard">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>
                <span class="truncate transition-all" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">Dashboard</span>
            </a>

            <a href="/pos" class="nav-link {{ request()->is('pos') ? 'active' : '' }}" title="POS Screen">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.943-7.143a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
                <span class="truncate transition-all" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">POS Screen</span>
            </a>

            <div class="px-2 py-1.5 mt-2" x-show="sidebarOpen" x-cloak>
                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Catalog</p>
            </div>

            <a href="/products" class="nav-link {{ request()->is('products*') ? 'active' : '' }}" title="Products">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
                <span class="truncate transition-all" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">Products</span>
            </a>

            <a href="/orders" class="nav-link {{ request()->is('orders*') ? 'active' : '' }}" title="Orders">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                </svg>
                <span class="truncate transition-all" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">Orders</span>
            </a>

            <a href="/customers" class="nav-link {{ request()->is('customers*') ? 'active' : '' }}" title="Customers">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
                <span class="truncate transition-all" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">Customers</span>
            </a>

            <a href="/staff" class="nav-link {{ request()->is('staff*') ? 'active' : '' }}" title="Staff">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                </svg>
                <span class="truncate transition-all" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">Staff</span>
            </a>

            <div class="px-2 py-1.5 mt-2" x-show="sidebarOpen" x-cloak>
                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Account</p>
            </div>

            <a href="/settings" class="nav-link {{ request()->is('settings*') ? 'active' : '' }}" title="Settings">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="truncate transition-all" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">Settings</span>
            </a>
        </nav>

        {{-- Sidebar Footer --}}
        <div class="border-t border-slate-200 px-3 py-3">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-full bg-slate-200 flex items-center justify-center flex-shrink-0">
                    <span class="text-xs font-semibold text-slate-600">JD</span>
                </div>
                <div class="overflow-hidden flex-1 transition-all" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                    <p class="text-xs font-semibold text-slate-700 truncate">John Doe</p>
                    <p class="text-[11px] text-slate-500 truncate">Admin</p>
                </div>
            </div>
        </div>
    </aside>
    {{-- END SIDEBAR --}}

    {{-- ========== MAIN CONTENT AREA ========== --}}
    <div class="flex flex-col flex-1 min-w-0 transition-all duration-200 ease-in-out" :class="sidebarOpen ? 'ml-60' : 'ml-16'">

        {{-- TOP NAVBAR --}}
        <header class="sticky top-0 z-20 flex items-center gap-4 px-4 border-b border-slate-200 bg-white/90 backdrop-blur-sm min-h-[60px]">

            {{-- Sidebar Toggle --}}
            <button
                @click="sidebarOpen = !sidebarOpen"
                class="btn btn-ghost btn-icon text-slate-500"
                id="sidebar-toggle-btn"
                :aria-label="sidebarOpen ? 'Collapse sidebar' : 'Expand sidebar'"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                </svg>
            </button>

            {{-- Page Title (injected from page) --}}
            <h1 class="text-sm font-semibold text-slate-700">{{ $pageTitle ?? 'Dashboard' }}</h1>

            {{-- Search --}}
            <div class="flex-1 max-w-sm hidden md:block">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input type="search" placeholder="Search…" class="form-input pl-9 py-2 text-sm" id="global-search">
                </div>
            </div>

            <div class="flex items-center gap-2 ml-auto">
                {{-- Notifications --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="btn btn-ghost btn-icon text-slate-500 relative" id="notification-btn">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-blue-600 rounded-full border-2 border-white"></span>
                    </button>
                    <div
                        x-show="open" @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-80 bg-white border border-slate-200 rounded-lg shadow-lg z-50"
                        x-cloak
                    >
                        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                            <p class="text-sm font-semibold text-slate-800">Notifications</p>
                            <button class="text-xs text-blue-600 font-medium hover:underline">Mark all read</button>
                        </div>
                        <div class="py-1 max-h-72 overflow-y-auto divide-y divide-slate-50">
                            <div class="flex gap-3 px-4 py-3 hover:bg-slate-50 cursor-pointer">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-700 font-medium">New order #1042 placed</p>
                                    <p class="text-xs text-slate-400 mt-0.5">2 minutes ago</p>
                                </div>
                            </div>
                            <div class="flex gap-3 px-4 py-3 hover:bg-slate-50 cursor-pointer">
                                <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-700 font-medium">Low stock: Wireless Headphones</p>
                                    <p class="text-xs text-slate-400 mt-0.5">1 hour ago</p>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-2.5 border-t border-slate-100">
                            <a href="#" class="text-xs text-blue-600 font-medium hover:underline">View all notifications</a>
                        </div>
                    </div>
                </div>

                {{-- Profile Dropdown --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 px-2 py-1.5 rounded-md hover:bg-slate-50 transition-colors" id="profile-menu-btn">
                        <div class="w-7 h-7 rounded-full bg-slate-200 flex items-center justify-center">
                            <span class="text-xs font-semibold text-slate-600">JD</span>
                        </div>
                        <span class="text-sm font-medium text-slate-700 hidden sm:block">John Doe</span>
                        <svg class="w-3.5 h-3.5 text-slate-400 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div
                        x-show="open" @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-lg shadow-lg z-50 py-1"
                        x-cloak
                    >
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="text-sm font-semibold text-slate-800">John Doe</p>
                            <p class="text-xs text-slate-500">john@acme.com</p>
                        </div>
                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                            Profile
                        </a>
                        <a href="/settings" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Settings
                        </a>
                        <div class="border-t border-slate-100 mt-1 pt-1">
                            <form method="POST" action="/logout">
                                @csrf
                                <button class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        {{-- END TOPBAR --}}

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto">
            {{-- Toast Container --}}
            <div
                id="toast-container"
                x-data="toastManager()"
                @show-toast.window="addToast($event.detail)"
                class="fixed bottom-4 right-4 z-50 flex flex-col gap-2"
            >
                <template x-for="toast in toasts" :key="toast.id">
                    <div
                        x-show="toast.visible"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="flex items-center gap-3 px-4 py-3 bg-white border border-slate-200 rounded-lg shadow-lg min-w-[280px] max-w-sm"
                    >
                        <span x-show="toast.type === 'success'" class="w-5 h-5 text-green-500">
                            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </span>
                        <span x-show="toast.type === 'error'" class="w-5 h-5 text-red-500">
                            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                        </span>
                        <span x-show="toast.type === 'info'" class="w-5 h-5 text-blue-500">
                            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                        </span>
                        <p class="text-sm text-slate-700 flex-1" x-text="toast.message"></p>
                        <button @click="removeToast(toast.id)" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </template>
            </div>

            {{ $slot }}
        </main>
    </div>
</div>

<script>
function appShell() {
    return {
        sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
        init() {
            this.$watch('sidebarOpen', val => localStorage.setItem('sidebarOpen', val));
        }
    };
}

function toastManager() {
    return {
        toasts: [],
        addToast({ message, type = 'info', duration = 4000 }) {
            const id = Date.now();
            this.toasts.push({ id, message, type, visible: true });
            setTimeout(() => this.removeToast(id), duration);
        },
        removeToast(id) {
            const t = this.toasts.find(t => t.id === id);
            if (t) t.visible = false;
            setTimeout(() => { this.toasts = this.toasts.filter(t => t.id !== id); }, 200);
        }
    };
}
</script>
</body>
</html>
