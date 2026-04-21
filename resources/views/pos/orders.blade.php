<x-layouts.admin pageTitle="Orders">

<div class="p-6 space-y-5" x-data="ordersPage()" x-init="init()">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Orders</h2>
            <p class="text-sm text-slate-500 mt-0.5">View and manage all sales transactions</p>
        </div>
        <div class="flex items-center gap-2">
            <button class="btn btn-secondary gap-1.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                Export
            </button>
        </div>
    </div>

    {{-- Filters Row --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 max-w-xs">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
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

    {{-- Orders Table --}}
    <div class="card">
        <div class="table-wrapper">
            <table class="table" id="orders-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Date & Time</th>
                        <th>Items</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr x-show="filtered.length === 0" x-cloak>
                        <td colspan="8">
                            <div class="flex flex-col items-center justify-center py-14 text-center">
                                <svg class="w-12 h-12 text-slate-200 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                                <p class="text-sm font-medium text-slate-500">No orders found</p>
                                <p class="text-xs text-slate-400 mt-1">Try adjusting your search or filters</p>
                            </div>
                        </td>
                    </tr>
                    <template x-for="o in filtered" :key="o.id">
                        <tr>
                            <td><span class="font-mono text-sm text-slate-600" x-text="'#' + o.id"></span></td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 text-[10px] font-semibold"
                                        :class="o.avatarColor"
                                        x-text="o.customer.split(' ').map(n => n[0]).join('')"
                                    ></div>
                                    <span class="font-medium text-slate-700" x-text="o.customer"></span>
                                </div>
                            </td>
                            <td class="text-slate-500 text-xs" x-text="o.datetime"></td>
                            <td class="text-slate-500" x-text="o.items + ' item' + (o.items !== 1 ? 's' : '')"></td>
                            <td>
                                <div class="flex items-center gap-1.5">
                                    <svg x-show="o.payment === 'cash'" class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" /></svg>
                                    <svg x-show="o.payment === 'card'" class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
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
                            <td>
                                <button @click="openDrawer(o)" class="btn btn-ghost btn-sm text-blue-600 hover:text-blue-700 whitespace-nowrap">View →</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== ORDER DETAIL DRAWER ===== --}}
    <div x-show="drawer.open" class="fixed inset-0 z-50 flex" x-cloak>
        <div class="absolute inset-0 bg-black/30" @click="drawer.open = false"></div>
        <div
            class="absolute right-0 top-0 bottom-0 w-full max-w-md bg-white shadow-xl flex flex-col"
            x-transition:enter="transition ease-out duration-250"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            id="order-detail-drawer"
        >
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <div>
                    <h2 class="text-base font-semibold text-slate-800" x-text="'Order #' + drawer.order?.id"></h2>
                    <p class="text-xs text-slate-500 mt-0.5" x-text="drawer.order?.datetime"></p>
                </div>
                <button @click="drawer.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-5">
                {{-- Status + Actions --}}
                <div class="flex items-center justify-between">
                    <span class="badge text-sm px-3 py-1"
                        :class="{
                            'badge-success': drawer.order?.status === 'completed',
                            'badge-warning': drawer.order?.status === 'pending',
                            'badge-danger':  drawer.order?.status === 'refunded',
                            'badge-gray':    drawer.order?.status === 'cancelled',
                        }"
                        x-text="drawer.order?.status?.charAt(0).toUpperCase() + drawer.order?.status?.slice(1)"
                    ></span>
                    <div class="flex gap-2">
                        <button class="btn btn-secondary btn-sm gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a.75.75 0 01-.875.854 49.188 49.188 0 01-14.01 0 .75.75 0 01-.875-.854L2.34 18m15.32 0H6.34" /></svg>
                            Print
                        </button>
                        <button x-show="drawer.order?.status === 'completed'" class="btn btn-danger btn-sm gap-1.5">Refund</button>
                    </div>
                </div>

                {{-- Customer --}}
                <div class="card">
                    <div class="card-body">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Customer</p>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-semibold"
                                :class="drawer.order?.avatarColor"
                                x-text="drawer.order?.customer?.split(' ').map(n => n[0]).join('')"
                            ></div>
                            <div>
                                <p class="font-medium text-slate-800" x-text="drawer.order?.customer"></p>
                                <p class="text-xs text-slate-400">Returning customer</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Items --}}
                <div class="card">
                    <div class="card-header">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Items</p>
                        <span class="text-xs text-slate-400" x-text="drawer.order?.items + ' item' + (drawer.order?.items !== 1 ? 's' : '')"></span>
                    </div>
                    <div class="divide-y divide-slate-50">
                        <template x-for="item in (drawer.order?.lineItems || [])" :key="item.id">
                            <div class="flex items-center gap-3 px-4 py-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-lg" x-text="item.emoji"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-700 truncate" x-text="item.name"></p>
                                    <p class="text-xs text-slate-400" x-text="'x' + item.qty + ' @ $' + item.price.toFixed(2)"></p>
                                </div>
                                <span class="text-sm font-semibold text-slate-700" x-text="'$' + (item.qty * item.price).toFixed(2)"></span>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Totals --}}
                <div class="card">
                    <div class="card-body space-y-2 text-sm">
                        <div class="flex justify-between text-slate-500"><span>Subtotal</span><span x-text="'$' + (drawer.order?.total * 0.906).toFixed(2)"></span></div>
                        <div class="flex justify-between text-slate-500"><span>Tax (8.5%)</span><span x-text="'$' + (drawer.order?.total * 0.077).toFixed(2)"></span></div>
                        <div class="flex justify-between font-semibold text-slate-900 text-base pt-2 border-t border-slate-100">
                            <span>Total</span>
                            <span x-text="'$' + drawer.order?.total.toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-slate-500 text-xs">
                            <span>Payment Method</span>
                            <span class="capitalize font-medium" x-text="drawer.order?.payment"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function ordersPage() {
    const sample = [
        { id: 1042, customer: 'Sarah Evans',  avatarColor: 'bg-blue-100 text-blue-600',   datetime: 'Apr 21, 2025 · 10:02 AM', items: 3, payment: 'card',  total: 128.50, status: 'completed' },
        { id: 1041, customer: 'Mike Johnson', avatarColor: 'bg-violet-100 text-violet-600', datetime: 'Apr 21, 2025 · 09:48 AM', items: 1, payment: 'cash',  total: 49.99,  status: 'pending'   },
        { id: 1040, customer: 'Anna Lee',     avatarColor: 'bg-emerald-100 text-emerald-600', datetime: 'Apr 21, 2025 · 09:27 AM', items: 5, payment: 'card',  total: 312.00, status: 'completed' },
        { id: 1039, customer: 'David Kim',    avatarColor: 'bg-rose-100 text-rose-600',    datetime: 'Apr 21, 2025 · 08:55 AM', items: 2, payment: 'cash',  total: 84.20,  status: 'refunded'  },
        { id: 1038, customer: 'Lisa Park',    avatarColor: 'bg-amber-100 text-amber-600',  datetime: 'Apr 21, 2025 · 08:10 AM', items: 4, payment: 'card',  total: 205.60, status: 'completed' },
        { id: 1037, customer: 'Tom Brown',    avatarColor: 'bg-slate-100 text-slate-600',  datetime: 'Apr 20, 2025 · 05:30 PM', items: 2, payment: 'cash',  total: 67.00,  status: 'cancelled' },
    ];
    const lineItemSets = [
        [{ id:1, name:'Wireless Headphones', emoji:'🎧', qty:1, price:89.99 }, { id:2, name:'USB-C Hub', emoji:'🔌', qty:2, price:19.25 }],
        [{ id:3, name:'Mechanical Keyboard', emoji:'⌨️', qty:1, price:49.99 }],
        [{ id:4, name:'Laptop Stand', emoji:'💻', qty:2, price:39.99 }, { id:5, name:'HDMI Cable', emoji:'📺', qty:3, price:12.99 }],
    ];
    return {
        search: '',
        filterStatus: '',
        filterPayment: '',
        filtered: [],
        drawer: { open: false, order: null },
        allOrders: sample.map((o, i) => ({ ...o, lineItems: lineItemSets[i % lineItemSets.length] })),
        init() { this.filter(); },
        filter() {
            let list = [...this.allOrders];
            if (this.filterStatus)  list = list.filter(o => o.status   === this.filterStatus);
            if (this.filterPayment) list = list.filter(o => o.payment  === this.filterPayment);
            if (this.search.trim()) {
                const q = this.search.toLowerCase();
                list = list.filter(o => String(o.id).includes(q) || o.customer.toLowerCase().includes(q));
            }
            this.filtered = list;
        },
        openDrawer(order) {
            this.drawer.order = order;
            this.drawer.open = true;
        },
    };
}
</script>

</x-layouts.admin>
