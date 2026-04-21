<x-layouts.admin pageTitle="Staff Management">

{{-- ============================================================ --}}
{{-- Keyboard shortcut listener (outside Alpine scope, global)    --}}
{{-- ============================================================ --}}
<div class="p-6 space-y-5" x-data="staffPage()" x-init="init()"
    @keydown.window="handleKey($event)"
>

    {{-- ===== PERFORMANCE INSIGHT BANNERS ===== --}}
    <div class="space-y-2" x-show="!loading" x-cloak>
        <template x-if="topPerformer">
            <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200/70 rounded-xl text-sm">
                <span class="flex-shrink-0 w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.664 1.319a.75.75 0 01.672 0 41.059 41.059 0 018.198 5.424.75.75 0 01-.254 1.285 31.372 31.372 0 00-7.86 3.83.75.75 0 01-.84 0 31.508 31.508 0 00-2.08-1.287V9.95l-.004.002.04.104a8.254 8.254 0 00-.818 3.572c0 .963.156 1.89.44 2.757a.75.75 0 01-.619 1.014 1.5 1.5 0 01-.53-.082A14.25 14.25 0 011.5 12.59a.75.75 0 01.132-.784l.78-.83A41.042 41.042 0 019.664 1.319z" clip-rule="evenodd"/></svg>
                </span>
                <p class="text-emerald-800 font-medium">
                    <span class="font-bold" x-text="topPerformer?.name"></span>
                    is the <span class="font-bold" x-text="rangeLabel.toLowerCase()"></span> top performer with
                    <span class="font-bold" x-text="'$' + getStat(topPerformer, 'sales').toLocaleString()"></span> in sales.
                </p>
                <button @click="openDrawer(topPerformer)" class="ml-auto text-xs font-semibold text-emerald-700 hover:text-emerald-900 underline underline-offset-2 flex-shrink-0">View Profile</button>
            </div>
        </template>
        <template x-if="lowPerformer">
            <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200/70 rounded-xl text-sm">
                <span class="flex-shrink-0 w-7 h-7 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                </span>
                <p class="text-amber-800 font-medium">
                    <span class="font-bold" x-text="lowPerformer?.name"></span>
                    has low activity — only <span class="font-bold" x-text="getStat(lowPerformer,'orders') + ' orders'"></span>. Consider checking in.
                </p>
                <button @click="openDrawer(lowPerformer)" class="ml-auto text-xs font-semibold text-amber-700 hover:text-amber-900 underline underline-offset-2 flex-shrink-0">View Profile</button>
            </div>
        </template>
        <template x-if="highRefundStaff">
            <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-rose-50 to-red-50 border border-rose-200/70 rounded-xl text-sm">
                <span class="flex-shrink-0 w-7 h-7 rounded-full bg-rose-100 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                </span>
                <p class="text-rose-800 font-medium">
                    <span class="font-bold" x-text="highRefundStaff?.name"></span>
                    has a high refund rate of <span class="font-bold" x-text="highRefundStaff?.refundRate + '%'"></span>. Review recent transactions.
                </p>
                <button @click="openDrawer(highRefundStaff)" class="ml-auto text-xs font-semibold text-rose-700 hover:text-rose-900 underline underline-offset-2 flex-shrink-0">Investigate</button>
            </div>
        </template>
    </div>

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Staff Management</h2>
            <p class="text-sm text-slate-500 mt-0.5">Monitor performance, manage roles, and track activity</p>
        </div>
        <button @click="openAddModal()" class="btn btn-primary flex-shrink-0" id="add-staff-btn">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
            Add Staff
        </button>
    </div>

    {{-- ============================================================ --}}
    {{-- ===== SALES FILTER SYSTEM ===== --}}
    {{-- ============================================================ --}}
    <div class="bg-white border border-slate-200 rounded-xl p-4 space-y-3">

        {{-- Segmented Control + Label row --}}
        <div class="flex flex-wrap items-center justify-between gap-3">

            {{-- Segmented Range Control --}}
            <div class="inline-flex items-center bg-slate-100 rounded-lg p-1 gap-1" role="group" aria-label="Date range selector" id="range-segmented-control">
                <template x-for="opt in rangeOptions" :key="opt.key">
                    <button
                        @click="setRange(opt.key)"
                        :id="'range-btn-' + opt.key"
                        :disabled="filterLoading"
                        class="relative px-3.5 py-1.5 rounded-md text-sm font-medium transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500/30 disabled:opacity-50 disabled:cursor-not-allowed"
                        :class="range === opt.key
                            ? 'bg-white text-blue-700 shadow-sm border border-slate-200/80'
                            : 'text-slate-500 hover:text-slate-700'"
                        :aria-pressed="range === opt.key"
                    >
                        <span x-text="opt.label"></span>
                        {{-- Keyboard hint badge --}}
                        <template x-if="opt.key !== 'today' && opt.key !== 'custom' && opt.shortcut">
                            <span class="ml-1 text-[10px] font-normal opacity-40 hidden sm:inline"
                                x-text="'(' + opt.shortcut + ')'">
                            </span>
                        </template>
                    </button>
                </template>
            </div>

            {{-- Current filter label --}}
            <div class="flex items-center gap-2" x-show="!filterLoading">
                <div class="flex items-center gap-1.5 text-xs text-slate-500 bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.5a2.25 2.25 0 012.25-2.25h13.5a2.25 2.25 0 012.25 2.25v11.25m-18 0A2.25 2.25 0 005.25 18h13.5A2.25 2.25 0 0021 15.75m-18 0v-7.5A2.25 2.25 0 015.25 6h13.5A2.25 2.25 0 0121 8.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H18v-.008zm0 2.25h.008v.008H18V15z"/></svg>
                    <span>Showing: <strong class="text-slate-700" x-text="rangeLabel"></strong></span>
                </div>
            </div>

            {{-- Filter loading indicator --}}
            <div x-show="filterLoading" class="flex items-center gap-2 text-xs text-blue-600" x-cloak>
                <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                <span>Updating data…</span>
            </div>
        </div>

        {{-- Custom Date Range Picker (conditional) --}}
        <div x-show="range === 'custom'"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="flex flex-wrap items-end gap-3 pt-3 border-t border-slate-100"
            x-cloak
        >
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1" for="custom-start-date">From</label>
                <input type="date" id="custom-start-date" x-model="customStart"
                    class="form-input text-sm py-1.5"
                    :max="customEnd || ''"
                >
            </div>
            <div class="text-slate-300 font-light pb-2">→</div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1" for="custom-end-date">To</label>
                <input type="date" id="custom-end-date" x-model="customEnd"
                    class="form-input text-sm py-1.5"
                    :min="customStart || ''"
                >
            </div>
            <button
                @click="applyCustomRange()"
                :disabled="!customStart || !customEnd || filterLoading"
                class="btn btn-primary py-1.5 text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                id="apply-custom-range-btn"
            >
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Apply Range
            </button>
            <p class="text-xs text-slate-400 pb-2" x-show="customStart && customEnd"
                x-text="customStart && customEnd ? formatDate(customStart) + ' → ' + formatDate(customEnd) : ''">
            </p>
        </div>
    </div>
    {{-- END FILTER SYSTEM --}}

    {{-- ===== METRIC CARDS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Total Staff --}}
        <div class="stat-card transition-opacity duration-200" :class="filterLoading ? 'opacity-50' : 'opacity-100'">
            <div class="flex items-start justify-between">
                <p class="stat-label">Total Staff</p>
                <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
            </div>
            <p class="stat-value" x-text="staff.length"></p>
            <div class="stat-delta stat-delta-up">
                <span x-text="staff.filter(s => s.status === 'active').length + ' currently active'"></span>
            </div>
        </div>

        {{-- Total Sales (range-aware) --}}
        <div class="stat-card transition-opacity duration-200" :class="filterLoading ? 'opacity-50' : 'opacity-100'">
            <div class="flex items-start justify-between">
                <p class="stat-label" x-text="rangeLabel + ' Team Sales'"></p>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="stat-value" x-text="'$' + staff.reduce((s, m) => s + getStat(m, 'sales'), 0).toLocaleString()"></p>
            <div class="stat-delta stat-delta-up">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18"/></svg>
                <span x-text="rangeDelta.sales"></span>
            </div>
        </div>

        {{-- Avg Refund Rate (range-aware) --}}
        <div class="stat-card transition-opacity duration-200" :class="filterLoading ? 'opacity-50' : 'opacity-100'">
            <div class="flex items-start justify-between">
                <p class="stat-label">Avg Refund Rate</p>
                <div class="w-8 h-8 rounded-xl bg-rose-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                </div>
            </div>
            <p class="stat-value" x-text="(staff.reduce((s,m) => s + m.refundRate, 0) / Math.max(staff.length, 1)).toFixed(1) + '%'"></p>
            <div class="stat-delta stat-delta-down">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3"/></svg>
                <span x-text="rangeDelta.refund"></span>
            </div>
        </div>

        {{-- Avg Processing Time --}}
        <div class="stat-card transition-opacity duration-200" :class="filterLoading ? 'opacity-50' : 'opacity-100'">
            <div class="flex items-start justify-between">
                <p class="stat-label">Avg Processing Time</p>
                <div class="w-8 h-8 rounded-xl bg-violet-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="stat-value" x-text="(staff.reduce((s,m) => s + m.avgProcessTime, 0) / Math.max(staff.length, 1)).toFixed(0) + 's'"></p>
            <div class="stat-delta stat-delta-up">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18"/></svg>
                <span x-text="rangeDelta.time"></span>
            </div>
        </div>
    </div>

    {{-- ===== STAFF FILTERS (search / role / status / chips) ===== --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-[200px] max-w-xs">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            <input type="search" x-model="search" @input="filterStaff()" placeholder="Search by name or email…" class="form-input pl-9" id="staff-search" :disabled="filterLoading">
        </div>
        <select x-model="filterRole" @change="filterStaff()" class="form-input form-select w-36" id="staff-role-filter" :disabled="filterLoading">
            <option value="">All Roles</option>
            <option value="admin">Admin</option>
            <option value="manager">Manager</option>
            <option value="cashier">Cashier</option>
        </select>
        <select x-model="filterStatus" @change="filterStaff()" class="form-input form-select w-36" id="staff-status-filter" :disabled="filterLoading">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="offline">Offline</option>
        </select>

        {{-- Quick Filter Chips --}}
        <div class="flex items-center gap-2 flex-wrap">
            <button @click="toggleQuickFilter('topPerformers')" :disabled="filterLoading"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium border transition-all duration-150 disabled:opacity-50"
                :class="quickFilters.topPerformers ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-white text-slate-600 border-slate-200 hover:border-emerald-400 hover:text-emerald-700'">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.664 1.319a.75.75 0 01.672 0 41.059 41.059 0 018.198 5.424.75.75 0 01-.254 1.285 31.372 31.372 0 00-7.86 3.83.75.75 0 01-.84 0 31.508 31.508 0 00-2.08-1.287V9.95l-.004.002.04.104a8.254 8.254 0 00-.818 3.572c0 .963.156 1.89.44 2.757a.75.75 0 01-.619 1.014 1.5 1.5 0 01-.53-.082A14.25 14.25 0 011.5 12.59a.75.75 0 01.132-.784l.78-.83A41.042 41.042 0 019.664 1.319z" clip-rule="evenodd"/></svg>
                Top Performers
            </button>
            <button @click="toggleQuickFilter('inactive')" :disabled="filterLoading"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium border transition-all duration-150 disabled:opacity-50"
                :class="quickFilters.inactive ? 'bg-slate-700 text-white border-slate-700 shadow-sm' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-400'">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Inactive Staff
            </button>
            <button @click="toggleQuickFilter('highRefund')" :disabled="filterLoading"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium border transition-all duration-150 disabled:opacity-50"
                :class="quickFilters.highRefund ? 'bg-rose-600 text-white border-rose-600 shadow-sm' : 'bg-white text-slate-600 border-slate-200 hover:border-rose-400 hover:text-rose-700'">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                High Refund Risk
            </button>
        </div>

        <span class="text-xs text-slate-400 ml-auto" x-text="filtered.length + ' staff members'"></span>
    </div>

    {{-- ===== STAFF TABLE ===== --}}
    <div class="card overflow-hidden transition-opacity duration-300" :class="filterLoading ? 'opacity-60 pointer-events-none' : 'opacity-100'">
        <div class="table-wrapper">
            <table class="table" id="staff-table">
                <thead>
                    <tr>
                        <th>Staff Member</th>
                        <th>Role</th>
                        <th>Shift Status</th>
                        {{-- Dynamic column header --}}
                        <th>
                            <button @click="sortBy('rangedSales')" class="flex items-center gap-1 hover:text-slate-700 transition-colors">
                                <span x-text="rangeLabel + ' Sales'"></span>
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"/></svg>
                            </button>
                        </th>
                        <th>
                            <button @click="sortBy('rangedOrders')" class="flex items-center gap-1 hover:text-slate-700 transition-colors">
                                Orders <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"/></svg>
                            </button>
                        </th>
                        <th>Avg Order</th>
                        <th>Refund Rate</th>
                        <th>Discount Use</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loading Skeleton (initial + filter reload) --}}
                    <template x-if="loading || filterLoading">
                        <template x-for="i in 5" :key="'skel-'+i">
                            <tr>
                                <td><div class="flex items-center gap-3"><div class="skeleton w-9 h-9 rounded-full"></div><div><div class="skeleton h-3.5 w-28 mb-1.5"></div><div class="skeleton h-3 w-20"></div></div></div></td>
                                <td><div class="skeleton h-5 w-16 rounded-full"></div></td>
                                <td><div class="skeleton h-5 w-24 rounded-full"></div></td>
                                <td><div class="skeleton h-4 w-16"></div></td>
                                <td><div class="skeleton h-4 w-8"></div></td>
                                <td><div class="skeleton h-4 w-12"></div></td>
                                <td><div class="skeleton h-4 w-12"></div></td>
                                <td><div class="skeleton h-4 w-10"></div></td>
                                <td><div class="skeleton h-4 w-6"></div></td>
                            </tr>
                        </template>
                    </template>

                    {{-- Empty State --}}
                    <tr x-show="!loading && !filterLoading && filtered.length === 0" x-cloak>
                        <td colspan="9">
                            <div class="flex flex-col items-center justify-center py-16 text-center">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-600">No sales data for this period</p>
                                <p class="text-xs text-slate-400 mt-1" x-text="search || filterRole || filterStatus ? 'Try adjusting your search or filters' : 'No staff data matches the selected date range'"></p>
                                <button @click="clearFilters()" class="btn btn-secondary btn-sm mt-4">Clear All Filters</button>
                            </div>
                        </td>
                    </tr>

                    {{-- Staff Rows --}}
                    <template x-for="s in filtered" :key="s.id">
                        <tr x-show="!loading && !filterLoading"
                            @click="openDrawer(s)"
                            class="cursor-pointer hover:bg-slate-50/80 transition-colors duration-100 group"
                            :class="{ 'bg-emerald-50/30': isTopPerformer(s), 'bg-rose-50/20': s.refundRate > 8 }"
                            :id="'staff-row-' + s.id"
                        >
                            {{-- Staff Member --}}
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="relative flex-shrink-0">
                                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold ring-2 ring-white shadow-sm"
                                            :class="s.avatarColor"
                                            x-text="s.name.split(' ').map(n => n[0]).join('')"
                                        ></div>
                                        <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white"
                                            :class="s.status === 'active' ? 'bg-green-500' : 'bg-slate-300'"
                                        ></div>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <p class="font-semibold text-slate-800 text-sm" x-text="s.name"></p>
                                            <template x-if="isTopPerformer(s)">
                                                <span class="text-amber-500" title="Top Performer">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"/></svg>
                                                </span>
                                            </template>
                                        </div>
                                        <p class="text-xs text-slate-400" x-text="s.email"></p>
                                    </div>
                                </div>
                            </td>

                            {{-- Role badge + tooltip --}}
                            <td @click.stop>
                                <div class="relative group/role inline-block">
                                    <span class="badge cursor-help"
                                        :class="{
                                            'bg-violet-100 text-violet-700 border border-violet-200': s.role === 'admin',
                                            'bg-blue-100 text-blue-700 border border-blue-200':   s.role === 'manager',
                                            'bg-slate-100 text-slate-600 border border-slate-200': s.role === 'cashier',
                                        }"
                                        x-text="s.role.charAt(0).toUpperCase() + s.role.slice(1)"
                                    ></span>
                                    <div class="absolute bottom-full left-0 mb-2 w-52 bg-slate-900 text-white rounded-lg shadow-xl p-3 z-30 text-xs opacity-0 group-hover/role:opacity-100 transition-opacity duration-150 pointer-events-none">
                                        <p class="font-semibold mb-2 text-slate-200">Permissions</p>
                                        <ul class="space-y-1">
                                            <template x-for="perm in allPermissions.filter(p => s.permissions?.includes(p.key))" :key="perm.key">
                                                <li class="flex items-center gap-1.5 text-slate-300">
                                                    <svg class="w-3 h-3 text-green-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                                    <span x-text="perm.label"></span>
                                                </li>
                                            </template>
                                        </ul>
                                        <div class="absolute top-full left-4 border-4 border-transparent border-t-slate-900"></div>
                                    </div>
                                </div>
                            </td>

                            {{-- Shift status --}}
                            <td>
                                <div x-show="s.status === 'active'">
                                    <div class="flex items-center gap-1.5">
                                        <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span></span>
                                        <span class="text-xs font-semibold text-green-700">Clocked In</span>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-0.5" x-text="s.shiftDuration + ' · since ' + s.shiftStart"></p>
                                </div>
                                <div x-show="s.status !== 'active'" x-cloak>
                                    <span class="text-xs font-medium text-slate-400">Offline</span>
                                    <p class="text-xs text-slate-400 mt-0.5" x-text="'Last: ' + s.lastLogin"></p>
                                </div>
                            </td>

                            {{-- Ranged Sales --}}
                            <td class="font-semibold text-slate-800" x-text="'$' + getStat(s, 'sales').toLocaleString()"></td>

                            {{-- Ranged Orders --}}
                            <td class="text-slate-600" x-text="getStat(s, 'orders')"></td>

                            {{-- Avg Order --}}
                            <td class="text-slate-600 text-sm" x-text="getStat(s,'orders') > 0 ? '$' + (getStat(s,'sales') / getStat(s,'orders')).toFixed(2) : '—'"></td>

                            {{-- Refund Rate bar --}}
                            <td>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-12 h-1.5 rounded-full bg-slate-100 overflow-hidden">
                                        <div class="h-full rounded-full transition-all"
                                            :class="s.refundRate > 8 ? 'bg-rose-500' : s.refundRate > 4 ? 'bg-amber-400' : 'bg-emerald-500'"
                                            :style="'width: ' + Math.min(s.refundRate * 10, 100) + '%'"
                                        ></div>
                                    </div>
                                    <span class="text-xs font-medium"
                                        :class="s.refundRate > 8 ? 'text-rose-600' : s.refundRate > 4 ? 'text-amber-600' : 'text-emerald-600'"
                                        x-text="s.refundRate + '%'"
                                    ></span>
                                </div>
                            </td>

                            {{-- Discount Usage --}}
                            <td>
                                <span class="text-xs font-medium"
                                    :class="s.discountUsage > 30 ? 'text-amber-600' : 'text-slate-500'"
                                    x-text="s.discountUsage + '%'"
                                ></span>
                            </td>

                            {{-- Action Dropdown --}}
                            <td @click.stop>
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="btn btn-ghost btn-icon btn-sm text-slate-400 hover:text-slate-700 opacity-0 group-hover:opacity-100 transition-opacity" :class="open ? 'opacity-100' : ''">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                                    </button>
                                    <div x-show="open" @click.outside="open = false"
                                        x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        class="absolute right-0 mt-1 w-48 bg-white border border-slate-200 rounded-xl shadow-lg z-20 py-1.5" x-cloak>
                                        <button @click="openDrawer(s); open = false" class="w-full flex items-center gap-2.5 px-3.5 py-2 text-sm text-slate-700 hover:bg-slate-50 text-left">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            View Details
                                        </button>
                                        <button @click="openEditModal(s); open = false" class="w-full flex items-center gap-2.5 px-3.5 py-2 text-sm text-slate-700 hover:bg-slate-50 text-left">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                                            Edit
                                        </button>
                                        <button @click="openActivityLog(s); open = false" class="w-full flex items-center gap-2.5 px-3.5 py-2 text-sm text-slate-700 hover:bg-slate-50 text-left">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
                                            Activity Log
                                        </button>
                                        <div class="border-t border-slate-100 my-1.5 mx-2"></div>
                                        <button @click="confirmResetPassword(s); open = false" class="w-full flex items-center gap-2.5 px-3.5 py-2 text-sm text-amber-600 hover:bg-amber-50 text-left">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                            Reset Password
                                        </button>
                                        <button @click="confirmDisable(s); open = false" class="w-full flex items-center gap-2.5 px-3.5 py-2 text-sm text-red-600 hover:bg-red-50 text-left">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
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
    {{-- ===== STAFF DETAIL DRAWER ===== --}}
    {{-- ================================================================ --}}
    <div x-show="drawer.open" class="fixed inset-0 z-50 flex" x-cloak>
        <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]" @click="drawer.open = false"></div>
        <div
            class="absolute right-0 top-0 bottom-0 w-full max-w-[540px] bg-white shadow-2xl flex flex-col"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            id="staff-detail-drawer"
        >
            {{-- Drawer Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-11 h-11 rounded-full flex items-center justify-center text-sm font-bold ring-2 ring-white shadow"
                            :class="drawer.staff?.avatarColor"
                            x-text="drawer.staff?.name?.split(' ').map(n => n[0]).join('')"
                        ></div>
                        <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full border-2 border-white"
                            :class="drawer.staff?.status === 'active' ? 'bg-green-500' : 'bg-slate-300'"
                        ></div>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base font-bold text-slate-800" x-text="drawer.staff?.name"></h2>
                            <template x-if="isTopPerformer(drawer.staff)">
                                <span class="text-amber-500" title="Top Performer">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"/></svg>
                                </span>
                            </template>
                        </div>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="badge badge-sm text-xs"
                                :class="{
                                    'bg-violet-100 text-violet-700 border border-violet-200': drawer.staff?.role === 'admin',
                                    'bg-blue-100 text-blue-700 border border-blue-200':   drawer.staff?.role === 'manager',
                                    'bg-slate-100 text-slate-600 border border-slate-200': drawer.staff?.role === 'cashier',
                                }"
                                x-text="drawer.staff?.role?.charAt(0).toUpperCase() + drawer.staff?.role?.slice(1)"
                            ></span>
                            <div class="flex items-center gap-1">
                                <div class="w-1.5 h-1.5 rounded-full" :class="drawer.staff?.status === 'active' ? 'bg-green-500' : 'bg-slate-300'"></div>
                                <span class="text-xs" :class="drawer.staff?.status === 'active' ? 'text-green-700 font-medium' : 'text-slate-400'"
                                    x-text="drawer.staff?.status === 'active' ? 'Clocked In · ' + drawer.shiftDuration : 'Offline · Last: ' + drawer.staff?.lastLogin"
                                ></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    {{-- Range badge in drawer --}}
                    <span class="hidden sm:inline-flex items-center gap-1 text-[11px] font-medium text-blue-700 bg-blue-50 border border-blue-100 rounded-md px-2 py-1">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.5a2.25 2.25 0 012.25-2.25h13.5a2.25 2.25 0 012.25 2.25v11.25m-18 0A2.25 2.25 0 005.25 18h13.5A2.25 2.25 0 0021 15.75m-18 0v-7.5A2.25 2.25 0 015.25 6h13.5A2.25 2.25 0 0121 8.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H18v-.008zm0 2.25h.008v.008H18V15z"/></svg>
                        <span x-text="rangeLabel"></span>
                    </span>
                    <button @click="openEditModal(drawer.staff)" class="btn btn-secondary btn-sm">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                        Edit
                    </button>
                    <button @click="drawer.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400 hover:text-slate-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Drawer Tabs --}}
            <div class="border-b border-slate-100 px-6">
                <nav class="flex gap-5" aria-label="Staff detail tabs">
                    <template x-for="tab in ['Overview', 'Transactions', 'Shifts', 'Activity']" :key="tab">
                        <button @click="drawer.tab = tab"
                            class="pb-3 text-sm font-medium border-b-2 transition-all duration-150 -mb-px"
                            :class="drawer.tab === tab ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                            x-text="tab"
                        ></button>
                    </template>
                </nav>
            </div>

            {{-- Drawer Content --}}
            <div class="flex-1 overflow-y-auto p-6 space-y-5 transition-opacity duration-200" :class="drawer.loading ? 'opacity-50' : 'opacity-100'">

                {{-- Drawer skeleton during range change --}}
                <template x-if="drawer.loading">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <template x-for="i in 4" :key="i"><div class="skeleton h-20 rounded-xl"></div></template>
                        </div>
                        <div class="skeleton h-32 rounded-xl"></div>
                        <div class="skeleton h-24 rounded-xl"></div>
                    </div>
                </template>

                {{-- ====== TAB: OVERVIEW ====== --}}
                <div x-show="drawer.tab === 'Overview' && !drawer.loading">

                    {{-- KPI Cards (range-aware) --}}
                    <div class="grid grid-cols-2 gap-3 mb-5">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 border border-blue-100 rounded-xl p-4">
                            <p class="text-[11px] font-semibold text-blue-500 uppercase tracking-wider" x-text="rangeLabel + ' Sales'"></p>
                            <p class="text-2xl font-bold text-blue-900 mt-1" x-text="'$' + getStat(drawer.staff, 'sales').toLocaleString()"></p>
                        </div>
                        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 border border-emerald-100 rounded-xl p-4">
                            <p class="text-[11px] font-semibold text-emerald-500 uppercase tracking-wider" x-text="rangeLabel + ' Orders'"></p>
                            <p class="text-2xl font-bold text-emerald-900 mt-1" x-text="getStat(drawer.staff, 'orders')"></p>
                        </div>
                        <div class="bg-gradient-to-br from-violet-50 to-violet-100/50 border border-violet-100 rounded-xl p-4">
                            <p class="text-[11px] font-semibold text-violet-500 uppercase tracking-wider">Avg Order Value</p>
                            <p class="text-2xl font-bold text-violet-900 mt-1"
                                x-text="getStat(drawer.staff,'orders') > 0 ? '$' + (getStat(drawer.staff,'sales') / getStat(drawer.staff,'orders')).toFixed(2) : '—'"
                            ></p>
                        </div>
                        <div class="bg-gradient-to-br from-rose-50 to-rose-100/50 border border-rose-100 rounded-xl p-4">
                            <p class="text-[11px] font-semibold text-rose-500 uppercase tracking-wider">Refund Rate</p>
                            <p class="text-2xl font-bold mt-1"
                                :class="(drawer.staff?.refundRate || 0) > 8 ? 'text-rose-700' : 'text-rose-900'"
                                x-text="(drawer.staff?.refundRate || 0) + '%'"
                            ></p>
                        </div>
                    </div>

                    {{-- Additional Metrics --}}
                    <div class="grid grid-cols-3 gap-3 mb-5">
                        <div class="stat-card py-3 px-4">
                            <p class="text-[11px] font-medium text-slate-400 uppercase">Discount Use</p>
                            <p class="text-lg font-bold text-slate-800 mt-0.5" x-text="(drawer.staff?.discountUsage || 0) + '%'"></p>
                        </div>
                        <div class="stat-card py-3 px-4">
                            <p class="text-[11px] font-medium text-slate-400 uppercase">Avg Time</p>
                            <p class="text-lg font-bold text-slate-800 mt-0.5" x-text="(drawer.staff?.avgProcessTime || 0) + 's'"></p>
                        </div>
                        <div class="stat-card py-3 px-4">
                            <p class="text-[11px] font-medium text-slate-400 uppercase">Weekly Sales</p>
                            <p class="text-lg font-bold text-slate-800 mt-0.5" x-text="'$' + (drawer.staff?.weeklySales || 0).toLocaleString()"></p>
                        </div>
                    </div>

                    {{-- Sales Trend Chart (days change label by range) --}}
                    <div class="card mb-5">
                        <div class="card-header">
                            <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider" x-text="'Sales Trend · ' + rangeLabel"></h3>
                        </div>
                        <div class="card-body">
                            <div class="flex items-end gap-1.5 h-24">
                                <template x-for="(val, idx) in getDrawerTrend()" :key="idx">
                                    <div class="flex-1 flex flex-col items-center gap-1 group/bar">
                                        <div class="w-full rounded-t transition-all duration-300 hover:opacity-80 relative"
                                            :class="idx === (getDrawerTrend().length - 1) ? 'bg-blue-500' : 'bg-blue-200'"
                                            :style="'height: ' + Math.max((val / Math.max(...getDrawerTrend().filter(v=>v>0), 1)) * 80, val > 0 ? 4 : 2) + 'px'"
                                        >
                                            <div class="absolute -top-7 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-1.5 py-0.5 rounded whitespace-nowrap opacity-0 group-hover/bar:opacity-100 transition-opacity pointer-events-none">
                                                $<span x-text="val.toLocaleString()"></span>
                                            </div>
                                        </div>
                                        <span class="text-[10px] text-slate-400" x-text="getTrendLabel(idx)"></span>
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
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Email</span>
                                <span class="text-slate-700 font-medium" x-text="drawer.staff?.email"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Phone</span>
                                <span class="text-slate-700 font-medium" x-text="drawer.staff?.phone"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Joined</span>
                                <span class="text-slate-700 font-medium" x-text="drawer.staff?.joined"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Last Login</span>
                                <span class="text-slate-700 font-medium" x-text="drawer.staff?.lastLogin"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ====== TAB: TRANSACTIONS ====== --}}
                <div x-show="drawer.tab === 'Transactions' && !drawer.loading" x-cloak>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Recent Transactions</h3>
                            <span class="badge badge-gray" x-text="drawer.activities.length + ' records'"></span>
                        </div>
                        <div class="divide-y divide-slate-50">
                            <template x-for="act in drawer.activities" :key="act.id">
                                <div class="flex items-center justify-between px-5 py-3 hover:bg-slate-50/80 transition-colors"
                                    :class="act.action === 'Refund' ? 'bg-rose-50/40' : ''">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                            :class="{ 'bg-emerald-100': act.action==='Sale', 'bg-rose-100': act.action==='Refund', 'bg-amber-100': act.action==='Discount' }">
                                            <svg x-show="act.action === 'Sale'" class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                                            <svg x-show="act.action === 'Refund'" class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                                            <svg x-show="act.action === 'Discount'" class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-700" x-text="act.action"></p>
                                            <p class="text-xs text-slate-400 font-mono" x-text="'#' + act.orderId + ' · ' + act.time"></p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-bold"
                                        :class="act.action === 'Refund' ? 'text-rose-600' : 'text-slate-800'"
                                        x-text="(act.action === 'Refund' ? '- ' : '') + '$' + act.amount.toFixed(2)"
                                    ></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- ====== TAB: SHIFTS ====== --}}
                <div x-show="drawer.tab === 'Shifts' && !drawer.loading" x-cloak>
                    <div class="rounded-xl border mb-5 overflow-hidden"
                        :class="drawer.staff?.status === 'active' ? 'border-green-200 bg-gradient-to-br from-green-50 to-emerald-50/50' : 'border-slate-200 bg-slate-50'">
                        <div class="p-5 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider" :class="drawer.staff?.status === 'active' ? 'text-green-600' : 'text-slate-400'">Current Shift Status</p>
                                <div x-show="drawer.staff?.status === 'active'" class="mt-2">
                                    <div class="flex items-center gap-2">
                                        <span class="relative flex h-2.5 w-2.5"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span></span>
                                        <p class="text-2xl font-black text-green-900" x-text="drawer.shiftDuration"></p>
                                    </div>
                                    <p class="text-xs text-green-700/80 mt-0.5">Clocked in since <span class="font-semibold" x-text="drawer.shiftStart"></span></p>
                                </div>
                                <div x-show="drawer.staff?.status !== 'active'" class="mt-2" x-cloak>
                                    <p class="text-xl font-bold text-slate-500">Not Clocked In</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Last active: <span class="font-medium" x-text="drawer.staff?.lastLogin"></span></p>
                                </div>
                            </div>
                            <button @click="toggleClock()" class="btn" :class="drawer.staff?.status === 'active' ? 'btn-danger' : 'btn-primary'" id="clock-toggle-btn">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span x-text="drawer.staff?.status === 'active' ? 'Clock Out' : 'Clock In'"></span>
                            </button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Shift History</h3>
                        </div>
                        <div class="table-wrapper">
                            <table class="table" id="shift-history-table">
                                <thead>
                                    <tr><th>Date</th><th>Clock In</th><th>Clock Out</th><th>Duration</th><th>Sales</th></tr>
                                </thead>
                                <tbody>
                                    <template x-for="sh in drawer.shifts" :key="sh.id">
                                        <tr>
                                            <td class="font-semibold text-slate-700" x-text="sh.date"></td>
                                            <td class="text-slate-500 font-mono text-xs" x-text="sh.clockIn"></td>
                                            <td class="text-slate-500 font-mono text-xs" x-text="sh.clockOut || '—'"></td>
                                            <td><span class="badge" :class="sh.clockOut ? 'badge-gray' : 'bg-green-100 text-green-700 border border-green-200'" x-text="sh.duration"></span></td>
                                            <td class="font-bold text-slate-800" x-text="'$' + sh.sales.toLocaleString()"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ====== TAB: ACTIVITY ====== --}}
                <div x-show="drawer.tab === 'Activity' && !drawer.loading" x-cloak>
                    <div class="space-y-1">
                        <template x-for="(log, idx) in drawer.activityLogs" :key="log.id">
                            <div class="flex gap-3 px-1">
                                <div class="flex flex-col items-center">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 shadow-sm"
                                        :class="{ 'bg-blue-100': log.type==='login', 'bg-emerald-100': log.type==='sale', 'bg-rose-100': log.type==='refund', 'bg-amber-100': log.type==='discount', 'bg-slate-100': log.type==='setting' }">
                                        <svg x-show="log.type === 'login'" class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                                        <svg x-show="log.type === 'sale'" class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        <svg x-show="log.type === 'refund'" class="w-3.5 h-3.5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                                        <svg x-show="log.type === 'discount'" class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                                        <svg x-show="log.type === 'setting'" class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <div x-show="idx < drawer.activityLogs.length - 1" class="w-px flex-1 bg-slate-100 my-1"></div>
                                </div>
                                <div class="pb-4 flex-1">
                                    <p class="text-sm text-slate-700" x-text="log.description"></p>
                                    <p class="text-xs text-slate-400 mt-0.5" x-text="log.time"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>{{-- /drawer content --}}
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- ===== ADD / EDIT STAFF MODAL ===== --}}
    {{-- ================================================================ --}}
    <div x-show="modal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]" @click="modal.open = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            id="staff-modal">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div>
                    <h2 class="text-base font-bold text-slate-800" x-text="modal.editing ? 'Edit Staff Member' : 'Add Staff Member'"></h2>
                    <p class="text-xs text-slate-400 mt-0.5" x-text="modal.editing ? 'Update profile and permissions' : 'Fill in the details to create a new account'"></p>
                </div>
                <button @click="modal.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
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
                <div class="border border-slate-100 rounded-xl p-4 bg-slate-50/50">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Permissions</p>
                    <div class="grid grid-cols-2 gap-2">
                        <template x-for="perm in allPermissions" :key="perm.key">
                            <label class="flex items-center gap-2.5 py-1.5 px-2 rounded-lg cursor-pointer hover:bg-white transition-colors group">
                                <input type="checkbox" :checked="modal.form.permissions?.includes(perm.key)" @change="togglePerm(perm.key)" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500/20">
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

    {{-- ===== DISABLE CONFIRMATION ===== --}}
    <div x-show="disableModal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]" @click="disableModal.open = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            id="disable-staff-modal">
            <div class="p-6">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 text-center">Disable Account?</h3>
                <p class="text-sm text-slate-500 text-center mt-2" x-text="'This will prevent ' + (disableModal.staff?.name || '') + ' from accessing the POS system. This can be reversed.'"></p>
                <div class="flex gap-3 mt-6">
                    <button @click="disableModal.open = false" class="flex-1 btn btn-secondary">Cancel</button>
                    <button @click="disableStaff()" class="flex-1 btn btn-danger" id="confirm-disable-btn">Disable Account</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== RESET PASSWORD CONFIRMATION ===== --}}
    <div x-show="resetModal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]" @click="resetModal.open = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            id="reset-password-modal">
            <div class="p-6">
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 text-center">Reset Password?</h3>
                <p class="text-sm text-slate-500 text-center mt-2" x-text="'A password reset link will be sent to ' + (resetModal.staff?.email || '') + '.'"></p>
                <div class="flex gap-3 mt-6">
                    <button @click="resetModal.open = false" class="flex-1 btn btn-secondary">Cancel</button>
                    <button @click="doResetPassword()" class="flex-1 btn bg-amber-500 hover:bg-amber-600 text-white" id="confirm-reset-btn">Send Reset Link</button>
                </div>
            </div>
        </div>
    </div>

