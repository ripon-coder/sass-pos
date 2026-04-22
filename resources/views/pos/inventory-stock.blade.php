<x-layouts.admin pageTitle="Stock Management">

<div class="p-6 lg:p-8 space-y-6" x-data="stockPage()" x-init="init()">

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
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Stock Management</h2>
            <p class="text-[13px] text-slate-400 mt-0.5">View and update stock levels for this branch.</p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="exportCSV()" class="btn btn-secondary gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12M12 16.5V3" /></svg>
                Export
            </button>
            <button @click="reorderModal.open = true" class="btn btn-primary gap-2" id="reorder-btn">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                Reorder Queue
                <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-red-500 text-white text-[9px] font-bold" x-text="lowStockItems.length" x-show="lowStockItems.length > 0"></span>
            </button>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card !p-4">
            <p class="stat-label">Total SKUs</p>
            <p class="text-2xl font-bold text-slate-900" x-text="items.length"></p>
        </div>
        <div class="stat-card !p-4">
            <p class="stat-label">In Stock</p>
            <p class="text-2xl font-bold text-emerald-600" x-text="items.filter(i=>i.qty>i.reorderAt).length"></p>
        </div>
        <div class="stat-card !p-4">
            <p class="stat-label">Low Stock</p>
            <p class="text-2xl font-bold text-amber-500" x-text="lowStockItems.length"></p>
        </div>
        <div class="stat-card !p-4">
            <p class="stat-label">Out of Stock</p>
            <p class="text-2xl font-bold text-red-500" x-text="items.filter(i=>i.qty===0).length"></p>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 max-w-xs">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            <input type="search" x-model="search" placeholder="Search SKU or name…" class="form-input pl-9" id="stock-search">
        </div>
        <select x-model="filterStatus" class="form-input form-select w-40">
            <option value="">All Status</option>
            <option value="ok">In Stock</option>
            <option value="low">Low Stock</option>
            <option value="out">Out of Stock</option>
        </select>
        <select x-model="filterCategory" class="form-input form-select w-40">
            <option value="">All Categories</option>
            <template x-for="cat in categories" :key="cat"><option x-text="cat" :value="cat"></option></template>
        </select>
        <span class="text-xs text-slate-400 ml-auto" x-text="filtered.length + ' items'"></span>
    </div>

    {{-- STOCK TABLE --}}
    <div class="card">
        <div class="table-wrapper">
            <table class="table" id="stock-table">
                <thead>
                    <tr>
                        <th>Product / SKU</th>
                        <th>Category</th>
                        <th>On Hand</th>
                        <th>Reserved</th>
                        <th>Available</th>
                        <th>Reorder At</th>
                        <th>Status</th>
                        <th>Quick Update</th>
                    </tr>
                </thead>
                <tbody>
                    <tr x-show="filtered.length === 0">
                        <td colspan="8">
                            <div class="flex flex-col items-center justify-center py-16 text-center">
                                <svg class="w-10 h-10 text-slate-200 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3" /></svg>
                                <p class="text-sm text-slate-400">No items match your filters</p>
                            </div>
                        </td>
                    </tr>
                    <template x-for="item in filtered" :key="item.id">
                        <tr>
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-base" x-text="item.emoji"></div>
                                    <div>
                                        <p class="font-medium text-slate-700 text-[13px]" x-text="item.name"></p>
                                        <p class="text-[11px] text-slate-400 font-mono" x-text="item.sku"></p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-gray" x-text="item.category"></span></td>
                            <td class="font-semibold text-slate-800" x-text="item.qty"></td>
                            <td class="text-slate-500" x-text="item.reserved"></td>
                            <td>
                                <span class="font-semibold" :class="(item.qty - item.reserved) <= 0 ? 'text-red-500' : 'text-slate-800'" x-text="Math.max(0, item.qty - item.reserved)"></span>
                            </td>
                            <td class="text-slate-500 text-[12px]" x-text="item.reorderAt"></td>
                            <td>
                                <span class="badge"
                                    :class="item.qty === 0 ? 'badge-danger' : (item.qty <= item.reorderAt ? 'badge-warning' : 'badge-success')"
                                    x-text="item.qty === 0 ? 'Out of Stock' : (item.qty <= item.reorderAt ? 'Low Stock' : 'In Stock')"
                                ></span>
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <button @click="adjustQty(item, -1)" class="btn btn-ghost btn-icon btn-sm !w-7 !h-7 text-slate-400 hover:text-red-500">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" /></svg>
                                    </button>
                                    <span class="w-8 text-center text-[13px] font-semibold text-slate-700" x-text="item.qty"></span>
                                    <button @click="adjustQty(item, 1)" class="btn btn-ghost btn-icon btn-sm !w-7 !h-7 text-slate-400 hover:text-emerald-500">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" /></svg>
                                    </button>
                                    <button @click="openEditModal(item)" class="btn btn-ghost btn-sm text-slate-400 hover:text-blue-600 text-[11px]">Set</button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- EDIT STOCK MODAL --}}
    <div x-show="editModal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/30" @click="editModal.open = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-sm"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            id="edit-stock-modal"
        >
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <h2 class="text-base font-semibold text-slate-800">Set Stock Level</h2>
                <button @click="editModal.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
                    <span class="text-2xl" x-text="editModal.item?.emoji"></span>
                    <div>
                        <p class="text-sm font-semibold text-slate-800" x-text="editModal.item?.name"></p>
                        <p class="text-[11px] text-slate-400 font-mono" x-text="editModal.item?.sku"></p>
                    </div>
                </div>
                <div>
                    <label class="form-label">New Quantity</label>
                    <input type="number" x-model.number="editModal.newQty" class="form-input" min="0" id="set-stock-input">
                </div>
                <div>
                    <label class="form-label">Reason / Note</label>
                    <input type="text" x-model="editModal.note" class="form-input" placeholder="e.g. Manual count correction">
                </div>
                <div class="flex gap-3 pt-1">
                    <button @click="editModal.open = false" class="flex-1 btn btn-secondary">Cancel</button>
                    <button @click="applyEdit()" class="flex-1 btn btn-primary" id="apply-stock-btn">Apply</button>
                </div>
            </div>
        </div>
    </div>

    {{-- REORDER QUEUE MODAL --}}
    <div x-show="reorderModal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/30" @click="reorderModal.open = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-lg"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            id="reorder-modal"
        >
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Reorder Queue</h2>
                    <p class="text-[11px] text-slate-400 mt-0.5" x-text="lowStockItems.length + ' items need restocking'"></p>
                </div>
                <button @click="reorderModal.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                <template x-for="item in lowStockItems" :key="item.id">
                    <div class="flex items-center gap-3 px-6 py-3">
                        <span class="text-xl" x-text="item.emoji"></span>
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] font-medium text-slate-700" x-text="item.name"></p>
                            <p class="text-[11px] text-slate-400 font-mono" x-text="item.sku"></p>
                        </div>
                        <div class="text-right">
                            <p class="text-[12px] font-semibold" :class="item.qty === 0 ? 'text-red-500' : 'text-amber-500'" x-text="item.qty + ' left'"></p>
                            <p class="text-[10px] text-slate-400">Reorder at <span x-text="item.reorderAt"></span></p>
                        </div>
                    </div>
                </template>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
                <button @click="reorderModal.open = false" class="btn btn-secondary">Close</button>
                <button class="btn btn-primary gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a.75.75 0 01-.875.854 49.188 49.188 0 01-14.01 0 .75.75 0 01-.875-.854L2.34 18m15.32 0H6.34" /></svg>
                    Create Purchase Order
                </button>
            </div>
        </div>
    </div>

