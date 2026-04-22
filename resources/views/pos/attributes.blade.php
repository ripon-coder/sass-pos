<x-layouts.admin pageTitle="Attributes">

<div class="p-6 lg:p-8 space-y-6" x-data="attributesPage()" x-init="init()">

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2.5 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-100 text-blue-700 border border-blue-200/80">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                    Global Products
                </span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Attributes</h2>
            <p class="text-[13px] text-slate-400 mt-0.5">Define global product attributes like Size, Color, and Material for variant generation.</p>
        </div>
        <button @click="openModal(null)" class="btn btn-primary gap-2" id="add-attribute-btn">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Add Attribute
        </button>
    </div>

    {{-- INFO BANNER --}}
    <div class="rounded-xl bg-blue-50 border border-blue-200/80 px-4 py-3 flex items-start gap-3">
        <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
        <p class="text-[12px] text-blue-700 leading-relaxed">Attributes are <strong>global</strong> — they apply to all branches. Use them to generate product variants (e.g. Size: S / M / L, Color: Red / Blue). Each attribute value combination creates a unique variant SKU.</p>
    </div>

    {{-- ATTRIBUTES LIST --}}
    <div class="space-y-4">
        <template x-for="attr in attributes" :key="attr.id">
            <div class="card overflow-visible">
                <div class="flex items-center gap-4 px-5 py-4">
                    {{-- Drag handle visual --}}
                    <svg class="w-4 h-4 text-slate-300 flex-shrink-0 cursor-move" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" /></svg>

                    {{-- Attribute name + type --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-sm font-semibold text-slate-800" x-text="attr.name"></h3>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-violet-50 text-violet-600 border border-violet-200/80" x-text="attr.type"></span>
                            <span x-show="attr.useForVariants" class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-600 border border-blue-200/80">Variants</span>
                        </div>
                        {{-- Attribute values as chips --}}
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            <template x-for="val in attr.values" :key="val.id">
                                <div class="group/chip flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200 hover:border-slate-300 transition-colors">
                                    <template x-if="val.swatch">
                                        <span class="w-2.5 h-2.5 rounded-full border border-white shadow-sm inline-block" :style="'background:' + val.swatch"></span>
                                    </template>
                                    <span x-text="val.label"></span>
                                    <button @click="removeValue(attr, val)" class="opacity-0 group-hover/chip:opacity-100 ml-0.5 text-slate-400 hover:text-red-500 transition-all">
                                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                            </template>

                            {{-- Quick-add chip --}}
                            <div x-show="attr._adding" x-cloak class="flex items-center gap-1">
                                <input type="text" x-ref="newValInput"
                                    @keydown.enter.prevent="addValue(attr)"
                                    @keydown.escape="attr._adding = false; attr._newVal = ''"
                                    x-model="attr._newVal"
                                    class="px-2 py-1 rounded-full text-[11px] border border-blue-300 outline-none w-24 bg-blue-50"
                                    placeholder="Value…"
                                >
                                <button @click="addValue(attr)" class="text-blue-500 hover:text-blue-700">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                </button>
                                <button @click="attr._adding = false" class="text-slate-400 hover:text-slate-600">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>

                            <button
                                @click="startAddValue(attr)"
                                class="flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-medium text-blue-500 border border-dashed border-blue-300 hover:bg-blue-50 transition-colors"
                            >
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                Add value
                            </button>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        <span class="text-[11px] text-slate-400" x-text="attr.values.length + ' values'"></span>
                        <button @click="openModal(attr)" class="btn btn-ghost btn-icon btn-sm text-slate-400 hover:text-blue-600" title="Edit">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /></svg>
                        </button>
                        <button @click="confirmDelete(attr)" class="btn btn-ghost btn-icon btn-sm text-slate-400 hover:text-red-500" title="Delete">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </template>

        {{-- Empty state --}}
        <template x-if="attributes.length === 0">
            <div class="flex flex-col items-center justify-center py-20 text-center card">
                <svg class="w-12 h-12 text-slate-200 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" /></svg>
                <p class="text-sm font-medium text-slate-500">No attributes defined yet</p>
                <p class="text-xs text-slate-400 mt-1">Add attributes like Size, Color or Material to generate variants</p>
                <button @click="openModal(null)" class="btn btn-primary mt-4">Add Attribute</button>
            </div>
        </template>
    </div>

    {{-- ADD / EDIT ATTRIBUTE MODAL --}}
    <div x-show="modal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/30" @click="modal.open = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            id="attribute-modal"
        >
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <h2 class="text-base font-semibold text-slate-800" x-text="modal.editing ? 'Edit Attribute' : 'Add Attribute'"></h2>
                <button @click="modal.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form @submit.prevent="saveAttribute()" class="p-6 space-y-4">
                <div>
                    <label class="form-label" for="attr-name-input">Attribute Name <span class="text-red-500">*</span></label>
                    <input type="text" id="attr-name-input" x-model="modal.form.name" class="form-input" placeholder="e.g. Size, Color, Material" required>
                </div>
                <div>
                    <label class="form-label">Type</label>
                    <select x-model="modal.form.type" class="form-input form-select">
                        <option value="Select">Select (dropdown)</option>
                        <option value="Color">Color (swatch)</option>
                        <option value="Radio">Radio (buttons)</option>
                        <option value="Text">Text (free form)</option>
                    </select>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-blue-50 border border-blue-200/80">
                    <div>
                        <p class="text-[12px] font-semibold text-blue-700">Use for variant generation</p>
                        <p class="text-[11px] text-blue-500">Combinations will create unique SKUs</p>
                    </div>
                    <button type="button"
                        @click="modal.form.useForVariants = !modal.form.useForVariants"
                        class="relative inline-flex h-5 w-9 rounded-full transition-colors"
                        :class="modal.form.useForVariants ? 'bg-blue-500' : 'bg-slate-200'"
                    >
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform mt-0.5 ml-0.5" :class="modal.form.useForVariants ? 'translate-x-4' : 'translate-x-0'"></span>
                    </button>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="modal.open = false" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="save-attribute-btn">
                        <span x-text="modal.editing ? 'Save Changes' : 'Create Attribute'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- DELETE CONFIRM --}}
    <div x-show="deleteModal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/30" @click="deleteModal.open = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-sm p-6" id="delete-attribute-modal">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
            </div>
            <h3 class="text-sm font-semibold text-slate-800 text-center">Delete Attribute?</h3>
            <p class="text-sm text-slate-500 text-center mt-1" x-text="'This will delete the \'' + deleteModal.attribute?.name + '\' attribute and all its values.'"></p>
            <div class="flex gap-3 mt-5">
                <button @click="deleteModal.open = false" class="flex-1 btn btn-secondary">Cancel</button>
                <button @click="deleteAttribute()" class="flex-1 btn btn-danger" id="confirm-delete-attribute-btn">Delete</button>
            </div>
        </div>
    </div>

</div>

<script>
function attributesPage() {
    return {
        modal: { open: false, editing: false, form: {} },
        deleteModal: { open: false, attribute: null },

        attributes: [
            { id: 1, name: 'Size',     type: 'Select', useForVariants: true,  values: [
                { id: 1, label: 'XS', swatch: null },
                { id: 2, label: 'S',  swatch: null },
                { id: 3, label: 'M',  swatch: null },
                { id: 4, label: 'L',  swatch: null },
                { id: 5, label: 'XL', swatch: null },
            ], _adding: false, _newVal: '' },
            { id: 2, name: 'Color',    type: 'Color',  useForVariants: true,  values: [
                { id: 1, label: 'Black',  swatch: '#1e293b' },
                { id: 2, label: 'White',  swatch: '#f8fafc' },
                { id: 3, label: 'Blue',   swatch: '#3b82f6' },
                { id: 4, label: 'Red',    swatch: '#ef4444' },
            ], _adding: false, _newVal: '' },
            { id: 3, name: 'Material', type: 'Select', useForVariants: false, values: [
                { id: 1, label: 'Cotton',     swatch: null },
                { id: 2, label: 'Polyester',  swatch: null },
                { id: 3, label: 'Leather',    swatch: null },
            ], _adding: false, _newVal: '' },
            { id: 4, name: 'Storage',  type: 'Radio',  useForVariants: true,  values: [
                { id: 1, label: '64GB',  swatch: null },
                { id: 2, label: '128GB', swatch: null },
                { id: 3, label: '256GB', swatch: null },
                { id: 4, label: '512GB', swatch: null },
            ], _adding: false, _newVal: '' },
        ],

        init() {},

        openModal(attr) {
            if (attr) {
                this.modal.editing = true;
                this.modal.form = { ...attr };
            } else {
                this.modal.editing = false;
                this.modal.form = { id: null, name: '', type: 'Select', useForVariants: true, values: [], _adding: false, _newVal: '' };
            }
            this.modal.open = true;
        },

        saveAttribute() {
            if (this.modal.editing) {
                const idx = this.attributes.findIndex(a => a.id === this.modal.form.id);
                if (idx > -1) {
                    this.attributes[idx].name = this.modal.form.name;
                    this.attributes[idx].type = this.modal.form.type;
                    this.attributes[idx].useForVariants = this.modal.form.useForVariants;
                }
            } else {
                this.attributes.push({ ...this.modal.form, id: Date.now(), values: [], _adding: false, _newVal: '' });
            }
            this.modal.open = false;
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: this.modal.editing ? 'Attribute updated.' : 'Attribute created.', type: 'success' } }));
        },

        confirmDelete(attr) {
            this.deleteModal.attribute = attr;
            this.deleteModal.open = true;
        },

        deleteAttribute() {
            this.attributes = this.attributes.filter(a => a.id !== this.deleteModal.attribute.id);
            this.deleteModal.open = false;
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'Attribute deleted.', type: 'info' } }));
        },

        startAddValue(attr) {
            attr._adding = true;
            attr._newVal = '';
            this.$nextTick(() => {
                const el = this.$el.querySelector(`[x-ref="newValInput"]`);
                if (el) el.focus();
            });
        },

        addValue(attr) {
            const label = (attr._newVal || '').trim();
            if (!label) return;
            attr.values.push({ id: Date.now(), label, swatch: null });
            attr._newVal = '';
        },

        removeValue(attr, val) {
            attr.values = attr.values.filter(v => v.id !== val.id);
        },
    };
}
</script>

</x-layouts.admin>
