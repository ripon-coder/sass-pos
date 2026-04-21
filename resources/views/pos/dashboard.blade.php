<x-layouts.admin pageTitle="Dashboard">

<div class="p-6 space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Dashboard</h2>
            <p class="text-sm text-slate-500 mt-0.5">Monday, April 21, 2025 — Acme Store</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="/pos" class="btn btn-primary" id="dashboard-new-sale-btn">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                New Sale
            </a>
        </div>
    </div>

    {{-- ======= STAT CARDS ======= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Today's Sales --}}
        <div class="stat-card">
            <div class="flex items-start justify-between">
                <p class="stat-label">Today's Sales</p>
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="stat-value">$4,280</p>
            <div class="stat-delta stat-delta-up">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" />
                </svg>
                <span>12.5% vs yesterday</span>
            </div>
        </div>

        {{-- Weekly Revenue --}}
        <div class="stat-card">
            <div class="flex items-start justify-between">
                <p class="stat-label">Weekly Revenue</p>
                <div class="w-8 h-8 rounded-lg bg-violet-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
                    </svg>
                </div>
            </div>
            <p class="stat-value">$26,100</p>
            <div class="stat-delta stat-delta-up">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" />
                </svg>
                <span>8.2% vs last week</span>
            </div>
        </div>

        {{-- Monthly Revenue --}}
        <div class="stat-card">
            <div class="flex items-start justify-between">
                <p class="stat-label">Monthly Revenue</p>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                    </svg>
                </div>
            </div>
            <p class="stat-value">$98,420</p>
            <div class="stat-delta stat-delta-down">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" />
                </svg>
                <span>2.3% vs last month</span>
            </div>
        </div>

        {{-- Total Orders --}}
        <div class="stat-card">
            <div class="flex items-start justify-between">
                <p class="stat-label">Total Orders Today</p>
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                    </svg>
                </div>
            </div>
            <p class="stat-value">64</p>
            <div class="stat-delta stat-delta-up">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" />
                </svg>
                <span>18 more than yesterday</span>
            </div>
        </div>
    </div>

    {{-- ======= RECENT TRANSACTIONS + QUICK ACTIONS ======= --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Recent Transactions --}}
        <div class="card xl:col-span-2">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-slate-800">Recent Transactions</h3>
                <a href="/orders" class="btn btn-ghost btn-sm text-blue-600 hover:text-blue-700">View all</a>
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
                        <tr>
                            <td><span class="font-mono text-xs text-slate-500">#1042</span></td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-[10px] font-semibold text-blue-600">SE</span>
                                    </div>
                                    <span class="font-medium text-slate-700">Sarah Evans</span>
                                </div>
                            </td>
                            <td class="text-slate-500">3 items</td>
                            <td class="font-semibold text-slate-800">$128.50</td>
                            <td><span class="badge badge-success">Completed</span></td>
                            <td class="text-slate-400 text-xs">2m ago</td>
                        </tr>
                        <tr>
                            <td><span class="font-mono text-xs text-slate-500">#1041</span></td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-violet-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-[10px] font-semibold text-violet-600">MJ</span>
                                    </div>
                                    <span class="font-medium text-slate-700">Mike Johnson</span>
                                </div>
                            </td>
                            <td class="text-slate-500">1 item</td>
                            <td class="font-semibold text-slate-800">$49.99</td>
                            <td><span class="badge badge-warning">Pending</span></td>
                            <td class="text-slate-400 text-xs">15m ago</td>
                        </tr>
                        <tr>
                            <td><span class="font-mono text-xs text-slate-500">#1040</span></td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-[10px] font-semibold text-emerald-600">AL</span>
                                    </div>
                                    <span class="font-medium text-slate-700">Anna Lee</span>
                                </div>
                            </td>
                            <td class="text-slate-500">5 items</td>
                            <td class="font-semibold text-slate-800">$312.00</td>
                            <td><span class="badge badge-success">Completed</span></td>
                            <td class="text-slate-400 text-xs">42m ago</td>
                        </tr>
                        <tr>
                            <td><span class="font-mono text-xs text-slate-500">#1039</span></td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-rose-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-[10px] font-semibold text-rose-600">DK</span>
                                    </div>
                                    <span class="font-medium text-slate-700">David Kim</span>
                                </div>
                            </td>
                            <td class="text-slate-500">2 items</td>
                            <td class="font-semibold text-slate-800">$84.20</td>
                            <td><span class="badge badge-danger">Refunded</span></td>
                            <td class="text-slate-400 text-xs">1h ago</td>
                        </tr>
                        <tr>
                            <td><span class="font-mono text-xs text-slate-500">#1038</span></td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-[10px] font-semibold text-amber-600">LP</span>
                                    </div>
                                    <span class="font-medium text-slate-700">Lisa Park</span>
                                </div>
                            </td>
                            <td class="text-slate-500">4 items</td>
                            <td class="font-semibold text-slate-800">$205.60</td>
                            <td><span class="badge badge-success">Completed</span></td>
                            <td class="text-slate-400 text-xs">2h ago</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Actions + Top Products --}}
        <div class="flex flex-col gap-4">

            {{-- Quick Actions --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold text-slate-800">Quick Actions</h3>
                </div>
                <div class="card-body space-y-2">
                    <a href="/pos" class="w-full btn btn-primary justify-start gap-3" id="quick-new-sale-btn">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.943-7.143a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                        Open POS Screen
                    </a>
                    <a href="/products?action=add" class="w-full btn btn-secondary justify-start gap-3" id="quick-add-product-btn">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                        Add Product
                    </a>
                    <a href="/customers?action=add" class="w-full btn btn-secondary justify-start gap-3" id="quick-add-customer-btn">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                        </svg>
                        Add Customer
                    </a>
                    <a href="/orders" class="w-full btn btn-secondary justify-start gap-3" id="quick-view-orders-btn">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                        View All Orders
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
                        ['Wireless Headphones',    42, '$1,890'],
                        ['Mechanical Keyboard',    29, '$1,305'],
                        ['USB-C Hub',              51, '$994'],
                        ['Laptop Stand',           18, '$720'],
                        ['Screen Protector',       70, '$349'],
                    ] as [$name, $units, $rev])
                    <div class="flex items-center gap-3 px-4 py-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-700 truncate">{{ $name }}</p>
                            <p class="text-xs text-slate-400">{{ $units }} units</p>
                        </div>
                        <span class="text-sm font-semibold text-slate-800">{{ $rev }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

</x-layouts.admin>
