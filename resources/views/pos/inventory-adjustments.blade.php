<x-layouts.admin pageTitle="Stock Adjustments">

<div class="p-6 lg:p-8 space-y-6" x-data="adjustmentsPage()" x-init="init()">

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
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Stock Adjustments</h2>
            <p class="text-[13px] text-slate-400 mt-0.5">Record manual stock corrections, write-offs, and cycle counts.</p>
        </div>
        <button @click="openNewModal()" class="btn btn-primary gap-2" id="new-adjustment-btn">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            New Adjustment
        </button>
    </div>

    {{-- ADJUSTMENT TYPES INFO --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        @foreach([
            ['Addition',   'bg-emerald-50 border-emerald-200', 'text-emerald-700', 'Received new stock, found items', '+'],
            ['Reduction',  'bg-red-50    border-red-200',      'text-red-700',     'Damaged, expired or pilfered',    '−'],
            ['Count',      'bg-violet-50 border-violet-200',   'text-violet-700',  'Cycle count / stock-take result',  '='],
        ] as [$type, $bg, $text, $desc, $sym])
        <div class="rounded-xl border {{ $bg }} px-4 py-3 flex items-center gap-3">
            <span class="text-xl font-black {{ $text }}">{{ $sym }}</span>
            <div>
                <p class="text-[12px] font-semibold {{ $text }}">{{ $type }}</p>
                <p class="text-[11px] text-slate-500">{{ $desc }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- FILTERS --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 max-w-xs">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            <input type="search" x-model="search" placeholder="Search product or reason…" class="form-input pl-9" id="adjustments-search">
        </div>
        <select x-model="filterType" class="form-input form-select w-36">
            <option value="">All Types</option>
            <option value="addition">Addition</option>
            <option value="reduction">Reduction</option>
            <option value="count">Count</option>
        </select>
        <span class="text-xs text-slate-400 ml-auto" x-text="filtered.length + ' records'"></span>
    </div>

    {{-- ADJUSTMENTS TABLE --}}
    <div class="card">
        <div class="table-wrapper">
            <table class="table" id="adjustments-table">
                <thead>
                    <tr>
                        <th>Date &amp; Time</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Before</th>
                        <th>Change</th>
                        <th>After</th>
                        <th>Reason</th>
                        <th>By</th>
                    </tr>
                </thead>
                <tbody>
                    <tr x-show="filtered.length === 0">
                        <td colspan="8">
                            <div class="flex flex-col items-center justify-center py-16 text-center">
                                <svg class="w-10 h-10 text-slate-200 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <p class="text-sm text-slate-400">No adjustments recorded yet</p>
                            </div>
                        </td>
                    </tr>
                    <template x-for="adj in filtered" :key="adj.id">
                        <tr>
                            <td class="text-[12px] text-slate-400 whitespace-nowrap" x-text="adj.date"></td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span x-text="adj.emoji"></span>
                                    <div>
                                        <p class="text-[13px] font-medium text-slate-700" x-text="adj.product"></p>
                                        <p class="text-[10px] text-slate-400 font-mono" x-text="adj.sku"></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge"
                                    :class="{
                                        'badge-success': adj.type === 'addition',
                                        'badge-danger':  adj.type === 'reduction',
                                        'badge-info':    adj.type === 'count'
                                    }"
                                    x-text="adj.type.charAt(0).toUpperCase() + adj.type.slice(1)"
                                ></span>
                            </td>
                            <td class="font-mono text-[13px] text-slate-600" x-text="adj.before"></td>
                            <td>
                                <span class="font-mono text-[13px] font-semibold"
                                    :class="adj.change > 0 ? 'text-emerald-600' : (adj.change < 0 ? 'text-red-500' : 'text-violet-600')"
                                    x-text="adj.change > 0 ? '+' + adj.change : (adj.change < 0 ? adj.change : '=' + adj.before)"
                                ></span>
                            </td>
                            <td class="font-mono text-[13px] font-semibold text-slate-800" x-text="adj.after"></td>
                            <td class="text-[12px] text-slate-500 max-w-[160px] truncate" x-text="adj.reason"></td>
                            <td>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-5 h-5 rounded-full bg-slate-200 flex items-center justify-center text-[9px] font-bold text-slate-600" x-text="adj.by.split(' ').map(n=>n[0]).join('')"></div>
                                    <span class="text-[12px] text-slate-500" x-text="adj.by"></span>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- NEW ADJUSTMENT MODAL --}}
    <div x-show="newModal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/30" @click="newModal.open = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            id="new-adjustment-modal"
        >
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <h2 class="text-base font-semibold text-slate-800">New Stock Adjustment</h2>
                <button @click="newModal.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form @submit.prevent="saveAdjustment()" class="p-6 space-y-4">
                <div>
                    <label class="form-label">Product <span class="text-red-500">*</span></label>
                    <select x-model="newModal.form.sku" class="form-input form-select" required>
                        <option value="">Select product…</option>
                        <template x-for="p in products" :key="p.sku">
                            <option :value="p.sku" x-text="p.name + ' (' + p.sku + ')'"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="form-label">Adjustment Type <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-3 gap-2 mt-1">
                        <template x-for="t in ['addition','reduction','count']" :key="t">
                            <button type="button"
                                @click="newModal.form.type = t"
                                class="px-3 py-2 rounded-lg border text-[12px] font-semibold capitalize transition-all"
                                :class="newModal.form.type === t
                                    ? (t==='addition' ? 'bg-emerald-500 text-white border-emerald-500' : t==='reduction' ? 'bg-red-500 text-white border-red-500' : 'bg-violet-500 text-white border-violet-500')
                                    : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300'"
                                x-text="t"
                            ></button>
                        </template>
                    </div>
                </div>
                <div>
                    <label class="form-label" x-text="newModal.form.type === 'count' ? 'New Counted Quantity' : 'Quantity'"></label>
                    <input type="number" x-model.number="newModal.form.qty" class="form-input" min="0" id="adj-qty-input" required>
                </div>
                <div>
                    <label class="form-label">Reason <span class="text-red-500">*</span></label>
                    <input type="text" x-model="newModal.form.reason" class="form-input" placeholder="e.g. Damaged in transit, Cycle count" required>
                </div>
                <div class="flex gap-3 pt-1">
                    <button type="button" @click="newModal.open = false" class="flex-1 btn btn-secondary">Cancel</button>
                    <button type="submit" class="flex-1 btn btn-primary" id="save-adjustment-btn">Record Adjustment</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function adjustmentsPage() {
    return {
        branchName: localStorage.getItem('posBranch') ? (JSON.parse(localStorage.getItem('posBranch'))?.name ?? 'Branch') : 'Branch',
        search: '',
        filterType: '',
        newModal: { open: false, form: { sku: '', type: 'addition', qty: 1, reason: '' } },

        products: [
            { sku: 'ELEC-001', name: 'Wireless Headphones', emoji: '🎧', qty: 12 },
            { sku: 'PERI-001', name: 'Mechanical Keyboard',  emoji: '⌨️', qty: 3  },
            { sku: 'ACCS-001', name: 'USB-C Hub 7-in-1',     emoji: '🔌', qty: 25 },
            { sku: 'ACCS-002', name: 'Laptop Stand',          emoji: '💻', qty: 4  },
            { sku: 'CABL-001', name: 'HDMI Cable 2m',         emoji: '📺', qty: 40 },
        ],

        adjustments: [
            { id:1,  date:'Apr 22, 09:14',  product:'Wireless Headphones', sku:'ELEC-001', emoji:'🎧', type:'addition',  before:8,   change:4,   after:12, reason:'New delivery received',       by:'John Doe'  },
            { id:2,  date:'Apr 21, 17:42',  product:'Laptop Stand',         sku:'ACCS-002', emoji:'💻', type:'reduction', before:6,   change:-2,  after:4,  reason:'Damaged — dropped in transit', by:'Jane Kim'  },
            { id:3,  date:'Apr 21, 11:00',  product:'Mechanical Keyboard',  sku:'PERI-001', emoji:'⌨️', type:'count',     before:5,   change:0,   after:3,  reason:'Cycle count — Q2 audit',       by:'John Doe'  },
            { id:4,  date:'Apr 20, 15:30',  product:'HDMI Cable 2m',        sku:'CABL-001', emoji:'📺', type:'addition',  before:32,  change:8,   after:40, reason:'Restocked from warehouse',     by:'Sam Park'  },
            { id:5,  date:'Apr 19, 10:05',  product:'USB-C Hub 7-in-1',     sku:'ACCS-001', emoji:'🔌', type:'reduction', before:28,  change:-3,  after:25, reason:'3 units returned damaged',     by:'Jane Kim'  },
        ],

        get filtered() {
            return this.adjustments.filter(a => {
                const q = this.search.toLowerCase();
                const matchQ = !q || a.product.toLowerCase().includes(q) || a.reason.toLowerCase().includes(q) || a.sku.toLowerCase().includes(q);
                const matchType = !this.filterType || a.type === this.filterType;
                return matchQ && matchType;
            });
        },

        init() {
            window.addEventListener('context-changed', (e) => {
                this.branchName = e.detail.branch?.name ?? 'Branch';
            });
        },

        openNewModal() {
            this.newModal.form = { sku: '', type: 'addition', qty: 1, reason: '' };
            this.newModal.open = true;
        },

        saveAdjustment() {
            const prod = this.products.find(p => p.sku === this.newModal.form.sku);
            if (!prod) return;
            const before = prod.qty;
            let change = this.newModal.form.qty;
            let after;
            if (this.newModal.form.type === 'addition')  { after = before + change; }
            else if (this.newModal.form.type === 'reduction') { change = -change; after = Math.max(0, before + change); }
            else { after = change; change = 0; } // count
            prod.qty = after;
            this.adjustments.unshift({
                id: Date.now(),
                date: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }),
                product: prod.name, sku: prod.sku, emoji: prod.emoji,
                type: this.newModal.form.type, before, change, after,
                reason: this.newModal.form.reason, by: 'John Doe',
            });
            this.newModal.open = false;
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'Adjustment recorded.', type: 'success' } }));
        },
    };
}
</script>

</x-layouts.admin>