</div>

<script>
function stockPage() {
    return {
        branchName: localStorage.getItem('posBranch') ? (JSON.parse(localStorage.getItem('posBranch'))?.name ?? 'Branch') : 'Branch',
        search: '',
        filterStatus: '',
        filterCategory: '',
        editModal: { open: false, item: null, newQty: 0, note: '' },
        reorderModal: { open: false },

        categories: ['Electronics', 'Peripherals', 'Accessories', 'Cables', 'Audio'],

        items: [
            { id:1,  name:'Wireless Headphones', sku:'ELEC-001', emoji:'🎧', category:'Electronics',  qty:12,  reserved:2, reorderAt:5  },
            { id:2,  name:'Mechanical Keyboard',  sku:'PERI-001', emoji:'⌨️', category:'Peripherals',  qty:3,   reserved:1, reorderAt:5  },
            { id:3,  name:'USB-C Hub 7-in-1',     sku:'ACCS-001', emoji:'🔌', category:'Accessories',  qty:25,  reserved:0, reorderAt:8  },
            { id:4,  name:'Laptop Stand',          sku:'ACCS-002', emoji:'💻', category:'Accessories',  qty:4,   reserved:0, reorderAt:5  },
            { id:5,  name:'Screen Protector',      sku:'ACCS-003', emoji:'🛡️', category:'Accessories',  qty:0,   reserved:0, reorderAt:10 },
            { id:6,  name:'Mouse Pad XL',          sku:'ACCS-004', emoji:'🖱️', category:'Accessories',  qty:18,  reserved:3, reorderAt:5  },
            { id:7,  name:'Webcam HD 1080p',       sku:'ELEC-002', emoji:'📷', category:'Electronics',  qty:2,   reserved:0, reorderAt:5  },
            { id:8,  name:'HDMI Cable 2m',         sku:'CABL-001', emoji:'📺', category:'Cables',       qty:40,  reserved:5, reorderAt:10 },
            { id:9,  name:'Wireless Mouse',        sku:'PERI-002', emoji:'🖱️', category:'Peripherals',  qty:8,   reserved:1, reorderAt:5  },
            { id:10, name:'USB-A Cable 1m',        sku:'CABL-002', emoji:'🔗', category:'Cables',       qty:30,  reserved:2, reorderAt:10 },
        ],

        get filtered() {
            return this.items.filter(i => {
                const q = this.search.toLowerCase();
                const matchQ = !q || i.name.toLowerCase().includes(q) || i.sku.toLowerCase().includes(q);
                const matchCat = !this.filterCategory || i.category === this.filterCategory;
                const matchStatus = !this.filterStatus
                    || (this.filterStatus === 'ok'  && i.qty > i.reorderAt)
                    || (this.filterStatus === 'low' && i.qty > 0 && i.qty <= i.reorderAt)
                    || (this.filterStatus === 'out' && i.qty === 0);
                return matchQ && matchCat && matchStatus;
            });
        },

        get lowStockItems() {
            return this.items.filter(i => i.qty <= i.reorderAt);
        },

        init() {
            // Sync branch name when context changes
            window.addEventListener('context-changed', (e) => {
                this.branchName = e.detail.branch?.name ?? 'Branch';
            });
        },

        adjustQty(item, delta) {
            item.qty = Math.max(0, item.qty + delta);
            window.dispatchEvent(new CustomEvent('show-toast', {
                detail: { message: item.name + ' → ' + item.qty + ' units', type: delta > 0 ? 'success' : 'info', duration: 2000 }
            }));
        },

        openEditModal(item) {
            this.editModal.item = item;
            this.editModal.newQty = item.qty;
            this.editModal.note = '';
            this.editModal.open = true;
        },

        applyEdit() {
            this.editModal.item.qty = Math.max(0, this.editModal.newQty);
            this.editModal.open = false;
            window.dispatchEvent(new CustomEvent('show-toast', {
                detail: { message: 'Stock updated to ' + this.editModal.newQty + ' units.', type: 'success' }
            }));
        },

        exportCSV() {
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'Exporting stock CSV…', type: 'info' } }));
        },
    };
}
</script>

</x-layouts.admin>