</div>{{-- /x-data --}}

{{-- ================================================================ --}}
{{-- ===== ALPINE.JS COMPONENT ===== --}}
{{-- ================================================================ --}}
<script>
function staffPage() {

    /* ─── Avatar palette ───────────────────────────────────────── */
    const colors = [
        'bg-blue-100 text-blue-700', 'bg-violet-100 text-violet-700',
        'bg-emerald-100 text-emerald-700', 'bg-rose-100 text-rose-700',
        'bg-amber-100 text-amber-700',  'bg-cyan-100 text-cyan-700',
    ];

    /* ─── Range multiplier map (vs "today" baseline) ───────────── */
    const rangeMultipliers = {
        today: 1, week: 7, month: 30, year: 365, custom: 14,
    };

    /* ─── Trend label sets ─────────────────────────────────────── */
    const trendLabels = {
        today:  ['7am','8am','9am','10am','11am','12pm','Now'],
        week:   ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
        month:  ['W1','W2','W3','W4','','',''],
        year:   ['Jan','Apr','Jul','Oct','','',''],
        custom: ['D1','D2','D3','D4','D5','D6','D7'],
    };

    return {
        /* ── Filter state ────────────────────────────────────── */
        range:       localStorage.getItem('staffRange') || 'week',
        customStart: '',
        customEnd:   '',
        filterLoading: false,

        /* ── Staff filter state ──────────────────────────────── */
        search:       '',
        filterRole:   '',
        filterStatus: '',
        sortKey:      'rangedSales',
        sortDir:      -1,
        loading:      true,
        staff:        [],
        filtered:     [],
        quickFilters: { topPerformers: false, inactive: false, highRefund: false },

        /* ── Modals / Drawer ─────────────────────────────────── */
        modal:       { open: false, editing: false, form: {} },
        disableModal:{ open: false, staff: null },
        resetModal:  { open: false, staff: null },
        drawer: {
            open: false, tab: 'Overview', loading: false,
            staff: null, activities: [], shifts: [], activityLogs: [],
            shiftDuration: '—', shiftStart: '—',
        },

        /* ── Static config ───────────────────────────────────── */
        rangeOptions: [
            { key: 'today',  label: 'Today',  shortcut: null },
            { key: 'week',   label: 'Week',   shortcut: 'W' },
            { key: 'month',  label: 'Month',  shortcut: 'M' },
            { key: 'year',   label: 'Year',   shortcut: 'Y' },
            { key: 'custom', label: 'Custom', shortcut: null },
        ],
        allPermissions: [
            { key: 'pos',       label: 'POS Screen'       },
            { key: 'products',  label: 'Manage Products'  },
            { key: 'orders',    label: 'View Orders'      },
            { key: 'refund',    label: 'Issue Refunds'    },
            { key: 'discount',  label: 'Apply Discounts'  },
            { key: 'customers', label: 'Manage Customers' },
            { key: 'reports',   label: 'View Reports'     },
            { key: 'settings',  label: 'System Settings'  },
        ],

        /* ── Computed: range label ───────────────────────────── */
        get rangeLabel() {
            const map = { today:'Today', week:'This Week', month:'This Month', year:'This Year', custom:'Custom Range' };
            return map[this.range] || 'This Week';
        },

        /* ── Computed: metric delta strings ──────────────────── */
        get rangeDelta() {
            const d = {
                today:  { sales: '8% vs yesterday',   refund: '1.2% vs last day',  time: '3s faster today'  },
                week:   { sales: '14% vs last week',  refund: '2.1% vs last week', time: '5s faster this week' },
                month:  { sales: '9% vs last month',  refund: '0.8% vs last month',time: '8s faster this month' },
                year:   { sales: '21% vs last year',  refund: '3.4% vs last year', time: '12s faster this year' },
                custom: { sales: 'Custom range',      refund: 'Custom range',      time: 'Custom range' },
            };
            return d[this.range] || d.week;
        },

        /* ── Computed: top/low/high-refund performers ────────── */
        get topPerformer() {
            const active = this.staff.filter(s => s.status === 'active' && this.getStat(s,'sales') > 0);
            if (!active.length) return null;
            return active.reduce((a, b) => this.getStat(a,'sales') > this.getStat(b,'sales') ? a : b);
        },
        get lowPerformer() {
            const active = this.staff.filter(s => s.status === 'active');
            if (active.length < 2) return null;
            const low = active.reduce((a, b) => this.getStat(a,'sales') < this.getStat(b,'sales') ? a : b);
            return this.getStat(low,'orders') < 8 ? low : null;
        },
        get highRefundStaff() {
            return this.staff.find(s => s.refundRate > 8) || null;
        },

        /* ═══════════════════════════════════════════════════════
           CORE: getStat — range-aware value resolver
           ═══════════════════════════════════════════════════════ */
        getStat(s, type) {
            if (!s) return 0;
            const mult = rangeMultipliers[this.range] || 1;
            if (type === 'sales') {
                if (this.range === 'today')  return s.todaySales;
                if (this.range === 'week')   return s.weeklySales;
                if (this.range === 'month')  return Math.round(s.weeklySales * 4.3);
                if (this.range === 'year')   return Math.round(s.weeklySales * 52);
                if (this.range === 'custom') return Math.round(s.weeklySales * 2);
            }
            if (type === 'orders') {
                if (this.range === 'today')  return s.todayOrders;
                if (this.range === 'week')   return s.todayOrders * 7;
                if (this.range === 'month')  return s.todayOrders * 30;
                if (this.range === 'year')   return s.todayOrders * 365;
                if (this.range === 'custom') return s.todayOrders * 14;
            }
            return 0;
        },

        /* ─── Format date (YYYY-MM-DD → "Apr 21, 2025") ──────── */
        formatDate(d) {
            if (!d) return '';
            const dt = new Date(d + 'T00:00:00');
            return dt.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        },

        /* ─── Trend data for drawer chart ────────────────────── */
        getDrawerTrend() {
            if (!this.drawer.staff) return [0,0,0,0,0,0,0];
            const base = this.drawer.staff.salesTrend || [0,0,0,0,0,0,0];
            // For non-today ranges, scale each bar
            if (this.range === 'today')  return base;
            if (this.range === 'week')   return base;
            const mult = { month: 4.3, year: 52, custom: 2 }[this.range] || 1;
            return base.map(v => Math.round(v * mult));
        },

        /* ─── Trend axis labels ─────────────────────────────── */
        getTrendLabel(idx) {
            const labels = trendLabels[this.range] || trendLabels.week;
            return labels[idx] || '';
        },

        isTopPerformer(s) {
            if (!s) return false;
            const top = this.topPerformer;
            return top && top.id === s.id;
        },

        /* ═══════════════════════════════════════════════════════
           INIT
           ═══════════════════════════════════════════════════════ */
        init() {
            setTimeout(() => {
                this.staff = [
                    {
                        id: 1, name: 'John Doe', email: 'john@acme.com', phone: '+1 (555) 111-2222',
                        role: 'admin', status: 'active',
                        todaySales: 2480, todayOrders: 32, weeklySales: 14200,
                        joined: 'Jan 15, 2024', avatarColor: colors[0],
                        permissions: ['pos','products','orders','refund','discount','customers','reports','settings'],
                        salesTrend: [1800,2100,1950,2400,2200,2800,2480],
                        refundRate: 2.1, discountUsage: 15, avgProcessTime: 38,
                        shiftDuration: '4h 22m', shiftStart: '09:00 AM', lastLogin: 'Today, 9:00 AM',
                    },
                    {
                        id: 2, name: 'Jane Smith', email: 'jane@acme.com', phone: '+1 (555) 222-3333',
                        role: 'manager', status: 'active',
                        todaySales: 1890, todayOrders: 24, weeklySales: 11800,
                        joined: 'Mar 02, 2024', avatarColor: colors[1],
                        permissions: ['pos','products','orders','refund','discount','customers'],
                        salesTrend: [1400,1700,1500,1900,1650,2100,1890],
                        refundRate: 4.3, discountUsage: 28, avgProcessTime: 45,
                        shiftDuration: '3h 55m', shiftStart: '09:30 AM', lastLogin: 'Today, 9:30 AM',
                    },
                    {
                        id: 3, name: 'Mark Wilson', email: 'mark@acme.com', phone: '+1 (555) 333-4444',
                        role: 'cashier', status: 'active',
                        todaySales: 1240, todayOrders: 18, weeklySales: 7600,
                        joined: 'Jun 10, 2024', avatarColor: colors[2],
                        permissions: ['pos','orders','discount'],
                        salesTrend: [900,1100,1050,1300,1200,1500,1240],
                        refundRate: 9.5, discountUsage: 42, avgProcessTime: 52,
                        shiftDuration: '3h 10m', shiftStart: '10:15 AM', lastLogin: 'Today, 10:15 AM',
                    },
                    {
                        id: 4, name: 'Emily Chen', email: 'emily@acme.com', phone: '+1 (555) 444-5555',
                        role: 'cashier', status: 'offline',
                        todaySales: 0, todayOrders: 0, weeklySales: 5200,
                        joined: 'Aug 22, 2024', avatarColor: colors[3],
                        permissions: ['pos','orders'],
                        salesTrend: [800,900,750,1100,950,700,0],
                        refundRate: 1.8, discountUsage: 8, avgProcessTime: 41,
                        shiftDuration: '—', shiftStart: '—', lastLogin: 'Yesterday, 5:00 PM',
                    },
                    {
                        id: 5, name: 'Alex Rivera', email: 'alex@acme.com', phone: '+1 (555) 555-6666',
                        role: 'cashier', status: 'active',
                        todaySales: 960, todayOrders: 5, weeklySales: 6100,
                        joined: 'Oct 05, 2024', avatarColor: colors[4],
                        permissions: ['pos','orders','discount'],
                        salesTrend: [700,850,920,1000,880,1100,960],
                        refundRate: 3.2, discountUsage: 19, avgProcessTime: 47,
                        shiftDuration: '2h 05m', shiftStart: '11:20 AM', lastLogin: 'Today, 11:20 AM',
                    },
                    {
                        id: 6, name: 'Sarah Kim', email: 'sarah@acme.com', phone: '+1 (555) 666-7777',
                        role: 'manager', status: 'offline',
                        todaySales: 0, todayOrders: 0, weeklySales: 9400,
                        joined: 'Apr 18, 2024', avatarColor: colors[5],
                        permissions: ['pos','products','orders','refund','discount','customers'],
                        salesTrend: [1200,1500,1400,1800,1600,1900,0],
                        refundRate: 2.9, discountUsage: 22, avgProcessTime: 44,
                        shiftDuration: '—', shiftStart: '—', lastLogin: 'Today, 8:00 AM',
                    },
                ];
                this.loading = false;
                this.filterStaff();
            }, 700);
        },

        /* ═══════════════════════════════════════════════════════
           RANGE FILTER LOGIC
           ═══════════════════════════════════════════════════════ */
        setRange(key) {
            if (this.range === key && key !== 'custom') return;
            this.range = key;
            localStorage.setItem('staffRange', key);

            // Don't trigger load yet if custom — wait for Apply
            if (key !== 'custom') {
                this.simulateRangeLoad();
            }
        },

        applyCustomRange() {
            if (!this.customStart || !this.customEnd) return;
            this.simulateRangeLoad();
            const label = this.formatDate(this.customStart) + ' → ' + this.formatDate(this.customEnd);
            window.dispatchEvent(new CustomEvent('show-toast', {
                detail: { message: 'Showing data for ' + label, type: 'info', duration: 3500 }
            }));
        },

        simulateRangeLoad() {
            this.filterLoading = true;
            // Update drawer if open
            if (this.drawer.open) this.drawer.loading = true;
            setTimeout(() => {
                this.filterLoading = false;
                this.filterStaff();   // re-sort with new rangedSales values
                if (this.drawer.open) {
                    // Refresh drawer data to reflect new range
                    const fresh = this.staff.find(s => s.id === this.drawer.staff?.id);
                    if (fresh) this.drawer.staff = { ...fresh };
                    this.drawer.loading = false;
                }
            }, 480);
        },

        /* ─── Keyboard shortcuts ─────────────────────────────── */
        handleKey(e) {
            // Ignore when typing in inputs
            if (['INPUT','SELECT','TEXTAREA'].includes(e.target.tagName)) return;
            const map = { w: 'week', m: 'month', y: 'year', t: 'today' };
            const key = e.key.toLowerCase();
            if (map[key]) {
                this.setRange(map[key]);
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: { message: 'Switched to ' + (this.rangeLabel), type: 'info', duration: 2000 }
                }));
            }
        },

        /* ═══════════════════════════════════════════════════════
           STAFF TABLE FILTER / SORT
           ═══════════════════════════════════════════════════════ */
        filterStaff() {
            let list = [...this.staff];

            // Add computed ranged fields for sorting
            list = list.map(s => ({
                ...s,
                rangedSales:  this.getStat(s, 'sales'),
                rangedOrders: this.getStat(s, 'orders'),
            }));

            if (this.filterRole)   list = list.filter(s => s.role === this.filterRole);
            if (this.filterStatus) list = list.filter(s => s.status === this.filterStatus);

            if (this.search.trim()) {
                const q = this.search.toLowerCase();
                list = list.filter(s => s.name.toLowerCase().includes(q) || s.email.toLowerCase().includes(q));
            }

            if (this.quickFilters.topPerformers) list = list.filter(s => s.rangedSales >= this.getStat({todaySales:1500,weeklySales:10500,todayOrders:0},'sales'));
            if (this.quickFilters.inactive)      list = list.filter(s => s.status === 'offline');
            if (this.quickFilters.highRefund)    list = list.filter(s => s.refundRate > 5);

            list.sort((a, b) => {
                const av = a[this.sortKey] ?? 0;
                const bv = b[this.sortKey] ?? 0;
                if (av < bv) return this.sortDir;
                if (av > bv) return -this.sortDir;
                return 0;
            });

            this.filtered = list;
        },

        // Alias for old calls
        filter() { this.filterStaff(); },

        toggleQuickFilter(key) {
            this.quickFilters[key] = !this.quickFilters[key];
            this.filterStaff();
        },

        clearFilters() {
            this.search = ''; this.filterRole = ''; this.filterStatus = '';
            this.quickFilters = { topPerformers: false, inactive: false, highRefund: false };
            this.filterStaff();
        },

        sortBy(key) {
            if (this.sortKey === key) this.sortDir *= -1;
            else { this.sortKey = key; this.sortDir = -1; }
            this.filterStaff();
        },

        /* ═══════════════════════════════════════════════════════
           DRAWER
           ═══════════════════════════════════════════════════════ */
        openDrawer(staffMember) {
            if (!staffMember) return;
            this.drawer.staff         = staffMember;
            this.drawer.tab           = 'Overview';
            this.drawer.shiftDuration = staffMember.shiftDuration || '—';
            this.drawer.shiftStart    = staffMember.shiftStart    || '—';
            this.drawer.activities = [
                { id:1, action:'Sale',     orderId:1042, amount:128.50, time:'10:02 AM' },
                { id:2, action:'Sale',     orderId:1041, amount: 49.99, time:'09:48 AM' },
                { id:3, action:'Discount', orderId:1040, amount: 15.00, time:'09:30 AM' },
                { id:4, action:'Sale',     orderId:1039, amount: 84.20, time:'09:15 AM' },
                { id:5, action:'Refund',   orderId:1035, amount: 39.99, time:'08:52 AM' },
                { id:6, action:'Sale',     orderId:1034, amount:205.60, time:'08:40 AM' },
                { id:7, action:'Discount', orderId:1032, amount: 12.00, time:'08:25 AM' },
                { id:8, action:'Sale',     orderId:1030, amount: 67.00, time:'08:12 AM' },
            ];
            this.drawer.shifts = [
                { id:1, date:'Today',        clockIn:'08:30 AM', clockOut:null,       duration:'In progress', sales:staffMember.todaySales },
                { id:2, date:'Apr 20, 2025', clockIn:'09:00 AM', clockOut:'05:15 PM', duration:'8h 15m',     sales:1950 },
                { id:3, date:'Apr 19, 2025', clockIn:'08:45 AM', clockOut:'05:00 PM', duration:'8h 15m',     sales:2200 },
                { id:4, date:'Apr 18, 2025', clockIn:'09:15 AM', clockOut:'06:00 PM', duration:'8h 45m',     sales:1800 },
                { id:5, date:'Apr 17, 2025', clockIn:'08:30 AM', clockOut:'04:45 PM', duration:'8h 15m',     sales:2400 },
            ];
            this.drawer.activityLogs = [
                { id:1, type:'login',    description:'Logged into the POS system',               time:'Today, 8:30 AM'  },
                { id:2, type:'sale',     description:'Completed order #1030 – $67.00',            time:'Today, 8:45 AM'  },
                { id:3, type:'discount', description:'Applied 10% discount on order #1032',      time:'Today, 9:02 AM'  },
                { id:4, type:'refund',   description:'Issued refund of $39.99 on order #1035',   time:'Today, 9:15 AM'  },
                { id:5, type:'sale',     description:'Completed order #1041 – $49.99',            time:'Today, 9:48 AM'  },
                { id:6, type:'sale',     description:'Completed order #1042 – $128.50',           time:'Today, 10:02 AM' },
                { id:7, type:'setting',  description:'Updated customer info for Emma Thompson',  time:'Today, 10:30 AM' },
            ];
            this.drawer.open = true;
        },

        openActivityLog(staffMember) {
            this.openDrawer(staffMember);
            this.$nextTick(() => { this.drawer.tab = 'Activity'; });
        },

        toggleClock() {
            if (!this.drawer.staff) return;
            const s = this.staff.find(x => x.id === this.drawer.staff.id);
            if (!s) return;
            s.status = s.status === 'active' ? 'offline' : 'active';
            if (s.status === 'active') {
                s.shiftDuration = '0h 0m';
                s.shiftStart    = new Date().toLocaleTimeString('en-US', { hour:'2-digit', minute:'2-digit' });
                s.lastLogin     = 'Just now';
            } else {
                s.shiftDuration = '—';
                s.lastLogin     = 'Now';
            }
            this.drawer.staff         = { ...s };
            this.drawer.shiftDuration = s.shiftDuration;
            this.drawer.shiftStart    = s.shiftStart;
            this.filterStaff();
            const msg = s.status === 'active' ? s.name + ' clocked in.' : s.name + ' clocked out.';
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: msg, type: 'success' } }));
        },

        /* ═══════════════════════════════════════════════════════
           MODALS
           ═══════════════════════════════════════════════════════ */
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
            if (!staffMember) return;
            this.modal.editing = true;
            this.modal.form = { ...staffMember, permissions: [...(staffMember.permissions || [])] };
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
                    ...this.modal.form, id: Date.now(), status: 'offline',
                    todaySales: 0, todayOrders: 0, weeklySales: 0,
                    refundRate: 0, discountUsage: 0, avgProcessTime: 0,
                    shiftDuration: '—', shiftStart: '—', lastLogin: 'Never',
                    joined: new Date().toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'}),
                    salesTrend: [0,0,0,0,0,0,0],
                });
                window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'Staff member added.', type: 'success' } }));
            }
            this.modal.open = false;
            this.filterStaff();
        },

        confirmDisable(staffMember) {
            this.disableModal.staff = staffMember;
            this.disableModal.open  = true;
        },

        disableStaff() {
            this.staff = this.staff.filter(s => s.id !== this.disableModal.staff.id);
            if (this.drawer.staff?.id === this.disableModal.staff.id) this.drawer.open = false;
            this.disableModal.open = false;
            this.filterStaff();
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'Staff account disabled.', type: 'info' } }));
        },

        confirmResetPassword(staffMember) {
            this.resetModal.staff = staffMember;
            this.resetModal.open  = true;
        },

        doResetPassword() {
            const name = this.resetModal.staff?.name || 'Staff member';
            this.resetModal.open = false;
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'Password reset sent to ' + name + '.', type: 'success' } }));
        },
    };
}
</script>

</x-layouts.admin>
