<x-layouts.admin pageTitle="Categories">

<div class="p-6 lg:p-8 space-y-6" x-data="categoriesPage()" x-init="init()">

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2.5 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-100 text-blue-700 border border-blue-200/80">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                    Global Products
                </span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Categories</h2>
            <p class="text-[13px] text-slate-400 mt-0.5">Organise your global product catalog into categories.</p>
        </div>
        <button @click="openModal(null)" class="btn btn-primary gap-2" id="add-category-btn">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Add Category
        </button>
    </div>

    {{-- STATS ROW --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="stat-card !p-4">
            <p class="stat-label">Total Categories</p>
            <p class="text-2xl font-bold text-slate-900" x-text="categories.length"></p>
        </div>
        <div class="stat-card !p-4">
            <p class="stat-label">Total Products</p>
            <p class="text-2xl font-bold text-slate-900" x-text="categories.reduce((s,c)=>s+c.productCount,0)"></p>
        </div>
        <div class="stat-card !p-4">
            <p class="stat-label">Active</p>
            <p class="text-2xl font-bold text-emerald-600" x-text="categories.filter(c=>c.active).length"></p>
        </div>
        <div class="stat-card !p-4">
            <p class="stat-label">Inactive</p>
            <p class="text-2xl font-bold text-slate-400" x-text="categories.filter(c=>!c.active).length"></p>
        </div>
    </div>

    {{-- SEARCH + FILTER --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 max-w-xs">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            <input type="search" x-model="search" placeholder="Search categories…" class="form-input pl-9" id="categories-search">
        </div>
        <select x-model="filterStatus" class="form-input form-select w-36">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
        <span class="text-xs text-slate-400 ml-auto" x-text="filtered.length + ' categories'"></span>
    </div>

    {{-- CATEGORY GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <template x-for="cat in filtered" :key="cat.id">
            <div class="card group relative overflow-hidden hover:shadow-md transition-all duration-200">
                {{-- Colour accent top bar --}}
                <div class="h-1.5 w-full" :style="'background:' + cat.color"></div>
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl flex-shrink-0" :style="'background:' + cat.color + '22'">
                                <span x-text="cat.icon"></span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-800" x-text="cat.name"></p>
                                <p class="text-[11px] text-slate-400" x-text="cat.productCount + ' products'"></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button @click="openModal(cat)" class="btn btn-ghost btn-icon btn-sm text-slate-400 hover:text-blue-600 !w-7 !h-7" title="Edit">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /></svg>
                            </button>
                            <button @click="confirmDelete(cat)" class="btn btn-ghost btn-icon btn-sm text-slate-400 hover:text-red-500 !w-7 !h-7" title="Delete">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                            </button>
                        </div>
                    </div>

                    <p class="text-[11px] text-slate-400 mt-2.5 leading-relaxed line-clamp-2" x-text="cat.description || 'No description.'"></p>

                    {{-- Stock mini-bar --}}
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
                        <div class="flex-1 h-1 bg-slate-100 rounded-full overflow-hidden mr-3">
                            <div class="h-full rounded-full transition-all" :style="'width:' + Math.min(100,(cat.productCount/30)*100) + '%;background:' + cat.color"></div>
                        </div>
                        <span
                            class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold"
                            :class="cat.active ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-slate-100 text-slate-400 border border-slate-200'"
                            x-text="cat.active ? 'Active' : 'Inactive'"
                        ></span>
                    </div>
                </div>
            </div>
        </template>

        {{-- Empty state --}}
        <template x-if="filtered.length === 0">
            <div class="col-span-full flex flex-col items-center justify-center py-20 text-center">
                <svg class="w-12 h-12 text-slate-200 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /></svg>
                <p class="text-sm font-medium text-slate-500">No categories found</p>
                <p class="text-xs text-slate-400 mt-1">Create your first category to start organising products</p>
                <button @click="openModal(null)" class="btn btn-primary mt-4">Add Category</button>
            </div>
        </template>
    </div>

    {{-- ===== ADD / EDIT MODAL ===== --}}
    <div x-show="modal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/30" @click="modal.open = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            id="category-modal"
        >
            {{-- Preview bar --}}
            <div class="h-2 rounded-t-xl transition-all" :style="'background:' + modal.form.color"></div>
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <h2 class="text-base font-semibold text-slate-800" x-text="modal.editing ? 'Edit Category' : 'Add Category'"></h2>
                <button @click="modal.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form @submit.prevent="saveCategory()" class="p-6 space-y-4">
                <div class="flex gap-3">
                    <div class="flex-1">
                        <label class="form-label" for="cat-name-input">Category Name <span class="text-red-500">*</span></label>
                        <input type="text" id="cat-name-input" x-model="modal.form.name" class="form-input" placeholder="e.g. Electronics" required>
                    </div>
                    <div class="w-16">
                        <label class="form-label">Icon</label>
                        <input type="text" x-model="modal.form.icon" class="form-input text-center text-xl" placeholder="📦" maxlength="4">
                    </div>
                </div>
                <div>
                    <label class="form-label">Description</label>
                    <textarea x-model="modal.form.description" class="form-input" rows="2" placeholder="Optional description…"></textarea>
                </div>
                <div>
                    <label class="form-label">Colour</label>
                    <div class="flex flex-wrap gap-2 mt-1">
                        <template x-for="col in colorPalette" :key="col">
                            <button type="button"
                                @click="modal.form.color = col"
                                class="w-7 h-7 rounded-lg border-2 transition-all hover:scale-110"
                                :style="'background:' + col"
                                :class="modal.form.color === col ? 'border-slate-700 scale-110' : 'border-transparent'"
                            ></button>
                        </template>
                        <input type="color" x-model="modal.form.color" class="w-7 h-7 rounded-lg border border-slate-200 cursor-pointer" title="Custom colour">
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <label class="form-label !mb-0">Active</label>
                    <button type="button"
                        @click="modal.form.active = !modal.form.active"
                        class="relative inline-flex h-5 w-9 rounded-full transition-colors focus:outline-none"
                        :class="modal.form.active ? 'bg-emerald-500' : 'bg-slate-200'"
                    >
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform mt-0.5 ml-0.5" :class="modal.form.active ? 'translate-x-4' : 'translate-x-0'"></span>
                    </button>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="modal.open = false" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="save-category-btn">
                        <span x-text="modal.editing ? 'Save Changes' : 'Add Category'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- DELETE CONFIRM --}}
    <div x-show="deleteModal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/30" @click="deleteModal.open = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-sm p-6"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            id="delete-category-modal"
        >
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
            </div>
            <h3 class="text-sm font-semibold text-slate-800 text-center">Delete Category?</h3>
            <p class="text-sm text-slate-500 text-center mt-1" x-text="'Delete \'' + deleteModal.category?.name + '\'? Products in this category won\'t be deleted.'"></p>
            <div class="flex gap-3 mt-5">
                <button @click="deleteModal.open = false" class="flex-1 btn btn-secondary">Cancel</button>
                <button @click="deleteCategory()" class="flex-1 btn btn-danger" id="confirm-delete-category-btn">Delete</button>
            </div>
        </div>
    </div>

</div>

<script>
function categoriesPage() {
    return {
        search: '',
        filterStatus: '',
        modal: { open: false, editing: false, form: {} },
        deleteModal: { open: false, category: null },

        colorPalette: ['#3b82f6','#8b5cf6','#10b981','#f59e0b','#ef4444','#ec4899','#06b6d4','#64748b'],

        categories: [
            { id: 1,  name: 'Electronics',  icon: '🔌', color: '#3b82f6', description: 'Consumer electronics and gadgets',    productCount: 12, active: true  },
            { id: 2,  name: 'Peripherals',  icon: '⌨️', color: '#8b5cf6', description: 'Keyboards, mice, headsets and more', productCount: 8,  active: true  },
            { id: 3,  name: 'Accessories',  icon: '💼', color: '#10b981', description: 'Stands, cases and everyday add-ons',  productCount: 20, active: true  },
            { id: 4,  name: 'Cables',       icon: '🔗', color: '#f59e0b', description: 'USB, HDMI, power and data cables',    productCount: 15, active: true  },
            { id: 5,  name: 'Audio',        icon: '🎧', color: '#ec4899', description: 'Speakers, headphones, microphones',   productCount: 6,  active: true  },
            { id: 6,  name: 'Storage',      icon: '💾', color: '#06b6d4', description: 'SSDs, HDDs and flash drives',         productCount: 4,  active: false },
        ],

        get filtered() {
            return this.categories.filter(c => {
                const q = this.search.toLowerCase();
                const matchSearch = !q || c.name.toLowerCase().includes(q) || (c.description||'').toLowerCase().includes(q);
                const matchStatus = !this.filterStatus
                    || (this.filterStatus === 'active' && c.active)
                    || (this.filterStatus === 'inactive' && !c.active);
                return matchSearch && matchStatus;
            });
        },

        init() {},

        openModal(cat) {
            if (cat) {
                this.modal.editing = true;
                this.modal.form = { ...cat };
            } else {
                this.modal.editing = false;
                this.modal.form = { id: null, name: '', icon: '📦', color: '#3b82f6', description: '', active: true, productCount: 0 };
            }
            this.modal.open = true;
        },

        saveCategory() {
            if (this.modal.editing) {
                const idx = this.categories.findIndex(c => c.id === this.modal.form.id);
                if (idx > -1) this.categories[idx] = { ...this.modal.form };
            } else {
                this.categories.push({ ...this.modal.form, id: Date.now() });
            }
            this.modal.open = false;
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: this.modal.editing ? 'Category updated.' : 'Category created.', type: 'success' } }));
        },

        confirmDelete(cat) {
            this.deleteModal.category = cat;
            this.deleteModal.open = true;
        },

        deleteCategory() {
            this.categories = this.categories.filter(c => c.id !== this.deleteModal.category.id);
            this.deleteModal.open = false;
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'Category deleted.', type: 'info' } }));
        },
    };
}
</script>

</x-layouts.admin>
