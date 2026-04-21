<x-layouts.admin pageTitle="Orders">

<div class="p-6 space-y-5" x-data="ordersPage()" x-init="init()">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Orders</h2>
            <p class="text-sm text-slate-500 mt-0.5">View and manage all sales transactions</p>
        </div>
        <div class="flex items-center gap-2">
            <button class="btn btn-secondary gap-1.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Export
            </button>
        </div>
    </div>

    {{-- ===== FILTERS ===== --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 max-w-xs">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            <input type="search" x-model="search" @input="filter()" placeholder="Search by order # or customer…" class="form-input pl-9" id="orders-search">
        </div>
        <select x-model="filterStatus" @change="filter()" class="form-input form-select w-40" id="orders-status-filter">
            <option value="">All Status</option>
            <option value="completed">Completed</option>
            <option value="pending">Pending</option>
            <option value="refunded">Refunded</option>
            <option value="cancelled">Cancelled</option>
        </select>
        <select x-model="filterPayment" @change="filter()" class="form-input form-select w-40" id="orders-payment-filter">
            <option value="">All Payments</option>
            <option value="cash">Cash</option>
            <option value="card">Card</option>
        </select>
        <span class="text-xs text-slate-400 ml-auto" x-text="filtered.length + ' orders'"></span>
    </div>

    {{-- ===== ORDERS TABLE ===== --}}
    <div class="card overflow-hidden">
        <div class="table-wrapper">
            <table class="table" id="orders-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Staff</th>
                        <th>Date & Time</th>
                        <th>Items</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Empty State --}}
                    <tr x-show="filtered.length === 0" x-cloak>
                        <td colspan="9">
                            <div class="flex flex-col items-center justify-center py-14 text-center">
                                <svg class="w-12 h-12 text-slate-200 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                                <p class="text-sm font-medium text-slate-500">No orders found</p>
                                <p class="text-xs text-slate-400 mt-1">Try adjusting your search or filters</p>
                            </div>
                        </td>
                    </tr>

                    {{-- Order Rows --}}
                    <template x-for="o in filtered" :key="o.id">
                        <tr @click="openDrawer(o)"
                            class="cursor-pointer hover:bg-slate-50/80 transition-colors group"
                            :class="{ 'bg-amber-50/40': o.discountPct >= 15 || o.status === 'refunded' }"
                            :id="'order-row-' + o.id"
                        >
                            <td>
                                <div class="flex items-center gap-1.5">
                                    <span class="font-mono text-sm font-semibold text-slate-700" x-text="'#' + o.id"></span>
                                    {{-- Risk indicator --}}
                                    <template x-if="o.discountPct >= 15">
                                        <span class="text-amber-500" title="High discount applied">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                        </span>
                                    </template>
                                    <template x-if="o.status === 'refunded'">
                                        <span class="text-rose-500" title="Refunded order">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                                        </span>
                                    </template>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 text-[10px] font-bold"
                                        :class="o.avatarColor"
                                        x-text="o.customer.split(' ').map(n => n[0]).join('')"></div>
                                    <span class="font-medium text-slate-700 text-sm" x-text="o.customer"></span>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 text-[9px] font-bold"
                                        :class="o.staffColor"
                                        x-text="o.staff.split(' ').map(n => n[0]).join('')"></div>
                                    <span class="text-xs text-slate-500" x-text="o.staff"></span>
                                </div>
                            </td>
                            <td class="text-slate-500 text-xs" x-text="o.datetime"></td>
                            <td class="text-slate-500 text-sm" x-text="o.items + ' item' + (o.items !== 1 ? 's' : '')"></td>
                            <td>
                                <div class="flex items-center gap-1.5">
                                    <svg x-show="o.payment === 'cash'" class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                                    <svg x-show="o.payment === 'card'" class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                                    <span class="text-xs text-slate-500 capitalize" x-text="o.payment"></span>
                                </div>
                            </td>
                            <td class="font-semibold text-slate-800" x-text="'$' + o.total.toFixed(2)"></td>
                            <td>
                                <span class="badge"
                                    :class="{
                                        'badge-success': o.status === 'completed',
                                        'badge-warning': o.status === 'pending',
                                        'badge-danger':  o.status === 'refunded',
                                        'badge-gray':    o.status === 'cancelled',
                                    }"
                                    x-text="o.status.charAt(0).toUpperCase() + o.status.slice(1)"
                                ></span>
                            </td>
                            <td @click.stop>
                                <button @click="openDrawer(o)" class="btn btn-ghost btn-sm text-blue-600 hover:text-blue-700 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                    View →
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- ===== ORDER DETAIL DRAWER ===== --}}
    {{-- ================================================================ --}}
    <div x-show="drawer.open" class="fixed inset-0 z-50 flex" x-cloak>
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]" @click="drawer.open = false"></div>

        {{-- Drawer Panel --}}
        <div class="absolute right-0 top-0 bottom-0 w-full max-w-[520px] bg-white shadow-2xl flex flex-col"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            id="order-detail-drawer"
        >

            {{-- ── DRAWER HEADER ── --}}
            <div class="flex items-start justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex-shrink-0">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-bold text-slate-800 font-mono" x-text="'Order #' + drawer.order?.id"></h2>

                        {{-- Copy Order ID --}}
                        <button @click="copyOrderId()"
                            title="Copy order ID"
                            class="p-1 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-200 transition-colors relative"
                            id="copy-order-id-btn">
                            <svg x-show="!drawer.copied" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"/></svg>
                            <svg x-show="drawer.copied" class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" x-cloak><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </button>

                        {{-- Risk badges --}}
                        <template x-if="drawer.order?.discountPct >= 15">
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                High Discount
                            </span>
                        </template>
                        <template x-if="drawer.order?.status === 'refunded'">
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 border border-rose-200">
                                Refunded
                            </span>
                        </template>
                    </div>
                    <p class="text-xs text-slate-500" x-text="drawer.order?.datetime"></p>
                </div>

                {{-- Nav + Close --}}
                <div class="flex items-center gap-1">
                    <button @click="prevOrder()" :disabled="drawerIndex <= 0"
                        title="Previous order"
                        class="btn btn-ghost btn-icon btn-sm text-slate-400 hover:text-slate-700 disabled:opacity-30 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    </button>
                    <span class="text-xs text-slate-400 font-medium tabular-nums"
                        x-text="(drawerIndex + 1) + ' / ' + filtered.length"></span>
                    <button @click="nextOrder()" :disabled="drawerIndex >= filtered.length - 1"
                        title="Next order"
                        class="btn btn-ghost btn-icon btn-sm text-slate-400 hover:text-slate-700 disabled:opacity-30 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </button>
                    <div class="w-px h-4 bg-slate-200 mx-1"></div>
                    <button @click="drawer.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400 hover:text-slate-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- ── STATUS BAR + ACTIONS ── --}}
            <div class="px-6 py-3 border-b border-slate-100 flex items-center justify-between gap-3 flex-shrink-0 bg-white">
                {{-- Interactive Status Selector --}}
                <div x-data="{ statusOpen: false }" class="relative">
                    <button @click="statusOpen = !statusOpen"
                        class="inline-flex items-center gap-1.5 pl-3 pr-2 py-1.5 rounded-lg border text-sm font-semibold transition-all duration-150"
                        :class="{
                            'bg-emerald-50 border-emerald-200 text-emerald-700': drawer.order?.status === 'completed',
                            'bg-amber-50 border-amber-200 text-amber-700':   drawer.order?.status === 'pending',
                            'bg-rose-50 border-rose-200 text-rose-700':      drawer.order?.status === 'refunded',
                            'bg-slate-50 border-slate-200 text-slate-500':   drawer.order?.status === 'cancelled',
                        }"
                        id="order-status-btn">
                        <span class="w-1.5 h-1.5 rounded-full"
                            :class="{
                                'bg-emerald-500': drawer.order?.status === 'completed',
                                'bg-amber-500':   drawer.order?.status === 'pending',
                                'bg-rose-500':    drawer.order?.status === 'refunded',
                                'bg-slate-400':   drawer.order?.status === 'cancelled',
                            }"></span>
                        <span x-text="drawer.order?.status?.charAt(0).toUpperCase() + drawer.order?.status?.slice(1)"></span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div x-show="statusOpen" @click.outside="statusOpen = false"
                        x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        class="absolute left-0 mt-1 w-40 bg-white border border-slate-200 rounded-xl shadow-lg z-30 py-1.5" x-cloak>
                        <template x-for="s in ['pending','completed','refunded','cancelled']" :key="s">
                            <button @click="updateStatus(s); statusOpen = false"
                                class="w-full flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-left hover:bg-slate-50 transition-colors capitalize"
                                :class="drawer.order?.status === s ? 'text-blue-600' : 'text-slate-600'"
                            >
                                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                                    :class="{
                                        'bg-emerald-500': s === 'completed',
                                        'bg-amber-500':   s === 'pending',
                                        'bg-rose-500':    s === 'refunded',
                                        'bg-slate-400':   s === 'cancelled',
                                    }"></span>
                                <span x-text="s.charAt(0).toUpperCase() + s.slice(1)"></span>
                                <svg x-show="drawer.order?.status === s" class="w-3 h-3 ml-auto text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="flex items-center gap-2">
                    <button class="btn btn-ghost btn-sm gap-1.5 text-slate-500 hover:text-slate-700" title="Print receipt" id="drawer-print-btn">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a.75.75 0 01-.875.854 49.188 49.188 0 01-14.01 0 .75.75 0 01-.875-.854L2.34 18m15.32 0H6.34"/></svg>
                        Print
                    </button>

                    {{-- Mark as Completed (pending only) --}}
                    <button x-show="drawer.order?.status === 'pending'"
                        @click="updateStatus('completed')"
                        class="btn btn-sm gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white"
                        id="mark-complete-btn">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        Mark Completed
                    </button>

                    {{-- Edit (pending only) --}}
                    <button x-show="drawer.order?.status === 'pending'"
                        class="btn btn-secondary btn-sm gap-1.5"
                        id="edit-order-btn">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                        Edit
                    </button>

                    {{-- Refund (completed only) --}}
                    <button x-show="drawer.order?.status === 'completed'"
                        @click="drawer.refundPanel = !drawer.refundPanel"
                        class="btn btn-sm gap-1.5 bg-rose-600 hover:bg-rose-700 text-white"
                        id="refund-order-btn">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                        Refund
                    </button>
                </div>
            </div>

            {{-- ── DRAWER BODY ── --}}
            <div class="flex-1 overflow-y-auto p-5 space-y-4">

                {{-- ── REFUND PANEL (expandable) ── --}}
                <div x-show="drawer.refundPanel"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="bg-rose-50 border border-rose-200 rounded-xl p-4 space-y-3" x-cloak>
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-rose-800 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                            Process Refund
                        </h3>
                        <button @click="drawer.refundPanel = false" class="text-rose-400 hover:text-rose-700">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[11px] font-semibold text-rose-700 uppercase tracking-wide block mb-1">Refund Amount ($)</label>
                            <div class="relative">
                                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm">$</span>
                                <input type="number" x-model.number="drawer.refundForm.amount"
                                    :max="drawer.order?.total" step="0.01" min="0.01"
                                    :placeholder="drawer.order?.total?.toFixed(2)"
                                    class="form-input pl-6 text-sm" id="refund-amount-input">
                            </div>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-rose-700 uppercase tracking-wide block mb-1">Refund Reason</label>
                            <select x-model="drawer.refundForm.reason" class="form-input form-select text-sm" id="refund-reason-select">
                                <option value="">Select reason…</option>
                                <option value="customer_request">Customer Request</option>
                                <option value="defective">Defective Item</option>
                                <option value="wrong_item">Wrong Item</option>
                                <option value="overcharge">Overcharge</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold text-rose-700 uppercase tracking-wide block mb-1">Notes (optional)</label>
                        <input type="text" x-model="drawer.refundForm.notes" class="form-input text-sm" placeholder="Additional notes…" id="refund-notes-input">
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button @click="drawer.refundPanel = false" class="flex-1 btn btn-secondary btn-sm">Cancel</button>
                        <button @click="processRefund()"
                            :disabled="!drawer.refundForm.amount || !drawer.refundForm.reason"
                            class="flex-1 btn btn-sm bg-rose-600 hover:bg-rose-700 text-white disabled:opacity-40 disabled:cursor-not-allowed gap-1.5"
                            id="confirm-refund-btn">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Confirm Refund
                        </button>
                    </div>
                </div>

                {{-- ── ORDER TIMELINE ── --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Order Timeline</h3>
                    </div>
                    <div class="px-5 py-4">
                        <div class="relative">
                            {{-- Vertical line --}}
                            <div class="absolute left-3 top-3 bottom-3 w-px bg-slate-100"></div>

                            <div class="space-y-4">
                                <template x-for="event in drawer.order?.timeline || []" :key="event.key">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 z-10 shadow-sm"
                                            :class="event.done
                                                ? 'bg-emerald-100 border-2 border-emerald-300'
                                                : 'bg-slate-100 border-2 border-slate-200'">
                                            <svg x-show="event.done" class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                            <span x-show="!event.done" class="w-1.5 h-1.5 rounded-full bg-slate-300 block"></span>
                                        </div>
                                        <div class="flex-1 pt-0.5">
                                            <div class="flex items-center justify-between">
                                                <p class="text-xs font-semibold"
                                                    :class="event.done ? 'text-slate-800' : 'text-slate-400'"
                                                    x-text="event.label"></p>
                                                <p class="text-[11px]"
                                                    :class="event.done ? 'text-slate-400' : 'text-slate-300'"
                                                    x-text="event.time || '—'"></p>
                                            </div>
                                            <p class="text-[11px] text-slate-400 mt-0.5" x-text="event.note || ''"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── STAFF + CUSTOMER ROW ── --}}
                <div class="grid grid-cols-2 gap-3">
                    {{-- Staff --}}
                    <div class="card">
                        <div class="card-body">
                            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2.5">Served By</p>
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold"
                                    :class="drawer.order?.staffColor"
                                    x-text="drawer.order?.staff?.split(' ').map(n => n[0]).join('')"></div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800" x-text="drawer.order?.staff"></p>
                                    <p class="text-[11px] text-slate-400 capitalize" x-text="drawer.order?.staffRole"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Customer --}}
                    <div class="card">
                        <div class="card-body">
                            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2.5">Customer</p>
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold"
                                    :class="drawer.order?.avatarColor"
                                    x-text="drawer.order?.customer?.split(' ').map(n => n[0]).join('')"></div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800" x-text="drawer.order?.customer"></p>
                                    <p class="text-[11px] text-slate-400">Returning customer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── LINE ITEMS ── --}}
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Items</h3>
                        <span class="text-xs text-slate-400" x-text="(drawer.order?.lineItems?.length || 0) + ' product' + ((drawer.order?.lineItems?.length || 0) !== 1 ? 's' : '')"></span>
                    </div>
                    <div class="divide-y divide-slate-50">
                        <template x-for="item in (drawer.order?.lineItems || [])" :key="item.id">
                            <div class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50/60 transition-colors">
                                <div class="w-9 h-9 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-xl flex-shrink-0" x-text="item.emoji"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-800 truncate" x-text="item.name"></p>
                                    <div class="flex items-center gap-3 mt-0.5">
                                        <span class="text-[11px] text-slate-400 font-mono" x-text="'SKU: ' + item.sku"></span>
                                        <span class="text-[11px] text-slate-400" x-text="'× ' + item.qty + ' @ $' + item.price.toFixed(2)"></span>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-slate-800 flex-shrink-0" x-text="'$' + (item.qty * item.price).toFixed(2)"></span>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- ── PAYMENT SUMMARY ── --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Payment Summary</h3>
                        <div class="flex items-center gap-1.5">
                            <svg x-show="drawer.order?.payment === 'cash'" class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                            <svg x-show="drawer.order?.payment === 'card'" class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                            <span class="text-xs font-semibold capitalize"
                                :class="drawer.order?.payment === 'cash' ? 'text-emerald-600' : 'text-blue-600'"
                                x-text="drawer.order?.payment"></span>
                        </div>
                    </div>
                    <div class="card-body space-y-2 text-sm">
                        <div class="flex justify-between text-slate-500"><span>Subtotal</span><span x-text="'$' + (drawer.order?.subtotal || 0).toFixed(2)"></span></div>
                        <div class="flex justify-between text-emerald-700" x-show="(drawer.order?.discountPct || 0) > 0">
                            <span x-text="'Discount (' + drawer.order?.discountPct + '%)'"></span>
                            <span x-text="'− $' + (drawer.order?.discountAmt || 0).toFixed(2)"></span>
                        </div>

                        {{-- High discount risk warning --}}
                        <div x-show="(drawer.order?.discountPct || 0) >= 15"
                            class="flex items-center gap-1.5 text-[11px] text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-2.5 py-1.5 font-medium">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                            High discount applied — may require manager review.
                        </div>

                        <div class="flex justify-between text-slate-500"><span>Tax (8.5%)</span><span x-text="'$' + (drawer.order?.tax || 0).toFixed(2)"></span></div>
                        <div class="flex justify-between font-bold text-slate-900 text-base pt-2 border-t border-slate-100">
                            <span>Total</span><span x-text="'$' + (drawer.order?.total || 0).toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-slate-500" x-show="(drawer.order?.cashPaid || 0) > 0">
                            <span>Cash Paid</span><span class="font-semibold text-slate-700" x-text="'$' + (drawer.order?.cashPaid || 0).toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-emerald-700 font-semibold" x-show="(drawer.order?.changeGiven || 0) > 0">
                            <span>Change Returned</span><span x-text="'$' + (drawer.order?.changeGiven || 0).toFixed(2)"></span>
                        </div>
                    </div>
                </div>

                {{-- ── REFUND DETAILS (if refunded) ── --}}
                <div x-show="drawer.order?.status === 'refunded' && drawer.order?.refundDetails"
                    class="card border-rose-200" x-cloak>
                    <div class="card-header bg-rose-50/50">
                        <h3 class="text-xs font-semibold text-rose-600 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                            Refund Details
                        </h3>
                    </div>
                    <div class="card-body space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Refund Amount</span>
                            <span class="font-bold text-rose-700" x-text="'$' + (drawer.order?.refundDetails?.amount || 0).toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Reason</span>
                            <span class="font-medium text-slate-700 capitalize" x-text="(drawer.order?.refundDetails?.reason || '').replace(/_/g,' ')"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Processed By</span>
                            <span class="font-medium text-slate-700" x-text="drawer.order?.refundDetails?.processedBy"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Date</span>
                            <span class="font-medium text-slate-700" x-text="drawer.order?.refundDetails?.date"></span>
                        </div>
                        <div x-show="drawer.order?.refundDetails?.notes"
                            class="text-xs text-slate-500 bg-slate-50 border border-slate-100 rounded-lg px-3 py-2 italic"
                            x-text="drawer.order?.refundDetails?.notes"></div>
                    </div>
                </div>

            </div>{{-- /drawer body --}}
        </div>{{-- /drawer panel --}}
    </div>{{-- /drawer --}}

