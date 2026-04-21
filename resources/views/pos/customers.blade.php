<x-layouts.admin pageTitle="Customers">

<div class="p-6 space-y-5" x-data="customersPage()" x-init="init()">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Customers</h2>
            <p class="text-sm text-slate-500 mt-0.5">Manage your customer database</p>
        </div>
        <button @click="openModal(null)" class="btn btn-primary" id="add-customer-btn">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
            </svg>
            Add Customer
        </button>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 max-w-xs">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            <input type="search" x-model="search" @input="filter()" placeholder="Search by name, email, or phone…" class="form-input pl-9" id="customers-search">
        </div>
        <span class="text-xs text-slate-400 ml-auto" x-text="filtered.length + ' customers'"></span>
    </div>

    {{-- Customers Table --}}
    <div class="card">
        <div class="table-wrapper">
            <table class="table" id="customers-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Total Orders</th>
                        <th>Total Spent</th>
                        <th>Last Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Empty State --}}
                    <tr x-show="filtered.length === 0" x-cloak>
                        <td colspan="7">
                            <div class="flex flex-col items-center justify-center py-14 text-center">
                                <svg class="w-12 h-12 text-slate-200 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                                <p class="text-sm font-medium text-slate-500">No customers found</p>
                                <p class="text-xs text-slate-400 mt-1">Try adjusting your search or add a new customer</p>
                            </div>
                        </td>
                    </tr>

                    <template x-for="c in filtered" :key="c.id">
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold flex-shrink-0"
                                        :class="c.avatarColor"
                                        x-text="c.name.split(' ').map(n => n[0]).join('')"
                                    ></div>
                                    <div>
                                        <p class="font-medium text-slate-700" x-text="c.name"></p>
                                        <p class="text-xs text-slate-400" x-text="'Customer since ' + c.since"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-slate-500" x-text="c.email"></td>
                            <td class="text-slate-500" x-text="c.phone"></td>
                            <td>
                                <span class="font-medium text-slate-700" x-text="c.totalOrders"></span>
                            </td>
                            <td class="font-semibold text-slate-800" x-text="'$' + c.totalSpent.toLocaleString('en-US', {minimumFractionDigits:2})"></td>
                            <td class="text-slate-400 text-xs" x-text="c.lastOrder"></td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <button @click="viewHistory(c)" class="btn btn-ghost btn-sm text-blue-600 hover:text-blue-700 whitespace-nowrap">History</button>
                                    <button @click="openModal(c)" class="btn btn-ghost btn-sm btn-icon text-slate-400 hover:text-blue-600" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== ADD / EDIT CUSTOMER MODAL ===== --}}
    <div x-show="modal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/30" @click="modal.open = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-lg"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            id="customer-modal"
        >
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <h2 class="text-base font-semibold text-slate-800" x-text="modal.editing ? 'Edit Customer' : 'Add Customer'"></h2>
                <button @click="modal.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form @submit.prevent="saveCustomer()" class="p-6 space-y-4">
                <div>
                    <label class="form-label" for="customer-name-input">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" id="customer-name-input" x-model="modal.form.name" class="form-input" placeholder="e.g. John Doe" required>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label" for="customer-email-input">Email</label>
                        <input type="email" id="customer-email-input" x-model="modal.form.email" class="form-input" placeholder="john@example.com">
                    </div>
                    <div>
                        <label class="form-label" for="customer-phone-input">Phone</label>
                        <input type="tel" id="customer-phone-input" x-model="modal.form.phone" class="form-input" placeholder="+1 (555) 000-0000">
                    </div>
                </div>
                <div>
                    <label class="form-label" for="customer-address-input">Address</label>
                    <textarea id="customer-address-input" x-model="modal.form.address" class="form-input" rows="2" placeholder="Street address, city, state…"></textarea>
                </div>
                <div>
                    <label class="form-label" for="customer-notes-input">Notes</label>
                    <textarea id="customer-notes-input" x-model="modal.form.notes" class="form-input" rows="2" placeholder="Internal notes…"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="modal.open = false" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="save-customer-btn" x-text="modal.editing ? 'Save Changes' : 'Add Customer'"></button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== PURCHASE HISTORY DRAWER ===== --}}
    <div x-show="historyDrawer.open" class="fixed inset-0 z-50 flex" x-cloak>
        <div class="absolute inset-0 bg-black/30" @click="historyDrawer.open = false"></div>
        <div
            class="absolute right-0 top-0 bottom-0 w-full max-w-md bg-white shadow-xl flex flex-col"
            x-transition:enter="transition ease-out duration-250" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"  x-transition:leave-start="translate-x-0"    x-transition:leave-end="translate-x-full"
            id="customer-history-drawer"
        >
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Purchase History</h2>
                    <p class="text-xs text-slate-500 mt-0.5" x-text="historyDrawer.customer?.name"></p>
                </div>
                <button @click="historyDrawer.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-6 space-y-4">

                {{-- Customer Summary --}}
                <div class="grid grid-cols-3 gap-3">
                    <div class="stat-card py-3 px-3 text-center">
                        <p class="text-[11px] font-medium text-slate-400 uppercase">Orders</p>
                        <p class="text-lg font-bold text-slate-800 mt-1" x-text="historyDrawer.customer?.totalOrders"></p>
                    </div>
                    <div class="stat-card py-3 px-3 text-center">
                        <p class="text-[11px] font-medium text-slate-400 uppercase">Spent</p>
                        <p class="text-lg font-bold text-slate-800 mt-1" x-text="'$' + (historyDrawer.customer?.totalSpent || 0).toLocaleString('en-US', {minimumFractionDigits:2})"></p>
                    </div>
                    <div class="stat-card py-3 px-3 text-center">
                        <p class="text-[11px] font-medium text-slate-400 uppercase">Avg Order</p>
                        <p class="text-lg font-bold text-slate-800 mt-1" x-text="'$' + (historyDrawer.customer?.totalOrders ? (historyDrawer.customer.totalSpent / historyDrawer.customer.totalOrders).toFixed(2) : '0.00')"></p>
                    </div>
                </div>

                {{-- History List --}}
                <div class="card">
                    <div class="card-header">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Recent Orders</p>
                    </div>
                    <div class="divide-y divide-slate-50">
                        <template x-for="order in historyDrawer.orders" :key="order.id">
                            <div class="flex items-center justify-between px-4 py-3">
                                <div>
                                    <p class="text-sm font-medium text-slate-700" x-text="'Order #' + order.id"></p>
                                    <p class="text-xs text-slate-400 mt-0.5" x-text="order.date"></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-slate-800" x-text="'$' + order.total.toFixed(2)"></p>
                                    <span class="badge mt-0.5"
                                        :class="order.status === 'completed' ? 'badge-success' : (order.status === 'refunded' ? 'badge-danger' : 'badge-warning')"
                                        x-text="order.status.charAt(0).toUpperCase() + order.status.slice(1)"
                                    ></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function customersPage() {
    const colors = [
        'bg-blue-100 text-blue-600', 'bg-violet-100 text-violet-600', 'bg-emerald-100 text-emerald-600',
        'bg-rose-100 text-rose-600', 'bg-amber-100 text-amber-600', 'bg-cyan-100 text-cyan-600',
    ];
    return {
        search: '',
        modal: { open: false, editing: false, form: {} },
        historyDrawer: { open: false, customer: null, orders: [] },
        allCustomers: [
            { id: 1, name: 'Sarah Evans',  email: 'sarah@email.com',  phone: '+1 (555) 123-4567', totalOrders: 24, totalSpent: 3480.50,  since: 'Jan 2024', lastOrder: 'Apr 21, 2025', avatarColor: colors[0], address: '123 Main St, New York', notes: '' },
            { id: 2, name: 'Mike Johnson', email: 'mike@email.com',   phone: '+1 (555) 234-5678', totalOrders: 12, totalSpent: 1560.00,  since: 'Mar 2024', lastOrder: 'Apr 21, 2025', avatarColor: colors[1], address: '', notes: 'VIP customer' },
            { id: 3, name: 'Anna Lee',     email: 'anna@email.com',   phone: '+1 (555) 345-6789', totalOrders: 31, totalSpent: 5820.30,  since: 'Nov 2023', lastOrder: 'Apr 21, 2025', avatarColor: colors[2], address: '456 Oak Ave, LA', notes: '' },
            { id: 4, name: 'David Kim',    email: 'david@email.com',  phone: '+1 (555) 456-7890', totalOrders: 8,  totalSpent: 920.80,   since: 'Jun 2024', lastOrder: 'Apr 21, 2025', avatarColor: colors[3], address: '', notes: '' },
            { id: 5, name: 'Lisa Park',    email: 'lisa@email.com',   phone: '+1 (555) 567-8901', totalOrders: 19, totalSpent: 2740.00,  since: 'Feb 2024', lastOrder: 'Apr 20, 2025', avatarColor: colors[4], address: '789 Pine Rd, Chicago', notes: 'Prefers card payment' },
            { id: 6, name: 'Tom Brown',    email: 'tom@email.com',    phone: '+1 (555) 678-9012', totalOrders: 5,  totalSpent: 445.50,   since: 'Sep 2024', lastOrder: 'Apr 20, 2025', avatarColor: colors[5], address: '', notes: '' },
        ],
        filtered: [],

        init() { this.filter(); },

        filter() {
            let list = [...this.allCustomers];
            if (this.search.trim()) {
                const q = this.search.toLowerCase();
                list = list.filter(c => c.name.toLowerCase().includes(q) || c.email.toLowerCase().includes(q) || c.phone.includes(q));
            }
            this.filtered = list;
        },

        openModal(customer) {
            if (customer) {
                this.modal.editing = true;
                this.modal.form = { ...customer };
            } else {
                this.modal.editing = false;
                this.modal.form = { id: null, name: '', email: '', phone: '', address: '', notes: '', avatarColor: colors[Math.floor(Math.random() * colors.length)] };
            }
            this.modal.open = true;
        },

        saveCustomer() {
            if (this.modal.editing) {
                const idx = this.allCustomers.findIndex(c => c.id === this.modal.form.id);
                if (idx > -1) this.allCustomers[idx] = { ...this.allCustomers[idx], ...this.modal.form };
            } else {
                this.allCustomers.push({
                    ...this.modal.form,
                    id: Date.now(),
                    totalOrders: 0,
                    totalSpent: 0,
                    since: new Date().toLocaleDateString('en-US', { month: 'short', year: 'numeric' }),
                    lastOrder: '—',
                });
            }
            this.modal.open = false;
            this.filter();
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: this.modal.editing ? 'Customer updated.' : 'Customer added.', type: 'success' } }));
        },

        viewHistory(customer) {
            this.historyDrawer.customer = customer;
            this.historyDrawer.orders = [
                { id: 1042, date: 'Apr 21, 2025', total: 128.50, status: 'completed' },
                { id: 1035, date: 'Apr 19, 2025', total: 89.99,  status: 'completed' },
                { id: 1028, date: 'Apr 15, 2025', total: 245.00, status: 'completed' },
                { id: 1019, date: 'Apr 10, 2025', total: 54.99,  status: 'refunded'  },
                { id: 1011, date: 'Apr 05, 2025', total: 179.00, status: 'completed' },
            ];
            this.historyDrawer.open = true;
        },
    };
}
</script>

</x-layouts.admin>
