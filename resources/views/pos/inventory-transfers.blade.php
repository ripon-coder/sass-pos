<x-layouts.admin pageTitle="Stock Transfers">

<div class="p-6 lg:p-8 space-y-6" x-data="transfersPage()" x-init="init()">

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <div class="flex items-center gap-2.5 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200/80">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Branch Inventory
                </span>
                <span class="text-[11px] text-slate-400 font-medium" x-text="'📍 ' + branchName"></span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Stock Transfers</h2>
            <p class="text-[13px] text-slate-400 mt-0.5">Move inventory between branches. Transfers are logged and require confirmation on arrival.</p>
        </div>
        <button @click="openNewModal()" class="btn btn-primary gap-2" id="new-transfer-btn">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
            New Transfer
        </button>
    </div>

    {{-- TABS -- Sent / Received / All --}}
    <div class="flex items-center gap-1 bg-slate-100 rounded-lg p-1 w-fit">
        <template x-for="tab in ['All','Outgoing','Incoming']" :key="tab">
            <button
                @click="activeTab = tab"
                class="px-4 py-1.5 rounded-md text-[13px] font-medium transition-all"
                :class="activeTab === tab
                    ? 'bg-white text-slate-800 shadow-sm'
                    : 'text-slate-500 hover:text-slate-700'"
                x-text="tab"
                :id="'tab-' + tab.toLowerCase()"
            ></button>
        </template>
    </div>

    {{-- SEARCH + STATUS FILTER --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 max-w-xs">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            <input type="search" x-model="search" placeholder="Search product, branch…" class="form-input pl-9" id="transfers-search">
        </div>
        <select x-model="filterStatus" class="form-input form-select w-36">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="in-transit">In Transit</option>
            <option value="received">Received</option>
            <option value="cancelled">Cancelled</option>
        </select>
        <span class="text-xs text-slate-400 ml-auto" x-text="filtered.length + ' transfers'"></span>
    </div>

    {{-- TRANSFERS TABLE --}}
    <div class="card">
        <div class="table-wrapper">
            <table class="table" id="transfers-table">
                <thead>
                    <tr>
                        <th>Ref #</th>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr x-show="filtered.length === 0">
                        <td colspan="8">
                            <div class="flex flex-col items-center justify-center py-16 text-center">
                                <svg class="w-10 h-10 text-slate-200 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
                                <p class="text-sm text-slate-400">No transfers found</p>
                            </div>
                        </td>
                    </tr>
                    <template x-for="t in filtered" :key="t.id">
                        <tr>
                            <td><span class="font-mono text-[11px] text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded" x-text="'TRF-' + String(t.id).padStart(4,'0')"></span></td>
                            <td class="text-[12px] text-slate-400" x-text="t.date"></td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span x-text="t.emoji"></span>
                                    <div>
                                        <p class="text-[13px] font-medium text-slate-700" x-text="t.product"></p>
                                        <p class="text-[10px] text-slate-400 font-mono" x-text="t.sku"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="font-semibold text-slate-800" x-text="t.qty"></td>
                            <td>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full flex-shrink-0" :style="'background:' + t.fromColor" title="From branch"></span>
                                    <span class="text-[12px] text-slate-600" x-text="t.from"></span>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full flex-shrink-0" :style="'background:' + t.toColor" title="To branch"></span>
                                    <span class="text-[12px] text-slate-600" x-text="t.to"></span>
                                </div>
                            </td>
                            <td>
                                <span class="badge"
                                    :class="{
                                        'badge-warning': t.status === 'pending',
                                        'badge-info':    t.status === 'in-transit',
                                        'badge-success': t.status === 'received',
                                        'badge-danger':  t.status === 'cancelled',
                                    }"
                                    x-text="t.status.split('-').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')"
                                ></span>
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <template x-if="t.status === 'pending'">
                                        <button @click="markInTransit(t)" class="btn btn-ghost btn-sm text-[11px] text-blue-600 hover:bg-blue-50">Ship</button>
                                    </template>
                                    <template x-if="t.status === 'in-transit' && t.to === branchName">
                                        <button @click="markReceived(t)" class="btn btn-ghost btn-sm text-[11px] text-emerald-600 hover:bg-emerald-50" :id="'receive-' + t.id">Confirm Receipt</button>
                                    </template>
                                    <template x-if="t.status === 'pending'">
                                        <button @click="cancelTransfer(t)" class="btn btn-ghost btn-sm text-[11px] text-red-500 hover:bg-red-50">Cancel</button>
                                    </template>
                                    <template x-if="t.status === 'received' || t.status === 'cancelled'">
                                        <span class="text-[11px] text-slate-400">—</span>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- NEW TRANSFER MODAL --}}
    <div x-show="newModal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/30" @click="newModal.open = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            id="new-transfer-modal"
        >
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <h2 class="text-base font-semibold text-slate-800">Create Transfer</h2>
                <button @click="newModal.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form @submit.prevent="saveTransfer()" class="p-6 space-y-4">
                <div>
                    <label class="form-label">Product <span class="text-red-500">*</span></label>
                    <select x-model="newModal.form.sku" class="form-input form-select" required>
                        <option value="">Select product…</option>
                        <template x-for="p in products" :key="p.sku">
                            <option :value="p.sku" x-text="p.name + ' (' + p.sku + ') — ' + p.qty + ' available'"></option>
                        </template>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label">From Branch</label>
                        <input type="text" :value="branchName" class="form-input bg-slate-50 text-slate-500" readonly>
                    </div>
                    <div>
                        <label class="form-label">To Branch <span class="text-red-500">*</span></label>
                        <select x-model="newModal.form.toBranch" class="form-input form-select" required>
                            <option value="">Select…</option>
                            <template x-for="b in otherBranches" :key="b.name">
                                <option :value="b.name" x-text="b.name"></option>
                            </template>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="form-label">Quantity <span class="text-red-500">*</span></label>
                    <input type="number" x-model.number="newModal.form.qty" class="form-input" min="1" id="transfer-qty-input" required>
                </div>
                <div>
                    <label class="form-label">Notes</label>
                    <textarea x-model="newModal.form.notes" class="form-input" rows="2" placeholder="Optional transfer notes…"></textarea>
                </div>

                {{-- Preview --}}
                <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 text-[12px] text-slate-600" x-show="newModal.form.sku && newModal.form.toBranch">
                    <p>
                        Transferring <strong x-text="newModal.form.qty"></strong> unit(s) of
                        <strong x-text="products.find(p=>p.sku===newModal.form.sku)?.name ?? ''"></strong>
                        from <strong x-text="branchName"></strong> → <strong x-text="newModal.form.toBranch"></strong>
                    </p>
                </div>

                <div class="flex gap-3 pt-1">
                    <button type="button" @click="newModal.open = false" class="flex-1 btn btn-secondary">Cancel</button>
                    <button type="submit" class="flex-1 btn btn-primary" id="save-transfer-btn">Request Transfer</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function transfersPage() {
    return {
        branchName: localStorage.getItem('posBranch') ? (JSON.parse(localStorage.getItem('posBranch'))?.name ?? 'Acme Store') : 'Acme Store',
        activeTab: 'All',
        search: '',
        filterStatus: '',
        newModal: { open: false, form: { sku: '', toBranch: '', qty: 1, notes: '' } },

        otherBranches: [
            { name: 'Beta Market',  color: '#8b5cf6' },
            { name: 'Gamma Outlet', color: '#10b981' },
        ],

        products: [
            { sku: 'ELEC-001', name: 'Wireless Headphones', emoji: '🎧', qty: 12 },
            { sku: 'PERI-001', name: 'Mechanical Keyboard',  emoji: '⌨️', qty: 3  },
            { sku: 'ACCS-001', name: 'USB-C Hub 7-in-1',     emoji: '🔌', qty: 25 },
            { sku: 'CABL-001', name: 'HDMI Cable 2m',         emoji: '📺', qty: 40 },
        ],

        transfers: [
            { id:1, date:'Apr 22, 08:30', product:'Wireless Headphones', sku:'ELEC-001', emoji:'🎧', qty:5, from:'Acme Store', fromColor:'#3b82f6', to:'Beta Market',  toColor:'#8b5cf6', status:'in-transit' },
            { id:2, date:'Apr 21, 14:00', product:'HDMI Cable 2m',        sku:'CABL-001', emoji:'📺', qty:10,from:'Gamma Outlet',fromColor:'#10b981',to:'Acme Store', toColor:'#3b82f6', status:'received'   },
            { id:3, date:'Apr 20, 11:15', product:'USB-C Hub 7-in-1',     sku:'ACCS-001', emoji:'🔌', qty:3, from:'Acme Store', fromColor:'#3b82f6', to:'Gamma Outlet',toColor:'#10b981', status:'pending'    },
            { id:4, date:'Apr 19, 09:45', product:'Mechanical Keyboard',  sku:'PERI-001', emoji:'⌨️', qty:2, from:'Beta Market',fromColor:'#8b5cf6', to:'Acme Store', toColor:'#3b82f6', status:'received'   },
            { id:5, date:'Apr 18, 16:00', product:'Wireless Headphones', sku:'ELEC-001', emoji:'🎧', qty:3, from:'Acme Store', fromColor:'#3b82f6', to:'Beta Market',  toColor:'#8b5cf6', status:'cancelled'  },
        ],

        get filtered() {
            return this.transfers.filter(t => {
                const q = this.search.toLowerCase();
                const matchQ = !q || t.product.toLowerCase().includes(q) || t.from.toLowerCase().includes(q) || t.to.toLowerCase().includes(q);
                const matchStatus = !this.filterStatus || t.status === this.filterStatus;
                const matchTab = this.activeTab === 'All'
                    || (this.activeTab === 'Outgoing' && t.from === this.branchName)
                    || (this.activeTab === 'Incoming' && t.to   === this.branchName);
                return matchQ && matchStatus && matchTab;
            });
        },

        init() {
            window.addEventListener('context-changed', (e) => {
                this.branchName = e.detail.branch?.name ?? 'Acme Store';
            });
        },

        markInTransit(t) {
            t.status = 'in-transit';
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'Transfer marked In Transit.', type: 'info' } }));
        },

        markReceived(t) {
            t.status = 'received';
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'Transfer confirmed as Received ✓', type: 'success' } }));
        },

        cancelTransfer(t) {
            t.status = 'cancelled';
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'Transfer cancelled.', type: 'warning' } }));
        },

        openNewModal() {
            this.newModal.form = { sku: '', toBranch: '', qty: 1, notes: '' };
            this.newModal.open = true;
        },

        saveTransfer() {
            const prod = this.products.find(p => p.sku === this.newModal.form.sku);
            if (!prod) return;
            this.transfers.unshift({
                id: Date.now() % 9999,
                date: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }),
                product: prod.name, sku: prod.sku, emoji: prod.emoji,
                qty: this.newModal.form.qty,
                from: this.branchName, fromColor: '#3b82f6',
                to: this.newModal.form.toBranch,
                toColor: this.otherBranches.find(b => b.name === this.newModal.form.toBranch)?.color ?? '#64748b',
                status: 'pending',
            });
            prod.qty = Math.max(0, prod.qty - this.newModal.form.qty);
            this.newModal.open = false;
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'Transfer requested successfully.', type: 'success' } }));
        },
    };
}
</script>

</x-layouts.admin>