</div>

{{-- ================================================================ --}}
{{-- ALPINE.JS COMPONENT                                               --}}
{{-- ================================================================ --}}
<script>
function ordersPage() {

    /* ── Helpers ─────────────────────────────────────────────── */
    function buildTimeline(status, datetime) {
        const base = datetime.split(' · ')[0];
        const time = datetime.split(' · ')[1] || '—';
        const isCompleted = status === 'completed' || status === 'refunded';
        const isRefunded  = status === 'refunded';
        return [
            { key: 'created',   label: 'Order Created',   done: true,         time: time,                       note: 'Order placed at ' + time },
            { key: 'paid',      label: 'Payment Received', done: true,         time: time,                       note: 'Payment confirmed' },
            { key: 'completed', label: 'Completed',        done: isCompleted,  time: isCompleted ? time : null,  note: isCompleted ? 'Order fulfilled' : null },
            { key: 'refunded',  label: 'Refunded',         done: isRefunded,   time: isRefunded  ? time : null,  note: isRefunded  ? 'Refund processed' : null },
        ].filter(e => e.key !== 'refunded' || isRefunded);
    }

    const avatarColors = [
        'bg-blue-100 text-blue-700', 'bg-violet-100 text-violet-700',
        'bg-emerald-100 text-emerald-700', 'bg-rose-100 text-rose-700',
        'bg-amber-100 text-amber-700', 'bg-cyan-100 text-cyan-700',
    ];

    const staffList = [
        { name: 'John Doe',   role: 'admin',   color: 'bg-violet-100 text-violet-700' },
        { name: 'Jane Smith', role: 'manager', color: 'bg-blue-100 text-blue-700'    },
        { name: 'Mark Wilson',role: 'cashier', color: 'bg-emerald-100 text-emerald-700' },
    ];

    const lineItemSets = [
        [
            { id:1, name:'Wireless Headphones', emoji:'🎧', qty:1, price:89.99, sku:'WH-001' },
            { id:2, name:'USB-C Hub 7-in-1',    emoji:'🔌', qty:2, price:19.25, sku:'UH-003' },
        ],
        [
            { id:3, name:'Mechanical Keyboard', emoji:'⌨️', qty:1, price:49.99, sku:'MK-002' },
        ],
        [
            { id:4, name:'Laptop Stand',   emoji:'💻', qty:2, price:39.99, sku:'LS-004' },
            { id:5, name:'HDMI Cable 2m',  emoji:'📺', qty:3, price:12.99, sku:'HC-008' },
            { id:6, name:'Mouse Pad XL',   emoji:'🖱️', qty:1, price:24.99, sku:'MP-006' },
        ],
    ];

    const rawOrders = [
        { id:1042, customer:'Sarah Evans',  avatarColor:avatarColors[0], datetime:'Apr 21, 2025 · 10:02 AM', items:3, payment:'card', total:128.50, status:'completed', discountPct:0,  cashPaid:0,      changeGiven:0, staffIdx:0, lineIdx:0 },
        { id:1041, customer:'Mike Johnson', avatarColor:avatarColors[1], datetime:'Apr 21, 2025 · 09:48 AM', items:1, payment:'cash', total:49.99,  status:'pending',   discountPct:0,  cashPaid:60,     changeGiven:10.01, staffIdx:2, lineIdx:1 },
        { id:1040, customer:'Anna Lee',     avatarColor:avatarColors[2], datetime:'Apr 21, 2025 · 09:27 AM', items:5, payment:'card', total:312.00, status:'completed', discountPct:20, cashPaid:0,      changeGiven:0, staffIdx:1, lineIdx:2 },
        { id:1039, customer:'David Kim',    avatarColor:avatarColors[3], datetime:'Apr 21, 2025 · 08:55 AM', items:2, payment:'cash', total:84.20,  status:'refunded',  discountPct:0,  cashPaid:100,    changeGiven:15.80, staffIdx:2, lineIdx:0,
          refundDetails:{ amount:84.20, reason:'customer_request', processedBy:'Jane Smith', date:'Apr 21, 2025 · 09:10 AM', notes:'Customer changed their mind.' }
        },
        { id:1038, customer:'Lisa Park',    avatarColor:avatarColors[4], datetime:'Apr 21, 2025 · 08:10 AM', items:4, payment:'card', total:205.60, status:'completed', discountPct:15, cashPaid:0,      changeGiven:0, staffIdx:0, lineIdx:2 },
        { id:1037, customer:'Tom Brown',    avatarColor:avatarColors[5], datetime:'Apr 20, 2025 · 05:30 PM', items:2, payment:'cash', total:67.00,  status:'cancelled', discountPct:0,  cashPaid:0,      changeGiven:0, staffIdx:1, lineIdx:1 },
    ];

    const allOrders = rawOrders.map(o => {
        const st = staffList[o.staffIdx];
        const tax        = parseFloat((o.total * 0.077).toFixed(2));
        const discountAmt= parseFloat((o.total * o.discountPct / 100).toFixed(2));
        const subtotal   = parseFloat((o.total - tax + discountAmt).toFixed(2));
        return {
            ...o,
            staff:      st.name,
            staffRole:  st.role,
            staffColor: st.color,
            lineItems:  lineItemSets[o.lineIdx],
            subtotal,
            tax,
            discountAmt,
            timeline:   buildTimeline(o.status, o.datetime),
        };
    });

    return {
        /* ── State ─────────────────────────────────────────── */
        search:        '',
        filterStatus:  '',
        filterPayment: '',
        filtered:      [],
        allOrders,

        drawer: {
            open:        false,
            order:       null,
            copied:      false,
            refundPanel: false,
            refundForm:  { amount: 0, reason: '', notes: '' },
        },

        /* ── Computed ─────────────────────────────────────── */
        get drawerIndex() {
            if (!this.drawer.order) return -1;
            return this.filtered.findIndex(o => o.id === this.drawer.order.id);
        },

        /* ── Init ─────────────────────────────────────────── */
        init() { this.filter(); },

        /* ── Filter ───────────────────────────────────────── */
        filter() {
            let list = [...this.allOrders];
            if (this.filterStatus)  list = list.filter(o => o.status  === this.filterStatus);
            if (this.filterPayment) list = list.filter(o => o.payment === this.filterPayment);
            if (this.search.trim()) {
                const q = this.search.toLowerCase();
                list = list.filter(o => String(o.id).includes(q) || o.customer.toLowerCase().includes(q));
            }
            this.filtered = list;
        },

        /* ── Drawer ───────────────────────────────────────── */
        openDrawer(order) {
            this.drawer.order       = order;
            this.drawer.open        = true;
            this.drawer.refundPanel = false;
            this.drawer.copied      = false;
            this.drawer.refundForm  = { amount: order.total, reason: '', notes: '' };
        },

        prevOrder() {
            const i = this.drawerIndex;
            if (i > 0) this.openDrawer(this.filtered[i - 1]);
        },

        nextOrder() {
            const i = this.drawerIndex;
            if (i < this.filtered.length - 1) this.openDrawer(this.filtered[i + 1]);
        },

        /* ── Copy order ID ────────────────────────────────── */
        copyOrderId() {
            if (!this.drawer.order) return;
            navigator.clipboard?.writeText('#' + this.drawer.order.id).catch(() => {});
            this.drawer.copied = true;
            setTimeout(() => { this.drawer.copied = false; }, 2000);
        },

        /* ── Status update ────────────────────────────────── */
        updateStatus(newStatus) {
            if (!this.drawer.order) return;
            const idx = this.allOrders.findIndex(o => o.id === this.drawer.order.id);
            if (idx > -1) {
                this.allOrders[idx].status = newStatus;
                this.allOrders[idx].timeline = buildTimeline(newStatus, this.allOrders[idx].datetime);
                this.drawer.order = { ...this.allOrders[idx] };
            }
            this.filter();
            window.dispatchEvent(new CustomEvent('show-toast', {
                detail: { message: 'Order #' + this.drawer.order.id + ' marked as ' + newStatus, type: 'success' }
            }));
        },

        /* ── Refund ───────────────────────────────────────── */
        processRefund() {
            if (!this.drawer.refundForm.amount || !this.drawer.refundForm.reason) return;
            const idx = this.allOrders.findIndex(o => o.id === this.drawer.order.id);
            if (idx > -1) {
                this.allOrders[idx].status = 'refunded';
                this.allOrders[idx].refundDetails = {
                    amount:      this.drawer.refundForm.amount,
                    reason:      this.drawer.refundForm.reason,
                    notes:       this.drawer.refundForm.notes,
                    processedBy: 'John Doe',
                    date:        new Date().toLocaleString('en-US', { month:'short', day:'numeric', year:'numeric', hour:'2-digit', minute:'2-digit' }),
                };
                this.allOrders[idx].timeline = buildTimeline('refunded', this.allOrders[idx].datetime);
                this.drawer.order       = { ...this.allOrders[idx] };
                this.drawer.refundPanel = false;
            }
            this.filter();
            window.dispatchEvent(new CustomEvent('show-toast', {
                detail: { message: 'Refund of $' + parseFloat(this.drawer.refundForm.amount).toFixed(2) + ' processed.', type: 'success' }
            }));
        },
    };
}
</script>

</x-layouts.admin>
