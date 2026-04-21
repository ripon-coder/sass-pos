<x-layouts.admin pageTitle="Products">

<div class="p-6 space-y-5" x-data="productsPage()" x-init="init()">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Products</h2>
            <p class="text-sm text-slate-500 mt-0.5">Manage your product catalog and inventory</p>
        </div>
        <button @click="openModal(null)" class="btn btn-primary" id="add-product-btn">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add Product
        </button>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 max-w-xs">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
            <input type="search" x-model="search" @input="filterRows()" placeholder="Search products…" class="form-input pl-9" id="products-search">
        </div>
        <select x-model="filterCategory" @change="filterRows()" class="form-input form-select w-44" id="products-category-filter">
            <option value="">All Categories</option>
            <template x-for="c in categories" :key="c"><option x-text="c" :value="c"></option></template>
        </select>
        <select x-model="filterStock" @change="filterRows()" class="form-input form-select w-44" id="products-stock-filter">
            <option value="">All Stock</option>
            <option value="instock">In Stock</option>
            <option value="lowstock">Low Stock (≤5)</option>
            <option value="outofstock">Out of Stock</option>
        </select>
        <span class="text-xs text-slate-400 ml-auto" x-text="filteredProducts.length + ' results'"></span>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="table-wrapper">
            <table class="table" id="products-table">
                <thead>
                    <tr>
                        <th>
                            <button @click="sortBy('name')" class="flex items-center gap-1 hover:text-slate-700">
                                Product <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" /></svg>
                            </button>
                        </th>
                        <th>Category</th>
                        <th>
                            <button @click="sortBy('price')" class="flex items-center gap-1 hover:text-slate-700">
                                Price <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" /></svg>
                            </button>
                        </th>
                        <th>
                            <button @click="sortBy('stock')" class="flex items-center gap-1 hover:text-slate-700">
                                Stock <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" /></svg>
                            </button>
                        </th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Empty State --}}
                    <tr x-show="filteredProducts.length === 0" x-cloak>
                        <td colspan="6">
                            <div class="flex flex-col items-center justify-center py-16 text-center">
                                <svg class="w-12 h-12 text-slate-200 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                                <p class="text-sm font-medium text-slate-500">No products found</p>
                                <p class="text-xs text-slate-400 mt-1">Try adjusting your search or filters</p>
                            </div>
                        </td>
                    </tr>

                    <template x-for="p in filteredProducts" :key="p.id">
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-base flex-shrink-0" x-text="p.emoji"></div>
                                    <div>
                                        <p class="font-medium text-slate-700" x-text="p.name"></p>
                                        <p class="text-xs text-slate-400" x-text="'SKU: ' + p.sku"></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-gray" x-text="p.category"></span>
                            </td>
                            <td class="font-medium text-slate-700" x-text="'$' + p.price.toFixed(2)"></td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all"
                                            :class="p.stock === 0 ? 'bg-red-400' : (p.stock <= 5 ? 'bg-amber-400' : 'bg-emerald-400')"
                                            :style="'width: ' + Math.min(100, (p.stock / 50) * 100) + '%'"
                                        ></div>
                                    </div>
                                    <span class="text-sm text-slate-600" x-text="p.stock"></span>
                                </div>
                            </td>
                            <td>
                                <span
                                    class="badge"
                                    :class="p.stock === 0 ? 'badge-danger' : (p.stock <= 5 ? 'badge-warning' : 'badge-success')"
                                    x-text="p.stock === 0 ? 'Out of Stock' : (p.stock <= 5 ? 'Low Stock' : 'In Stock')"
                                ></span>
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <button @click="openModal(p)" class="btn btn-ghost btn-sm btn-icon text-slate-400 hover:text-blue-600" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                    </button>
                                    <button @click="confirmDelete(p)" class="btn btn-ghost btn-sm btn-icon text-slate-400 hover:text-red-500" title="Delete">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== ADD / EDIT PRODUCT MODAL ===== --}}
    <div x-show="modal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/30" @click="modal.open = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            id="product-modal"
        >
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <h2 class="text-base font-semibold text-slate-800" x-text="modal.editing ? 'Edit Product' : 'Add Product'"></h2>
                <button @click="modal.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form @submit.prevent="saveProduct()" class="p-6 space-y-4">
                <div>
                    <label class="form-label" for="product-name-input">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" id="product-name-input" x-model="modal.form.name" class="form-input" placeholder="e.g. Wireless Headphones" required>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label" for="product-sku-input">SKU</label>
                        <input type="text" id="product-sku-input" x-model="modal.form.sku" class="form-input" placeholder="SKU-001">
                    </div>
                    <div>
                        <label class="form-label" for="product-category-input">Category</label>
                        <select id="product-category-input" x-model="modal.form.category" class="form-input form-select">
                            <option value="">Select…</option>
                            <template x-for="c in categories" :key="c"><option x-text="c" :value="c"></option></template>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label" for="product-price-input">Price ($) <span class="text-red-500">*</span></label>
                        <input type="number" id="product-price-input" x-model.number="modal.form.price" class="form-input" placeholder="0.00" step="0.01" min="0" required>
                    </div>
                    <div>
                        <label class="form-label" for="product-stock-input">Stock Quantity</label>
                        <input type="number" id="product-stock-input" x-model.number="modal.form.stock" class="form-input" placeholder="0" min="0">
                    </div>
                </div>
                <div>
                    <label class="form-label" for="product-description-input">Description</label>
                    <textarea id="product-description-input" x-model="modal.form.description" class="form-input" rows="3" placeholder="Product description…"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="modal.open = false" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="save-product-btn">
                        <span x-text="modal.editing ? 'Save Changes' : 'Add Product'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== DELETE CONFIRM MODAL ===== --}}
    <div x-show="deleteModal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/30" @click="deleteModal.open = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-sm"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            id="delete-product-modal"
        >
            <div class="p-6">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                </div>
                <h3 class="text-sm font-semibold text-slate-800 text-center">Delete Product?</h3>
                <p class="text-sm text-slate-500 text-center mt-1" x-text="'This will permanently delete ' + (deleteModal.product?.name || '') + '.'"></p>
                <div class="flex gap-3 mt-5">
                    <button @click="deleteModal.open = false" class="flex-1 btn btn-secondary">Cancel</button>
                    <button @click="deleteProduct()" class="flex-1 btn btn-danger" id="confirm-delete-product-btn">Delete</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function productsPage() {
    return {
        search: '',
        filterCategory: '',
        filterStock: '',
        sortKey: 'name',
        sortDir: 1,
        categories: ['Electronics', 'Accessories', 'Cables', 'Peripherals'],
        modal: { open: false, editing: false, form: {} },
        deleteModal: { open: false, product: null },
        allProducts: [
            { id: 1, name: 'Wireless Headphones',  sku: 'ELEC-001', price: 89.99,  stock: 12, category: 'Electronics',  emoji: '🎧', description: '' },
            { id: 2, name: 'Mechanical Keyboard',   sku: 'PERI-001', price: 129.00, stock: 8,  category: 'Peripherals',  emoji: '⌨️', description: '' },
            { id: 3, name: 'USB-C Hub 7-in-1',      sku: 'ACCS-001', price: 49.99,  stock: 25, category: 'Accessories',  emoji: '🔌', description: '' },
            { id: 4, name: 'Laptop Stand',           sku: 'ACCS-002', price: 39.99,  stock: 5,  category: 'Accessories',  emoji: '💻', description: '' },
            { id: 5, name: 'Screen Protector',       sku: 'ACCS-003', price: 14.99,  stock: 0,  category: 'Accessories',  emoji: '🛡️', description: '' },
            { id: 6, name: 'Mouse Pad XL',           sku: 'ACCS-004', price: 24.99,  stock: 18, category: 'Accessories',  emoji: '🖱️', description: '' },
            { id: 7, name: 'Webcam HD 1080p',        sku: 'ELEC-002', price: 79.99,  stock: 3,  category: 'Electronics',  emoji: '📷', description: '' },
            { id: 8, name: 'HDMI Cable 2m',          sku: 'CABL-001', price: 12.99,  stock: 40, category: 'Cables',       emoji: '📺', description: '' },
        ],
        filteredProducts: [],

        init() { this.filterRows(); },

        filterRows() {
            let p = [...this.allProducts];
            if (this.filterCategory) p = p.filter(x => x.category === this.filterCategory);
            if (this.filterStock === 'instock')    p = p.filter(x => x.stock > 5);
            if (this.filterStock === 'lowstock')   p = p.filter(x => x.stock > 0 && x.stock <= 5);
            if (this.filterStock === 'outofstock') p = p.filter(x => x.stock === 0);
            if (this.search.trim()) {
                const q = this.search.toLowerCase();
                p = p.filter(x => x.name.toLowerCase().includes(q) || x.sku.toLowerCase().includes(q));
            }
            p.sort((a, b) => {
                if (a[this.sortKey] < b[this.sortKey]) return -this.sortDir;
                if (a[this.sortKey] > b[this.sortKey]) return this.sortDir;
                return 0;
            });
            this.filteredProducts = p;
        },

        sortBy(key) {
            if (this.sortKey === key) this.sortDir *= -1;
            else { this.sortKey = key; this.sortDir = 1; }
            this.filterRows();
        },

        openModal(product) {
            if (product) {
                this.modal.editing = true;
                this.modal.form = { ...product };
            } else {
                this.modal.editing = false;
                this.modal.form = { id: null, name: '', sku: '', category: '', price: '', stock: 0, description: '', emoji: '📦' };
            }
            this.modal.open = true;
        },

        saveProduct() {
            if (this.modal.editing) {
                const idx = this.allProducts.findIndex(p => p.id === this.modal.form.id);
                if (idx > -1) this.allProducts[idx] = { ...this.modal.form };
            } else {
                this.allProducts.push({ ...this.modal.form, id: Date.now() });
            }
            this.modal.open = false;
            this.filterRows();
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: this.modal.editing ? 'Product updated.' : 'Product added.', type: 'success' } }));
        },

        confirmDelete(product) {
            this.deleteModal.product = product;
            this.deleteModal.open = true;
        },

        deleteProduct() {
            this.allProducts = this.allProducts.filter(p => p.id !== this.deleteModal.product.id);
            this.deleteModal.open = false;
            this.filterRows();
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'Product deleted.', type: 'info' } }));
        },
    };
}
</script>

</x-layouts.admin>
