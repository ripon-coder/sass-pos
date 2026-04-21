<x-layouts.admin pageTitle="Dashboard">

<div class="p-6 lg:p-8 space-y-8" x-data="dashboardPage()" x-init="init()">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Dashboard</h2>
            <p class="text-[13px] text-slate-400 mt-1">Monday, April 21, 2025 — <span class="text-slate-500 font-medium">Acme Store</span></p>
        </div>
        <div class="flex items-center gap-2">
            <a href="/pos" class="btn btn-primary btn-lg gap-2" id="dashboard-new-sale-btn">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                New Sale
                <span class="kbd text-blue-200 bg-blue-500/30 border-blue-400/30 ml-1">N</span>
            </a>
        </div>
    </div>

    {{-- ===== STAT CARDS WITH SPARKLINES ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        {{-- Today's Sales --}}
        <div class="stat-card" style="background: linear-gradient(135deg, #ffffff 0%, #eff6ff 100%);">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="stat-label">Today's Sales</p>
                    <p class="stat-value">$4,280</p>
                </div>
                <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-[1.125rem] h-[1.125rem] text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <div class="sparkline mb-2">
                @foreach([35, 50, 40, 60, 55, 70, 65] as $v)
                <div class="sparkline-bar bg-blue-400/60" style="height: {{ ($v / 70) * 100 }}%"></div>
                @endforeach
            </div>
            <div class="stat-delta stat-delta-up">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" /></svg>
                <span>12.5% vs yesterday</span>
            </div>
        </div>

        {{-- Weekly Revenue --}}
        <div class="stat-card" style="background: linear-gradient(135deg, #ffffff 0%, #f5f3ff 100%);">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="stat-label">Weekly Revenue</p>
                    <p class="stat-value">$26,100</p>
                </div>
                <div class="w-9 h-9 rounded-xl bg-violet-100 flex items-center justify-center">
                    <svg class="w-[1.125rem] h-[1.125rem] text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" /></svg>
                </div>
            </div>
            <div class="sparkline mb-2">
                @foreach([40, 55, 45, 65, 50, 70, 60] as $v)
                <div class="sparkline-bar bg-violet-400/60" style="height: {{ ($v / 70) * 100 }}%"></div>
                @endforeach
            </div>
            <div class="stat-delta stat-delta-up">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" /></svg>
                <span>8.2% vs last week</span>
            </div>
        </div>

        {{-- Monthly Revenue --}}
        <div class="stat-card" style="background: linear-gradient(135deg, #ffffff 0%, #ecfdf5 100%);">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="stat-label">Monthly Revenue</p>
                    <p class="stat-value">$98,420</p>
                </div>
                <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-[1.125rem] h-[1.125rem] text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" /></svg>
                </div>
            </div>
            <div class="sparkline mb-2">
                @foreach([60, 55, 50, 45, 40, 48, 42] as $v)
                <div class="sparkline-bar bg-emerald-400/60" style="height: {{ ($v / 60) * 100 }}%"></div>
                @endforeach
            </div>
            <div class="stat-delta stat-delta-down">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" /></svg>
                <span>2.3% vs last month</span>
            </div>
        </div>

        {{-- Total Orders --}}
        <div class="stat-card" style="background: linear-gradient(135deg, #ffffff 0%, #fffbeb 100%);">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="stat-label">Total Orders Today</p>
                    <p class="stat-value">64</p>
                </div>
                <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-[1.125rem] h-[1.125rem] text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                </div>
            </div>
            <div class="sparkline mb-2">
                @foreach([30, 45, 35, 50, 42, 55, 64] as $v)
                <div class="sparkline-bar bg-amber-400/60" style="height: {{ ($v / 64) * 100 }}%"></div>
                @endforeach
            </div>
            <div class="stat-delta stat-delta-up">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" /></svg>
                <span>18 more than yesterday</span>
            </div>
        </div>
    </div>

    {{-- ===== TRANSACTIONS + SIDEBAR ===== --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Recent Transactions (clickable rows → drawer) --}}
        <div class="card xl:col-span-2">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-slate-800">Recent Transactions</h3>
                <div class="flex items-center gap-2">
                    <a href="/orders" class="btn btn-ghost btn-sm text-blue-600 hover:text-blue-700 gap-1">
                        View all
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </a>
                </div>
            </div>
            <div class="table-wrapper">
                <table class="table" id="recent-transactions-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="tx in paginatedTransactions" :key="tx.id">
                            <tr class="table-row-clickable" @click="openTxDrawer(tx)">
                                <td><span class="font-mono text-[11px] text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded" x-text="'#' + tx.id"></span></td>
                                <td>
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-6 h-6 rounded-md flex items-center justify-center flex-shrink-0 text-[9px] font-bold" :class="tx.avatarColor" x-text="tx.customer.split(' ').map(n => n[0]).join('')"></div>
                                        <span class="font-medium text-slate-700 text-[13px]" x-text="tx.customer"></span>
                                    </div>
                                </td>
                                <td class="text-slate-500" x-text="tx.items + ' items'"></td>
                                <td class="font-semibold text-slate-800" x-text="'$' + tx.amount.toFixed(2)"></td>
                                <td>
                                    <span class="badge badge-dot"
                                        :class="{
                                            'badge-success': tx.status === 'completed',
                                            'badge-warning': tx.status === 'pending',
                                            'badge-danger':  tx.status === 'refunded',
                                        }"
                                        x-text="tx.status.charAt(0).toUpperCase() + tx.status.slice(1)"
                                    ></span>
                                </td>
                                <td class="text-slate-400 text-[11px]" x-text="tx.time"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="flex items-center justify-between px-4 py-3 border-t border-slate-100">
                <p class="text-[11px] text-slate-400" x-text="'Showing ' + ((currentPage - 1) * perPage + 1) + '–' + Math.min(currentPage * perPage, allTransactions.length) + ' of ' + allTransactions.length"></p>
                <div class="pagination">
                    <button class="pagination-btn" :disabled="currentPage === 1" @click="currentPage--">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                    </button>
                    <template x-for="page in totalPages" :key="page">
                        <button class="pagination-btn" :class="page === currentPage && 'active'" @click="currentPage = page" x-text="page"></button>
                    </template>
                    <button class="pagination-btn" :disabled="currentPage === totalPages" @click="currentPage++">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Right Panel: Quick Actions + Top Products --}}
        <div class="flex flex-col gap-5">

            {{-- Quick Actions --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold text-slate-800">Quick Actions</h3>
                </div>
                <div class="card-body space-y-2">
                    <a href="/pos" class="w-full btn btn-primary justify-between" id="quick-new-sale-btn">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.943-7.143a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                            Open POS Screen
                        </div>
                        <span class="kbd text-blue-300 bg-blue-500/20 border-blue-400/20">N</span>
                    </a>
                    <a href="/products?action=add" class="w-full btn btn-secondary justify-between" id="quick-add-product-btn">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                            Add Product
                        </div>
                        <span class="kbd">P</span>
                    </a>
                    <a href="/customers?action=add" class="w-full btn btn-secondary justify-between" id="quick-add-customer-btn">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" /></svg>
                            Add Customer
                        </div>
                        <span class="kbd">C</span>
                    </a>
                    <a href="/orders" class="w-full btn btn-secondary justify-between" id="quick-view-orders-btn">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                            View All Orders
                        </div>
                        <span class="kbd">O</span>
                    </a>
                </div>
            </div>

            {{-- Top Products --}}
            <div class="card flex-1">
                <div class="card-header">
                    <h3 class="text-sm font-semibold text-slate-800">Top Products</h3>
                    <span class="badge badge-gray">Today</span>
                </div>
                <div class="divide-y divide-slate-50">
                    @foreach([
                        ['Wireless Headphones', 42, '$1,890', 'bg-blue-50 text-blue-600',   '🎧'],
                        ['Mechanical Keyboard', 29, '$1,305', 'bg-violet-50 text-violet-600','⌨️'],
                        ['USB-C Hub',           51, '$994',   'bg-emerald-50 text-emerald-600','🔌'],
                        ['Laptop Stand',        18, '$720',   'bg-amber-50 text-amber-600', '💻'],
                        ['Screen Protector',    70, '$349',   'bg-rose-50 text-rose-600',   '🛡️'],
                    ] as [$name, $units, $rev, $color, $emoji])
                    <div class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50/50 transition-colors">
                        <div class="w-8 h-8 rounded-lg {{ $color }} flex items-center justify-center text-sm flex-shrink-0">{{ $emoji }}</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] font-medium text-slate-700 truncate">{{ $name }}</p>
                            <p class="text-[11px] text-slate-400">{{ $units }} units sold</p>
                        </div>
                        <span class="text-[13px] font-semibold text-slate-800">{{ $rev }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TRANSACTION DETAIL DRAWER ===== --}}
    <div x-show="txDrawer.open" class="fixed inset-0 z-50 flex" x-cloak>
        <div class="absolute inset-0 bg-black/30" @click="txDrawer.open = false"></div>
        <div
            class="absolute right-0 top-0 bottom-0 w-full max-w-md bg-white shadow-xl flex flex-col"
            x-transition:enter="transition ease-out duration-250" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"  x-transition:leave-start="translate-x-0"    x-transition:leave-end="translate-x-full"
            id="tx-detail-drawer"
        >
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <div>
                    <h2 class="text-base font-semibold text-slate-800" x-text="'Order #' + txDrawer.tx?.id"></h2>
                    <p class="text-[11px] text-slate-400 mt-0.5" x-text="txDrawer.tx?.time"></p>
                </div>
                <button @click="txDrawer.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-6 space-y-5">
                <div class="flex items-center justify-between">
                    <span class="badge badge-dot text-sm px-3 py-1"
                        :class="{
                            'badge-success': txDrawer.tx?.status === 'completed',
                            'badge-warning': txDrawer.tx?.status === 'pending',
                            'badge-danger':  txDrawer.tx?.status === 'refunded',
                        }"
                        x-text="txDrawer.tx?.status?.charAt(0).toUpperCase() + txDrawer.tx?.status?.slice(1)"
                    ></span>
                    <div class="flex gap-2">
                        <button class="btn btn-secondary btn-sm gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a.75.75 0 01-.875.854 49.188 49.188 0 01-14.01 0 .75.75 0 01-.875-.854L2.34 18m15.32 0H6.34" /></svg>
                            Print
                        </button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Customer</p>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center text-[11px] font-bold" :class="txDrawer.tx?.avatarColor" x-text="txDrawer.tx?.customer?.split(' ').map(n => n[0]).join('')"></div>
                            <div>
                                <p class="font-medium text-slate-700 text-[13px]" x-text="txDrawer.tx?.customer"></p>
                                <p class="text-[11px] text-slate-400">Returning customer</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Items</p>
                    </div>
                    <div class="divide-y divide-slate-50">
                        <template x-for="item in txDrawer.tx?.lineItems || []" :key="item.name">
                            <div class="flex items-center gap-3 px-4 py-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-lg" x-text="item.emoji"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[13px] font-medium text-slate-700 truncate" x-text="item.name"></p>
                                    <p class="text-[11px] text-slate-400" x-text="'x' + item.qty + ' @ $' + item.price.toFixed(2)"></p>
                                </div>
                                <span class="text-[13px] font-semibold text-slate-700" x-text="'$' + (item.qty * item.price).toFixed(2)"></span>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body space-y-2 text-[13px]">
                        <div class="flex justify-between text-slate-500"><span>Subtotal</span><span x-text="'$' + (txDrawer.tx?.amount * 0.92).toFixed(2)"></span></div>
                        <div class="flex justify-between text-slate-500"><span>Tax (8.5%)</span><span x-text="'$' + (txDrawer.tx?.amount * 0.08).toFixed(2)"></span></div>
                        <div class="flex justify-between font-bold text-slate-900 text-base pt-2 border-t border-slate-100"><span>Total</span><span x-text="'$' + txDrawer.tx?.amount.toFixed(2)"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function dashboardPage() {
    return {
        currentPage: 1,
        perPage: 5,
        txDrawer: { open: false, tx: null },
        allTransactions: [
            { id: 1042, customer: 'Sarah Evans',  avatarColor: 'bg-blue-100 text-blue-600',    items: 3, amount: 128.50, status: 'completed', time: '2 min ago', lineItems: [{name:'Wireless Headphones',emoji:'🎧',qty:1,price:89.99},{name:'USB-C Hub',emoji:'🔌',qty:2,price:19.25}] },
            { id: 1041, customer: 'Mike Johnson', avatarColor: 'bg-violet-100 text-violet-600', items: 1, amount: 49.99,  status: 'pending',   time: '15 min ago', lineItems: [{name:'Mechanical Keyboard',emoji:'⌨️',qty:1,price:49.99}] },
            { id: 1040, customer: 'Anna Lee',     avatarColor: 'bg-emerald-100 text-emerald-600', items: 5, amount: 312.00, status: 'completed', time: '42 min ago', lineItems: [{name:'Laptop Stand',emoji:'💻',qty:2,price:39.99},{name:'HDMI Cable',emoji:'📺',qty:3,price:12.99},{name:'Mouse Pad',emoji:'🖱️',qty:1,price:24.99}] },
            { id: 1039, customer: 'David Kim',    avatarColor: 'bg-rose-100 text-rose-600',    items: 2, amount: 84.20,  status: 'refunded',  time: '1h ago', lineItems: [{name:'Screen Protector',emoji:'🛡️',qty:2,price:14.99},{name:'Phone Stand',emoji:'📱',qty:1,price:19.99}] },
            { id: 1038, customer: 'Lisa Park',    avatarColor: 'bg-amber-100 text-amber-600',  items: 4, amount: 205.60, status: 'completed', time: '2h ago', lineItems: [{name:'Wireless Mouse',emoji:'🖱️',qty:1,price:45.00},{name:'Power Bank',emoji:'🔋',qty:1,price:54.99},{name:'USB Cable',emoji:'🔗',qty:2,price:9.99}] },
            { id: 1037, customer: 'Tom Brown',    avatarColor: 'bg-slate-100 text-slate-600',  items: 1, amount: 45.00,  status: 'completed', time: '3h ago', lineItems: [{name:'Wireless Mouse',emoji:'🖱️',qty:1,price:45.00}] },
            { id: 1036, customer: 'Emma Hart',    avatarColor: 'bg-cyan-100 text-cyan-600',    items: 3, amount: 167.50, status: 'completed', time: '3h ago', lineItems: [{name:'Webcam HD',emoji:'📷',qty:1,price:79.99},{name:'HDMI Cable',emoji:'📺',qty:2,price:12.99}] },
            { id: 1035, customer: 'James Wu',     avatarColor: 'bg-pink-100 text-pink-600',    items: 2, amount: 99.98,  status: 'pending',   time: '4h ago', lineItems: [{name:'USB-C Hub',emoji:'🔌',qty:2,price:49.99}] },
        ],

        init() {
            // Keyboard shortcuts
            document.addEventListener('keydown', (e) => {
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') return;
                if (e.key === 'n' || e.key === 'N') window.location.href = '/pos';
                if (e.key === 'p' || e.key === 'P') window.location.href = '/products?action=add';
                if (e.key === 'c' || e.key === 'C') window.location.href = '/customers?action=add';
                if (e.key === 'o' || e.key === 'O') window.location.href = '/orders';
            });
        },

        get paginatedTransactions() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.allTransactions.slice(start, start + this.perPage);
        },
        get totalPages() {
            return Math.ceil(this.allTransactions.length / this.perPage);
        },

        openTxDrawer(tx) {
            this.txDrawer.tx = tx;
            this.txDrawer.open = true;
        },
    };
}
</script>

</x-layouts.admin>
