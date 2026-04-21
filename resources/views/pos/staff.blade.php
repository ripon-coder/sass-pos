<x-layouts.admin pageTitle="Staff Management">

<div class="p-6 space-y-5" x-data="staffPage()" x-init="init()">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Staff Management</h2>
            <p class="text-sm text-slate-500 mt-0.5">Monitor performance, manage roles, and track activity</p>
        </div>
        <button @click="openAddModal()" class="btn btn-primary" id="add-staff-btn">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
            </svg>
            Add Staff
        </button>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="flex items-start justify-between">
                <p class="stat-label">Total Staff</p>
                <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                </div>
            </div>
            <p class="stat-value" x-text="staff.length"></p>
            <div class="stat-delta stat-delta-up">
                <span x-text="staff.filter(s => s.status === 'active').length + ' currently active'"></span>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-start justify-between">
                <p class="stat-label">Today's Team Sales</p>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <p class="stat-value" x-text="'$' + staff.reduce((s, m) => s + m.todaySales, 0).toLocaleString()"></p>
            <div class="stat-delta stat-delta-up">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" /></svg>
                <span>14% vs yesterday</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-start justify-between">
                <p class="stat-label">Total Orders Today</p>
                <div class="w-8 h-8 rounded-xl bg-violet-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                </div>
            </div>
            <p class="stat-value" x-text="staff.reduce((s, m) => s + m.todayOrders, 0)"></p>
            <div class="stat-delta stat-delta-up">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" /></svg>
                <span>8 more than yesterday</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-start justify-between">
                <p class="stat-label">Avg Order Value</p>
                <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" /></svg>
                </div>
            </div>
            <p class="stat-value">$42.50</p>
            <div class="stat-delta stat-delta-down">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" /></svg>
                <span>3.2% vs last week</span>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 max-w-xs">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            <input type="search" x-model="search" @input="filter()" placeholder="Search by name or email…" class="form-input pl-9" id="staff-search">
        </div>
        <select x-model="filterRole" @change="filter()" class="form-input form-select w-36" id="staff-role-filter">
            <option value="">All Roles</option>
            <option value="admin">Admin</option>
            <option value="manager">Manager</option>
            <option value="cashier">Cashier</option>
        </select>
        <select x-model="filterStatus" @change="filter()" class="form-input form-select w-36" id="staff-status-filter">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="offline">Offline</option>
        </select>
        <span class="text-xs text-slate-400 ml-auto" x-text="filtered.length + ' staff members'"></span>
    </div>

    {{-- ===== STAFF TABLE ===== --}}
    <div class="card">
        <div class="table-wrapper">
            <table class="table" id="staff-table">
                <thead>
                    <tr>
                        <th>Staff Member</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>
                            <button @click="sortBy('todaySales')" class="flex items-center gap-1 hover:text-slate-700">
                                Today Sales <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" /></svg>
                            </button>
                        </th>
                        <th>
                            <button @click="sortBy('todayOrders')" class="flex items-center gap-1 hover:text-slate-700">
                                Orders <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" /></svg>
                            </button>
                        </th>
                        <th>Avg Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loading Skeleton --}}
                    <template x-if="loading">
                        <template x-for="i in 4" :key="'skel-'+i">
                            <tr>
                                <td><div class="flex items-center gap-3"><div class="skeleton w-8 h-8 rounded-full"></div><div><div class="skeleton h-3.5 w-28 mb-1.5"></div><div class="skeleton h-3 w-20"></div></div></div></td>
                                <td><div class="skeleton h-5 w-16 rounded-full"></div></td>
                                <td><div class="skeleton h-5 w-14 rounded-full"></div></td>
                                <td><div class="skeleton h-4 w-16"></div></td>
                                <td><div class="skeleton h-4 w-8"></div></td>
                                <td><div class="skeleton h-4 w-12"></div></td>
                                <td><div class="skeleton h-4 w-6"></div></td>
                            </tr>
                        </template>
                    </template>

                    {{-- Empty State --}}
                    <tr x-show="!loading && filtered.length === 0" x-cloak>
                        <td colspan="7">
                            <div class="flex flex-col items-center justify-center py-14 text-center">
                                <svg class="w-12 h-12 text-slate-200 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                                <p class="text-sm font-medium text-slate-500">No staff members found</p>
                                <p class="text-xs text-slate-400 mt-1">Try adjusting your search or filters</p>
                            </div>
                        </td>
                    </tr>

                    {{-- Rows --}}
                    <template x-for="s in filtered" :key="s.id">
                        <tr x-show="!loading">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold flex-shrink-0"
                                        :class="s.avatarColor"
                                        x-text="s.name.split(' ').map(n => n[0]).join('')"
                                    ></div>
                                    <div>
                                        <p class="font-medium text-slate-700" x-text="s.name"></p>
                                        <p class="text-xs text-slate-400" x-text="s.email"></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge"
                                    :class="{
                                        'badge-info':    s.role === 'admin',
                                        'badge-warning': s.role === 'manager',
                                        'badge-gray':    s.role === 'cashier',
                                    }"
                                    x-text="s.role.charAt(0).toUpperCase() + s.role.slice(1)"
                                ></span>
                            </td>
                            <td>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-1.5 h-1.5 rounded-full" :class="s.status === 'active' ? 'bg-green-500' : 'bg-slate-300'"></div>
                                    <span class="text-xs font-medium" :class="s.status === 'active' ? 'text-green-600' : 'text-slate-400'" x-text="s.status === 'active' ? 'Active' : 'Offline'"></span>
                                </div>
                            </td>
                            <td class="font-semibold text-slate-800" x-text="'$' + s.todaySales.toLocaleString()"></td>
                            <td class="text-slate-600" x-text="s.todayOrders"></td>
                            <td class="text-slate-600" x-text="s.todayOrders > 0 ? '$' + (s.todaySales / s.todayOrders).toFixed(2) : '—'"></td>
                            <td>
                                {{-- Action Dropdown --}}
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="btn btn-ghost btn-icon btn-sm text-slate-400 hover:text-slate-600">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                                    </button>
                                    <div x-show="open" @click.outside="open = false"
                                        x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        class="absolute right-0 mt-1 w-40 bg-white border border-slate-200 rounded-lg shadow-lg z-20 py-1" x-cloak>
                                        <button @click="openDrawer(s); open = false" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 text-left">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            View Details
                                        </button>
                                        <button @click="openEditModal(s); open = false" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 text-left">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" /></svg>
                                            Edit
                                        </button>
                                        <div class="border-t border-slate-100 my-1"></div>
                                        <button @click="confirmDisable(s); open = false" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 text-left">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                            Disable Account
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- ===== STAFF DETAIL DRAWER (right panel) ===== --}}
    {{-- ================================================================ --}}
    <div x-show="drawer.open" class="fixed inset-0 z-50 flex" x-cloak>
        <div class="absolute inset-0 bg-black/30" @click="drawer.open = false"></div>
        <div
            class="absolute right-0 top-0 bottom-0 w-full max-w-xl bg-white shadow-xl flex flex-col"
            x-transition:enter="transition ease-out duration-250" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"  x-transition:leave-start="translate-x-0"    x-transition:leave-end="translate-x-full"
            id="staff-detail-drawer"
        >
            {{-- Drawer Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold"
                        :class="drawer.staff?.avatarColor"
                        x-text="drawer.staff?.name?.split(' ').map(n => n[0]).join('')"
                    ></div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-800" x-text="drawer.staff?.name"></h2>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="badge badge-sm"
                                :class="{
                                    'badge-info':    drawer.staff?.role === 'admin',
                                    'badge-warning': drawer.staff?.role === 'manager',
                                    'badge-gray':    drawer.staff?.role === 'cashier',
                                }"
                                x-text="drawer.staff?.role?.charAt(0).toUpperCase() + drawer.staff?.role?.slice(1)"
                            ></span>
                            <div class="flex items-center gap-1">
                                <div class="w-1.5 h-1.5 rounded-full" :class="drawer.staff?.status === 'active' ? 'bg-green-500' : 'bg-slate-300'"></div>
                                <span class="text-xs" :class="drawer.staff?.status === 'active' ? 'text-green-600' : 'text-slate-400'" x-text="drawer.staff?.status === 'active' ? 'Active now' : 'Offline'"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <button @click="drawer.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            {{-- Drawer Tabs --}}
            <div class="border-b border-slate-200 px-6">
                <nav class="flex gap-5" aria-label="Staff detail tabs">
                    <template x-for="tab in ['Overview', 'Activity', 'Shifts']" :key="tab">
                        <button @click="drawer.tab = tab"
                            class="pb-3 text-sm font-medium border-b-2 transition-colors -mb-px"
                            :class="drawer.tab === tab ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                            x-text="tab"
                        ></button>
                    </template>
                </nav>
            </div>

            {{-- Drawer Content --}}
            <div class="flex-1 overflow-y-auto p-6 space-y-5">

                {{-- ====== TAB: OVERVIEW ====== --}}
                <div x-show="drawer.tab === 'Overview'">

                    {{-- Performance Cards --}}
                    <div class="grid grid-cols-2 gap-3 mb-5">
                        <div class="stat-card py-3 px-4">
                            <p class="text-[11px] font-medium text-slate-400 uppercase">Today Sales</p>
                            <p class="text-xl font-bold text-slate-800 mt-1" x-text="'$' + (drawer.staff?.todaySales || 0).toLocaleString()"></p>
                        </div>
                        <div class="stat-card py-3 px-4">
                            <p class="text-[11px] font-medium text-slate-400 uppercase">Weekly Sales</p>
                            <p class="text-xl font-bold text-slate-800 mt-1" x-text="'$' + (drawer.staff?.weeklySales || 0).toLocaleString()"></p>
                        </div>
                        <div class="stat-card py-3 px-4">
                            <p class="text-[11px] font-medium text-slate-400 uppercase">Total Orders</p>
                            <p class="text-xl font-bold text-slate-800 mt-1" x-text="drawer.staff?.todayOrders || 0"></p>
                        </div>
                        <div class="stat-card py-3 px-4">
                            <p class="text-[11px] font-medium text-slate-400 uppercase">Avg Order Value</p>
                            <p class="text-xl font-bold text-slate-800 mt-1" x-text="drawer.staff?.todayOrders > 0 ? '$' + (drawer.staff.todaySales / drawer.staff.todayOrders).toFixed(2) : '—'"></p>
                        </div>
                    </div>

                    {{-- Mini Sales Trend Chart (CSS bars) --}}
                    <div class="card mb-5">
                        <div class="card-header">
                            <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Sales Trend (Last 7 Days)</h3>
                        </div>
                        <div class="card-body">
                            <div class="flex items-end gap-2 h-28">
                                <template x-for="(val, idx) in drawer.staff?.salesTrend || []" :key="idx">
                                    <div class="flex-1 flex flex-col items-center gap-1">
                                        <div class="w-full rounded-t-md transition-all"
                                            :class="idx === (drawer.staff?.salesTrend?.length - 1) ? 'bg-blue-500' : 'bg-blue-200'"
                                            :style="'height: ' + (val / Math.max(...(drawer.staff?.salesTrend || [1])) * 100) + '%'"
                                        ></div>
                                        <span class="text-[10px] text-slate-400" x-text="['Mon','Tue','Wed','Thu','Fri','Sat','Sun'][idx]"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Contact Info --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Contact Information</h3>
                        </div>
                        <div class="card-body space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Email</span>
                                <span class="text-slate-700 font-medium" x-text="drawer.staff?.email"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Phone</span>
                                <span class="text-slate-700 font-medium" x-text="drawer.staff?.phone"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Joined</span>
                                <span class="text-slate-700 font-medium" x-text="drawer.staff?.joined"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ====== TAB: ACTIVITY ====== --}}
                <div x-show="drawer.tab === 'Activity'" x-cloak>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Recent Activity</h3>
                            <span class="badge badge-gray" x-text="drawer.activities.length + ' events'"></span>
                        </div>
                        <div class="table-wrapper">
                            <table class="table" id="staff-activity-table">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Order ID</th>
                                        <th>Amount</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="act in drawer.activities" :key="act.id">
                                        <tr :class="act.action === 'Refund' ? 'bg-red-50/50' : ''">
                                            <td>
                                                <span class="badge"
                                                    :class="{
                                                        'badge-success': act.action === 'Sale',
                                                        'badge-danger':  act.action === 'Refund',
                                                        'badge-warning': act.action === 'Discount',
                                                    }"
                                                    x-text="act.action"
                                                ></span>
                                            </td>
                                            <td><span class="font-mono text-xs text-slate-500" x-text="'#' + act.orderId"></span></td>
                                            <td class="font-medium" :class="act.action === 'Refund' ? 'text-red-600' : 'text-slate-700'" x-text="(act.action === 'Refund' ? '- ' : '') + '$' + act.amount.toFixed(2)"></td>
                                            <td class="text-xs text-slate-400" x-text="act.time"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ====== TAB: SHIFTS ====== --}}
                <div x-show="drawer.tab === 'Shifts'" x-cloak>

                    {{-- Clock In/Out --}}
                    <div class="card mb-5">
                        <div class="card-body">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Current Shift</p>
                                    <div x-show="drawer.staff?.status === 'active'" class="mt-2">
                                        <p class="text-2xl font-bold text-slate-800" x-text="drawer.shiftDuration"></p>
                                        <p class="text-xs text-slate-400 mt-0.5">Started at <span class="font-medium text-slate-600" x-text="drawer.shiftStart"></span></p>
                                    </div>
                                    <div x-show="drawer.staff?.status !== 'active'" class="mt-2" x-cloak>
                                        <p class="text-sm text-slate-500">Not clocked in</p>
                                    </div>
                                </div>
                                <button
                                    @click="toggleClock()"
                                    class="btn"
                                    :class="drawer.staff?.status === 'active' ? 'btn-danger' : 'btn-primary'"
                                    id="clock-toggle-btn"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span x-text="drawer.staff?.status === 'active' ? 'Clock Out' : 'Clock In'"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Shift History --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Shift History</h3>
                        </div>
                        <div class="table-wrapper">
                            <table class="table" id="shift-history-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Clock In</th>
                                        <th>Clock Out</th>
                                        <th>Duration</th>
                                        <th>Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="sh in drawer.shifts" :key="sh.id">
                                        <tr>
                                            <td class="font-medium text-slate-700" x-text="sh.date"></td>
                                            <td class="text-slate-500" x-text="sh.clockIn"></td>
                                            <td class="text-slate-500" x-text="sh.clockOut || '—'"></td>
                                            <td><span class="badge badge-gray" x-text="sh.duration"></span></td>
                                            <td class="font-semibold text-slate-800" x-text="'$' + sh.sales.toLocaleString()"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- ===== ADD / EDIT STAFF MODAL ===== --}}
    {{-- ================================================================ --}}
    <div x-show="modal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/30" @click="modal.open = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            id="staff-modal"
        >
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <h2 class="text-base font-semibold text-slate-800" x-text="modal.editing ? 'Edit Staff Member' : 'Add Staff Member'"></h2>
                <button @click="modal.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form @submit.prevent="saveStaff()" class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label" for="staff-name-input">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" id="staff-name-input" x-model="modal.form.name" class="form-input" placeholder="Jane Smith" required>
                    </div>
                    <div>
                        <label class="form-label" for="staff-email-input">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="staff-email-input" x-model="modal.form.email" class="form-input" placeholder="jane@acme.com" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label" for="staff-phone-input">Phone</label>
                        <input type="tel" id="staff-phone-input" x-model="modal.form.phone" class="form-input" placeholder="+1 (555) 000-0000">
                    </div>
                    <div>
                        <label class="form-label" for="staff-role-input">Role <span class="text-red-500">*</span></label>
                        <select id="staff-role-input" x-model="modal.form.role" class="form-input form-select" required>
                            <option value="">Select role…</option>
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                            <option value="cashier">Cashier</option>
                        </select>
                    </div>
                </div>
                <div x-show="!modal.editing">
                    <label class="form-label" for="staff-password-input">Temporary Password</label>
                    <input type="password" id="staff-password-input" x-model="modal.form.password" class="form-input" placeholder="••••••••">
                    <p class="text-xs text-slate-400 mt-1">User will be asked to change this on first login.</p>
                </div>

                {{-- Role-based Permissions --}}
                <div class="border border-slate-200 rounded-xl p-4">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Permissions</p>
                    <div class="grid grid-cols-2 gap-2">
                        <template x-for="perm in allPermissions" :key="perm.key">
                            <label class="flex items-center gap-2.5 py-1 cursor-pointer group">
                                <input
                                    type="checkbox"
                                    :checked="modal.form.permissions?.includes(perm.key)"
                                    @change="togglePerm(perm.key)"
                                    class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500/20"
                                >
                                <span class="text-sm text-slate-600 group-hover:text-slate-800" x-text="perm.label"></span>
                            </label>
                        </template>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="modal.open = false" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="save-staff-btn" x-text="modal.editing ? 'Save Changes' : 'Add Staff'"></button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- ===== DISABLE CONFIRMATION DIALOG ===== --}}
    {{-- ================================================================ --}}
    <div x-show="disableModal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/30" @click="disableModal.open = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-sm"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            id="disable-staff-modal"
        >
            <div class="p-6">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                </div>
                <h3 class="text-sm font-semibold text-slate-800 text-center">Disable Staff Account?</h3>
                <p class="text-sm text-slate-500 text-center mt-1.5" x-text="'This will prevent ' + (disableModal.staff?.name || '') + ' from accessing the POS system. This action can be reversed.'"></p>
                <div class="flex gap-3 mt-5">
                    <button @click="disableModal.open = false" class="flex-1 btn btn-secondary">Cancel</button>
                    <button @click="disableStaff()" class="flex-1 btn btn-danger" id="confirm-disable-btn">Disable</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function staffPage() {
    const colors = [
        'bg-blue-100 text-blue-600', 'bg-violet-100 text-violet-600', 'bg-emerald-100 text-emerald-600',
        'bg-rose-100 text-rose-600', 'bg-amber-100 text-amber-600', 'bg-cyan-100 text-cyan-600',
    ];

    return {
        search: '',
        filterRole: '',
        filterStatus: '',
        sortKey: 'todaySales',
        sortDir: -1,
        loading: true,
        staff: [],
        filtered: [],
        modal: { open: false, editing: false, form: {} },
        disableModal: { open: false, staff: null },
        drawer: {
            open: false,
            tab: 'Overview',
            staff: null,
            activities: [],
            shifts: [],
            shiftDuration: '4h 32m',
            shiftStart: '08:30 AM',
        },
        allPermissions: [
            { key: 'pos',        label: 'POS Screen' },
            { key: 'products',   label: 'Manage Products' },
            { key: 'orders',     label: 'View Orders' },
            { key: 'refund',     label: 'Issue Refunds' },
            { key: 'discount',   label: 'Apply Discounts' },
            { key: 'customers',  label: 'Manage Customers' },
            { key: 'reports',    label: 'View Reports' },
            { key: 'settings',   label: 'System Settings' },
        ],

        init() {
            // Simulate loading state
            setTimeout(() => {
                this.staff = [
                    { id: 1, name: 'John Doe',       email: 'john@acme.com',    phone: '+1 (555) 111-2222', role: 'admin',   status: 'active',  todaySales: 2480, todayOrders: 32, weeklySales: 14200, joined: 'Jan 15, 2024', avatarColor: colors[0], permissions: ['pos','products','orders','refund','discount','customers','reports','settings'], salesTrend: [1800, 2100, 1950, 2400, 2200, 2800, 2480] },
                    { id: 2, name: 'Jane Smith',     email: 'jane@acme.com',    phone: '+1 (555) 222-3333', role: 'manager', status: 'active',  todaySales: 1890, todayOrders: 24, weeklySales: 11800, joined: 'Mar 02, 2024', avatarColor: colors[1], permissions: ['pos','products','orders','refund','discount','customers'], salesTrend: [1400, 1700, 1500, 1900, 1650, 2100, 1890] },
                    { id: 3, name: 'Mark Wilson',    email: 'mark@acme.com',    phone: '+1 (555) 333-4444', role: 'cashier', status: 'active',  todaySales: 1240, todayOrders: 18, weeklySales: 7600,  joined: 'Jun 10, 2024', avatarColor: colors[2], permissions: ['pos','orders','discount'], salesTrend: [900, 1100, 1050, 1300, 1200, 1500, 1240] },
                    { id: 4, name: 'Emily Chen',     email: 'emily@acme.com',   phone: '+1 (555) 444-5555', role: 'cashier', status: 'offline', todaySales: 0,    todayOrders: 0,  weeklySales: 5200,  joined: 'Aug 22, 2024', avatarColor: colors[3], permissions: ['pos','orders'], salesTrend: [800, 900, 750, 1100, 950, 700, 0] },
                    { id: 5, name: 'Alex Rivera',    email: 'alex@acme.com',    phone: '+1 (555) 555-6666', role: 'cashier', status: 'active',  todaySales: 960,  todayOrders: 14, weeklySales: 6100,  joined: 'Oct 05, 2024', avatarColor: colors[4], permissions: ['pos','orders','discount'], salesTrend: [700, 850, 920, 1000, 880, 1100, 960] },
                    { id: 6, name: 'Sarah Kim',      email: 'sarah@acme.com',   phone: '+1 (555) 666-7777', role: 'manager', status: 'offline', todaySales: 0,    todayOrders: 0,  weeklySales: 9400,  joined: 'Apr 18, 2024', avatarColor: colors[5], permissions: ['pos','products','orders','refund','discount','customers'], salesTrend: [1200, 1500, 1400, 1800, 1600, 1900, 0] },
                ];
                this.loading = false;
                this.filter();
            }, 600);
        },

        filter() {
            let list = [...this.staff];
            if (this.filterRole)   list = list.filter(s => s.role === this.filterRole);
            if (this.filterStatus) list = list.filter(s => s.status === this.filterStatus);
            if (this.search.trim()) {
                const q = this.search.toLowerCase();
                list = list.filter(s => s.name.toLowerCase().includes(q) || s.email.toLowerCase().includes(q));
            }
            list.sort((a, b) => {
                if (a[this.sortKey] < b[this.sortKey]) return this.sortDir;
                if (a[this.sortKey] > b[this.sortKey]) return -this.sortDir;
                return 0;
            });
            this.filtered = list;
        },

        sortBy(key) {
            if (this.sortKey === key) this.sortDir *= -1;
            else { this.sortKey = key; this.sortDir = -1; }
            this.filter();
        },

        openDrawer(staffMember) {
            this.drawer.staff = staffMember;
            this.drawer.tab = 'Overview';
            this.drawer.shiftDuration = staffMember.status === 'active' ? this.randomShiftDuration() : '—';
            this.drawer.shiftStart = staffMember.status === 'active' ? '08:30 AM' : '—';
            this.drawer.activities = [
                { id: 1, action: 'Sale',     orderId: 1042, amount: 128.50, time: '10:02 AM' },
                { id: 2, action: 'Sale',     orderId: 1041, amount: 49.99,  time: '09:48 AM' },
                { id: 3, action: 'Discount', orderId: 1040, amount: 15.00,  time: '09:30 AM' },
                { id: 4, action: 'Sale',     orderId: 1039, amount: 84.20,  time: '09:15 AM' },
                { id: 5, action: 'Refund',   orderId: 1035, amount: 39.99,  time: '08:52 AM' },
                { id: 6, action: 'Sale',     orderId: 1034, amount: 205.60, time: '08:40 AM' },
                { id: 7, action: 'Discount', orderId: 1032, amount: 12.00,  time: '08:25 AM' },
                { id: 8, action: 'Sale',     orderId: 1030, amount: 67.00,  time: '08:12 AM' },
            ];
            this.drawer.shifts = [
                { id: 1, date: 'Today',        clockIn: '08:30 AM', clockOut: null,      duration: 'In progress', sales: staffMember.todaySales },
                { id: 2, date: 'Apr 20, 2025', clockIn: '09:00 AM', clockOut: '05:15 PM', duration: '8h 15m',     sales: 1950 },
                { id: 3, date: 'Apr 19, 2025', clockIn: '08:45 AM', clockOut: '05:00 PM', duration: '8h 15m',     sales: 2200 },
                { id: 4, date: 'Apr 18, 2025', clockIn: '09:15 AM', clockOut: '06:00 PM', duration: '8h 45m',     sales: 1800 },
                { id: 5, date: 'Apr 17, 2025', clockIn: '08:30 AM', clockOut: '04:45 PM', duration: '8h 15m',     sales: 2400 },
            ];
            this.drawer.open = true;
        },

        randomShiftDuration() {
            const h = Math.floor(Math.random() * 5) + 3;
            const m = Math.floor(Math.random() * 60);
            return h + 'h ' + m + 'm';
        },

        toggleClock() {
            if (this.drawer.staff) {
                const s = this.staff.find(x => x.id === this.drawer.staff.id);
                if (s) {
                    s.status = s.status === 'active' ? 'offline' : 'active';
                    this.drawer.staff = { ...s };
                    this.filter();
                    const msg = s.status === 'active' ? s.name + ' clocked in.' : s.name + ' clocked out.';
                    window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: msg, type: 'success' } }));
                }
            }
        },

        openAddModal() {
            this.modal.editing = false;
            this.modal.form = {
                id: null, name: '', email: '', phone: '', role: '', password: '',
                permissions: ['pos'],
                avatarColor: colors[Math.floor(Math.random() * colors.length)],
            };
            this.modal.open = true;
        },

        openEditModal(staffMember) {
            this.modal.editing = true;
            this.modal.form = { ...staffMember, permissions: [...staffMember.permissions] };
            this.modal.open = true;
        },

        togglePerm(key) {
            if (!this.modal.form.permissions) this.modal.form.permissions = [];
            const idx = this.modal.form.permissions.indexOf(key);
            if (idx > -1) this.modal.form.permissions.splice(idx, 1);
            else this.modal.form.permissions.push(key);
        },

        saveStaff() {
            if (this.modal.editing) {
                const idx = this.staff.findIndex(s => s.id === this.modal.form.id);
                if (idx > -1) this.staff[idx] = { ...this.staff[idx], ...this.modal.form };
                window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'Staff member updated.', type: 'success' } }));
            } else {
                this.staff.push({
                    ...this.modal.form,
                    id: Date.now(),
                    status: 'offline',
                    todaySales: 0,
                    todayOrders: 0,
                    weeklySales: 0,
                    joined: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
                    salesTrend: [0,0,0,0,0,0,0],
                });
                window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'Staff member added.', type: 'success' } }));
            }
            this.modal.open = false;
            this.filter();
        },

        confirmDisable(staffMember) {
            this.disableModal.staff = staffMember;
            this.disableModal.open = true;
        },

        disableStaff() {
            this.staff = this.staff.filter(s => s.id !== this.disableModal.staff.id);
            this.disableModal.open = false;
            this.filter();
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'Staff account disabled.', type: 'info' } }));
        },
    };
}
</script>

</x-layouts.admin>
