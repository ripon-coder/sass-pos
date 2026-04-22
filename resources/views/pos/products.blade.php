<x-layouts.admin pageTitle="Products">

<div class="p-6 lg:p-8 space-y-6" x-data="productsManager()" x-init="init()">

    {{-- ========================================================
         LIST VIEW
    ======================================================== --}}
    <template x-if="view === 'list'">
        <div class="space-y-5">

            {{-- Header --}}
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-100 text-blue-700 border border-blue-200/80">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>Global Products
                        </span>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Products</h2>
                    <p class="text-[13px] text-slate-400 mt-0.5">Global catalog — all branches share products &amp; variant pricing.</p>
                </div>
                <button @click="openAdd()" class="btn btn-primary gap-2" id="add-product-btn">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add Product
                </button>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="stat-card !p-4"><p class="stat-label">Total Products</p><p class="text-2xl font-bold text-slate-900" x-text="products.length"></p></div>
                <div class="stat-card !p-4"><p class="stat-label">With Variants</p><p class="text-2xl font-bold text-violet-600" x-text="products.filter(p=>p.hasVariants).length"></p></div>
                <div class="stat-card !p-4"><p class="stat-label">Total Variants</p><p class="text-2xl font-bold text-blue-600" x-text="products.reduce((s,p)=>s+(p.variants?.length||0),0)"></p></div>
                <div class="stat-card !p-4"><p class="stat-label">Categories</p><p class="text-2xl font-bold text-slate-700" x-text="[...new Set(products.map(p=>p.category).filter(Boolean))].length"></p></div>
            </div>

            {{-- Filters --}}
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative flex-1 max-w-xs">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    <input type="search" x-model="search" placeholder="Search products…" class="form-input pl-9" id="product-list-search">
                </div>
                <select x-model="filterCat" class="form-input form-select w-44">
                    <option value="">All Categories</option>
                    <template x-for="c in availableCategories" :key="c"><option x-text="c" :value="c"></option></template>
                </select>
                <select x-model="filterVariant" class="form-input form-select w-36">
                    <option value="">All Types</option>
                    <option value="simple">Simple</option>
                    <option value="variant">Has Variants</option>
                </select>
                <span class="text-xs text-slate-400 ml-auto" x-text="filteredProducts.length + ' products'"></span>
            </div>

            {{-- Product Table --}}
            <div class="card">
                <div class="table-wrapper">
                    <table class="table" id="products-table">
                        <thead>
                            <tr>
                                <th>Product</th><th>Category</th><th>Type</th><th>Variants / Price</th><th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr x-show="filteredProducts.length === 0">
                                <td colspan="5">
                                    <div class="flex flex-col items-center py-16 text-center">
                                        <svg class="w-12 h-12 text-slate-200 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                                        <p class="text-sm font-medium text-slate-500">No products found</p>
                                        <button @click="openAdd()" class="btn btn-primary mt-4 text-sm">Add your first product</button>
                                    </div>
                                </td>
                            </tr>
                            <template x-for="p in filteredProducts" :key="p.id">
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-base flex-shrink-0" x-text="p.emoji||'📦'"></div>
                                            <div>
                                                <p class="font-semibold text-slate-800 text-[13px]" x-text="p.name"></p>
                                                <p class="text-[11px] text-slate-400" x-text="(p.description||'No description').substring(0,50)"></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-gray" x-text="p.category||'—'"></span></td>
                                    <td><span class="badge" :class="p.hasVariants?'badge-info':'badge-gray'" x-text="p.hasVariants?'Variants':'Simple'"></span></td>
                                    <td>
                                        <template x-if="p.hasVariants">
                                            <div>
                                                <p class="text-[12px] font-semibold text-slate-700" x-text="p.variants.length+' variant'+(p.variants.length!==1?'s':'')"></p>
                                                <p class="text-[11px] text-slate-400" x-text="'$'+Math.min(...p.variants.map(v=>parseFloat(v.price)||0)).toFixed(2)+' – $'+Math.max(...p.variants.map(v=>parseFloat(v.price)||0)).toFixed(2)"></p>
                                            </div>
                                        </template>
                                        <template x-if="!p.hasVariants">
                                            <p class="font-semibold text-slate-700 text-[13px]" x-text="'$'+parseFloat(p.price||0).toFixed(2)"></p>
                                        </template>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-1">
                                            <button @click="openEdit(p)" class="btn btn-ghost btn-sm btn-icon text-slate-400 hover:text-blue-600" title="Edit"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg></button>
                                            <button @click="duplicateProduct(p)" class="btn btn-ghost btn-sm btn-icon text-slate-400 hover:text-violet-600" title="Duplicate"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/></svg></button>
                                            <button @click="deleteProduct(p)" class="btn btn-ghost btn-sm btn-icon text-slate-400 hover:text-red-500" title="Delete"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg></button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </template>

    {{-- ========================================================
         FORM VIEW  (Add / Edit)
    ======================================================== --}}
    <template x-if="view === 'form'">
        <div class="space-y-6">

            {{-- Breadcrumb --}}
            <div class="flex items-center gap-3 flex-wrap">
                <button @click="cancelForm()" class="btn btn-ghost btn-sm gap-1.5 text-slate-500 hover:text-slate-800 -ml-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    Products
                </button>
                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                <span class="text-sm font-semibold text-slate-700" x-text="isEdit ? 'Edit: '+form.name : 'Add New Product'"></span>
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-100 text-blue-700 border border-blue-200/80 ml-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>Global
                </span>
            </div>

            {{-- Validation banner --}}
            <template x-if="validationErrors.length > 0">
                <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 flex items-start gap-3">
                    <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                    <ul class="space-y-0.5">
                        <template x-for="err in validationErrors" :key="err">
                            <li class="text-[12px] text-red-700 font-medium" x-text="err"></li>
                        </template>
                    </ul>
                </div>
            </template>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">

                {{-- ────────────────────────────
                     LEFT / CENTRE COLUMN
                ──────────────────────────── --}}
                <div class="xl:col-span-2 space-y-5">

                    {{-- PRODUCT INFO --}}
                    <div class="card p-5 space-y-4">
                        <h3 class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                            <span class="w-5 h-5 rounded-md bg-blue-100 flex items-center justify-center"><svg class="w-3 h-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg></span>
                            Product Information
                        </h3>
                        <div>
                            <label class="form-label" for="pf-name">Product Name <span class="text-red-500">*</span></label>
                            <input type="text" id="pf-name" x-model="form.name" class="form-input" :class="validationErrors.some(e=>e.includes('name'))?'!border-red-400':''" placeholder="e.g. Classic Cotton T-Shirt">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="form-label" for="pf-category">Category</label>
                                <select id="pf-category" x-model="form.category" class="form-input form-select">
                                    <option value="">Select category…</option>
                                    <template x-for="c in availableCategories" :key="c"><option x-text="c" :value="c"></option></template>
                                </select>
                            </div>
                            <div>
                                <label class="form-label" for="pf-emoji">Icon / Emoji</label>
                                <input type="text" id="pf-emoji" x-model="form.emoji" class="form-input text-center text-xl" placeholder="📦" maxlength="4">
                            </div>
                        </div>
                        <div>
                            <label class="form-label" for="pf-desc">Description</label>
                            <textarea id="pf-desc" x-model="form.description" class="form-input" rows="2" placeholder="Optional product description…"></textarea>
                        </div>
                    </div>

                    {{-- VARIANT TOGGLE CARD --}}
                    <div class="card p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">Product has Variants</p>
                                <p class="text-[12px] text-slate-400 mt-0.5">Enable for products with sizes, colors, or other options.</p>
                            </div>
                            <button type="button" @click="requestToggleVariants()"
                                class="relative inline-flex h-6 w-11 rounded-full transition-colors duration-200 focus:outline-none flex-shrink-0"
                                :class="form.hasVariants ? 'bg-blue-500' : 'bg-slate-200'" id="variant-toggle">
                                <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow-md transition-transform duration-200 mt-0.5 ml-0.5" :class="form.hasVariants?'translate-x-5':'translate-x-0'"></span>
                            </button>
                        </div>

                        {{-- Simple product fields --}}
                        <template x-if="!form.hasVariants">
                            <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-2 gap-3">
                                <div>
                                    <label class="form-label" for="pf-price">Price ($) <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[13px]">$</span>
                                        <input type="number" id="pf-price" x-model="form.price" class="form-input !pl-6" :class="validationErrors.some(e=>e.includes('Price')&&e.includes('required'))?'!border-red-400':''" placeholder="0.00" step="0.01" min="0">
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label" for="pf-cprice">Compare Price ($)</label>
                                    <div class="relative">
                                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[13px]">$</span>
                                        <input type="number" id="pf-cprice" x-model="form.comparePrice" class="form-input !pl-6" placeholder="0.00" step="0.01" min="0">
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label" for="pf-sku">SKU</label>
                                    <input type="text" id="pf-sku" x-model="form.sku" class="form-input font-mono" placeholder="PROD-001">
                                </div>
                                <div>
                                    <label class="form-label" for="pf-barcode">Barcode</label>
                                    <input type="text" id="pf-barcode" x-model="form.barcode" class="form-input font-mono" placeholder="Optional">
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- ATTRIBUTE BUILDER --}}
                    <template x-if="form.hasVariants">
                        <div class="card p-5 space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-md bg-violet-100 flex items-center justify-center"><svg class="w-3 h-3 text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg></span>
                                    Attributes
                                    <span x-show="attributes.length > 0" class="text-[10px] text-slate-400 font-normal" x-text="attributes.filter(a=>a.name).length + ' defined'"></span>
                                </h3>
                                <button type="button" @click="addAttribute()" class="btn btn-secondary btn-sm gap-1.5 text-violet-600 border-violet-200 hover:bg-violet-50" id="add-attribute-btn">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                    Add Attribute
                                </button>
                            </div>

                            <template x-if="attributes.length === 0">
                                <div class="rounded-xl border-2 border-dashed border-slate-200 p-8 text-center">
                                    <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg>
                                    <p class="text-sm text-slate-400 font-medium">No attributes yet</p>
                                    <p class="text-[12px] text-slate-400 mt-1">Add Color, Size, Material, etc.</p>
                                </div>
                            </template>

                            <div class="space-y-3">
                                <template x-for="(attr, ai) in attributes" :key="attr._key">
                                    <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-4 space-y-3 transition-all"
                                         :class="highlightAttr === ai ? 'border-violet-400 bg-violet-50/40 shadow-sm' : ''">
                                        {{-- Attribute Name Row --}}
                                        <div class="flex items-center gap-2">
                                            <input type="text" x-model="attr.name"
                                                @change="requestRegenVariants()"
                                                class="form-input bg-white text-sm font-medium flex-1"
                                                :placeholder="'Attribute '+(ai+1)+' (e.g. Color, Size)'"
                                                :id="'attr-name-'+ai">
                                            <button type="button" @click="removeAttribute(ai)"
                                                class="btn btn-ghost btn-icon btn-sm text-slate-400 hover:text-red-500 flex-shrink-0">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>

                                        {{-- Value chips --}}
                                        <div>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Values</p>
                                            <div class="flex flex-wrap gap-1.5 min-h-[2rem] items-start">
                                                <template x-for="(val, vi) in attr.values" :key="vi">
                                                    <span @click="highlightVariantsByAttrValue(ai, vi)"
                                                          class="group inline-flex items-center gap-1.5 pl-2.5 pr-1.5 py-1 rounded-full text-[12px] font-medium bg-white border border-slate-200 text-slate-700 shadow-sm hover:border-violet-400 hover:bg-violet-50 transition-all cursor-pointer select-none"
                                                          :class="highlightAttr===ai && highlightVal===vi ? 'border-violet-500 bg-violet-100 text-violet-800' : ''"
                                                          :title="'Click to highlight variants with '+val">
                                                        <span x-text="val"></span>
                                                        <button type="button" @click.stop="removeValue(ai, vi)"
                                                            class="w-3.5 h-3.5 rounded-full flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all flex-shrink-0">
                                                            <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        </button>
                                                    </span>
                                                </template>

                                                {{-- Inline add --}}
                                                <div class="flex items-center gap-1" x-show="attr._adding" x-cloak>
                                                    <input type="text"
                                                        x-model="attr._newVal"
                                                        @keydown.enter.prevent="addValue(ai)"
                                                        @keydown.escape="attr._adding=false;attr._newVal=''"
                                                        @blur="handleValueBlur(ai)"
                                                        class="px-2.5 py-1 rounded-full text-[12px] border border-blue-300 bg-blue-50 outline-none min-w-[5rem] max-w-[10rem] font-medium"
                                                        :id="'attr-val-input-'+ai"
                                                        placeholder="Value(s)… or ,sep">
                                                </div>

                                                <button type="button" @click="startAddValue(ai)"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[12px] font-medium text-blue-500 border border-dashed border-blue-300 hover:bg-blue-50 hover:border-blue-400 transition-colors"
                                                    :id="'add-value-'+ai">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                                    Add value
                                                </button>
                                            </div>
                                            <p class="text-[10px] text-slate-300 mt-1.5" x-show="attr._adding" x-cloak>Press <kbd class="kbd">Enter</kbd> to add, or use commas: <em>Red, Blue, Green</em></p>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            {{-- Variant count + regen warning --}}
                            <template x-if="variants.length > 0">
                                <div class="flex items-center gap-2 pt-1">
                                    <div class="flex-1 h-px bg-slate-200"></div>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[12px] font-semibold bg-violet-100 text-violet-700 border border-violet-200/80 whitespace-nowrap">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span x-text="variants.length+' variants generated'"></span>
                                    </span>
                                    <div class="flex-1 h-px bg-slate-200"></div>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- DEFAULT PRICE + SAME-PRICE TOGGLE --}}
                    <template x-if="form.hasVariants && variants.length > 0">
                        <div class="card p-5 space-y-4">
                            <div class="flex items-center justify-between flex-wrap gap-3">
                                <h3 class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-md bg-emerald-100 flex items-center justify-center"><svg class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                                    Pricing
                                </h3>
                                {{-- Same price toggle --}}
                                <label class="flex items-center gap-2 cursor-pointer select-none" for="same-price-toggle">
                                    <span class="text-[12px] font-medium text-slate-600">Same price for all variants</span>
                                    <button type="button" id="same-price-toggle"
                                        @click="toggleSamePrice()"
                                        class="relative inline-flex h-5 w-9 rounded-full transition-colors flex-shrink-0"
                                        :class="samePrice ? 'bg-emerald-500' : 'bg-slate-200'">
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform mt-0.5 ml-0.5" :class="samePrice?'translate-x-4':'translate-x-0'"></span>
                                    </button>
                                </label>
                            </div>

                            {{-- Default price (same price mode) --}}
                            <template x-if="samePrice">
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="form-label">Default Price for All Variants <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[13px]">$</span>
                                            <input type="number" x-model="defaultPrice" @input="syncSamePrice()" class="form-input !pl-6" id="default-price-input" placeholder="0.00" step="0.01" min="0">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="form-label">Default Compare Price</label>
                                        <div class="relative">
                                            <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[13px]">$</span>
                                            <input type="number" x-model="defaultComparePrice" @input="syncSamePrice()" class="form-input !pl-6" id="default-compare-price-input" placeholder="0.00" step="0.01" min="0">
                                        </div>
                                    </div>
                                </div>
                            </template>

                            {{-- Bulk pricing tools (non same-price mode) --}}
                            <template x-if="!samePrice">
                                <div class="flex flex-wrap items-end gap-3 rounded-lg bg-slate-50 border border-slate-200 p-3">
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Bulk Pricing Tools</p>
                                        <div class="flex flex-wrap gap-2">
                                            {{-- Set all --}}
                                            <div class="flex items-center gap-1" x-data="{v:'',open:false}">
                                                <template x-if="open">
                                                    <div class="flex items-center gap-1">
                                                        <div class="relative"><span class="absolute left-2 top-1/2 -translate-y-1/2 text-[12px] text-slate-400">$</span><input type="number" x-model="v" class="form-input !pl-5 !py-1.5 !text-[12px] w-24" id="bulk-set-input" placeholder="Price…" step="0.01" min="0"></div>
                                                        <button type="button" @click="applyBulkPrice('set', v); open=false; v=''" class="btn btn-primary btn-sm">Set All</button>
                                                        <button type="button" @click="open=false" class="btn btn-secondary btn-sm">✕</button>
                                                    </div>
                                                </template>
                                                <button type="button" x-show="!open" @click="open=true" class="btn btn-secondary btn-sm gap-1" id="bulk-set-btn">
                                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Set price for all
                                                </button>
                                            </div>

                                            {{-- Increase by % --}}
                                            <div class="flex items-center gap-1" x-data="{v:'',open:false}">
                                                <template x-if="open">
                                                    <div class="flex items-center gap-1">
                                                        <div class="relative"><input type="number" x-model="v" class="form-input !pr-6 !py-1.5 !text-[12px] w-20" id="bulk-pct-input" placeholder="%" step="0.1" min="0"><span class="absolute right-2 top-1/2 -translate-y-1/2 text-[12px] text-slate-400">%</span></div>
                                                        <button type="button" @click="applyBulkPrice('pct', v); open=false; v=''" class="btn btn-secondary btn-sm">+ % All</button>
                                                        <button type="button" @click="open=false" class="btn btn-secondary btn-sm">✕</button>
                                                    </div>
                                                </template>
                                                <button type="button" x-show="!open" @click="open=true" class="btn btn-secondary btn-sm gap-1" id="bulk-pct-btn">
                                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-4.125-2.625-4.125 2.25-4.125-2.25L3 21.75V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z"/></svg>
                                                    Increase by %
                                                </button>
                                            </div>

                                            {{-- Increase by fixed --}}
                                            <div class="flex items-center gap-1" x-data="{v:'',open:false}">
                                                <template x-if="open">
                                                    <div class="flex items-center gap-1">
                                                        <div class="relative"><span class="absolute left-2 top-1/2 -translate-y-1/2 text-[12px] text-slate-400">$</span><input type="number" x-model="v" class="form-input !pl-5 !py-1.5 !text-[12px] w-24" id="bulk-fixed-input" placeholder="Amount" step="0.01"></div>
                                                        <button type="button" @click="applyBulkPrice('fixed', v); open=false; v=''" class="btn btn-secondary btn-sm">+ $ All</button>
                                                        <button type="button" @click="open=false" class="btn btn-secondary btn-sm">✕</button>
                                                    </div>
                                                </template>
                                                <button type="button" x-show="!open" @click="open=true" class="btn btn-secondary btn-sm gap-1" id="bulk-fixed-btn">
                                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                                                    Increase by $
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- VARIANT TABLE --}}
                    <template x-if="form.hasVariants && variants.length > 0">
                        <div class="card overflow-hidden">
                            <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 flex-wrap gap-2">
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-800">Variant Details</h3>
                                    <p class="text-[11px] text-slate-400" x-text="filteredVariants.length+' of '+variants.length+' variant'+(variants.length!==1?'s':'')+(variantSearch||!variantShowInactive?' shown':'')"></p>
                                </div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    {{-- Variant search --}}
                                    <div class="relative">
                                        <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                                        <input type="search" x-model="variantSearch" placeholder="Filter variants…" class="form-input !pl-8 !py-1.5 !text-[12px] w-36" id="variant-search-input">
                                    </div>
                                    {{-- Show inactive toggle --}}
                                    <label class="flex items-center gap-1.5 cursor-pointer select-none">
                                        <input type="checkbox" x-model="variantShowInactive" class="w-3.5 h-3.5 rounded accent-blue-500" id="show-inactive-chk">
                                        <span class="text-[11px] text-slate-500">Show inactive</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Sticky, scrollable variant table --}}
                            <div class="max-h-[480px] overflow-y-auto">
                                <table class="table w-full" id="variants-table">
                                    <thead class="sticky top-0 z-10">
                                        <tr>
                                            <th class="!bg-slate-100 !text-[10px] w-36">Variant</th>
                                            <th class="!bg-slate-100 !text-[10px] w-32">SKU <span class="text-red-400">*</span></th>
                                            <th class="!bg-slate-100 !text-[10px] w-28">Barcode</th>
                                            <th class="!bg-slate-100 !text-[10px] w-26">Price ($) <span class="text-red-400">*</span></th>
                                            <th class="!bg-slate-100 !text-[10px] w-26">Compare ($)</th>
                                            <th class="!bg-slate-100 !text-[10px] w-16 text-center">Active</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr x-show="filteredVariants.length === 0">
                                            <td colspan="6" class="text-center py-6 text-[12px] text-slate-400">No variants match your filter.</td>
                                        </tr>
                                        <template x-for="(v, vi) in filteredVariants" :key="v._idx">
                                            <tr class="transition-colors"
                                                :class="[
                                                    !v.active ? 'opacity-40' : '',
                                                    isHighlightedVariant(v) ? '!bg-violet-50' : '',
                                                ]">
                                                <td class="!py-2">
                                                    <div class="flex flex-wrap gap-1">
                                                        <template x-for="(part, pi) in v.name.split(' / ')" :key="pi">
                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200" x-text="part"></span>
                                                        </template>
                                                    </div>
                                                </td>
                                                <td class="!py-2">
                                                    <div>
                                                        <input type="text"
                                                            x-model="variants[v._idx].sku"
                                                            @blur="validateSkus()"
                                                            class="form-input font-mono !py-1 !text-[11px] w-full"
                                                            :class="skuErrors.includes(v._idx) ? '!border-red-400 !bg-red-50' : ''"
                                                            :id="'vsku-'+v._idx"
                                                            placeholder="SKU">
                                                        <p x-show="skuErrors.includes(v._idx)" class="text-[9px] text-red-500 mt-0.5">Duplicate SKU</p>
                                                    </div>
                                                </td>
                                                <td class="!py-2">
                                                    <input type="text" x-model="variants[v._idx].barcode" class="form-input font-mono !py-1 !text-[11px] w-full" :id="'vbar-'+v._idx" placeholder="Optional">
                                                </td>
                                                <td class="!py-2">
                                                    <div class="relative">
                                                        <span class="absolute left-2 top-1/2 -translate-y-1/2 text-slate-400 text-[11px]">$</span>
                                                        <input type="number"
                                                            x-model="variants[v._idx].price"
                                                            :readonly="samePrice"
                                                            class="form-input !pl-5 !py-1 !text-[11px] w-full"
                                                            :class="[
                                                                samePrice ? '!bg-slate-50 !text-slate-400 cursor-not-allowed' : '',
                                                                validationErrors.some(e=>e.includes('SKU') || (e.includes('price') && e.includes(v.name))) ? '!border-red-300' : ''
                                                            ]"
                                                            :id="'vprice-'+v._idx"
                                                            step="0.01" min="0" placeholder="0.00">
                                                    </div>
                                                </td>
                                                <td class="!py-2">
                                                    <div class="relative">
                                                        <span class="absolute left-2 top-1/2 -translate-y-1/2 text-slate-400 text-[11px]">$</span>
                                                        <input type="number"
                                                            x-model="variants[v._idx].comparePrice"
                                                            :readonly="samePrice"
                                                            class="form-input !pl-5 !py-1 !text-[11px] w-full"
                                                            :class="samePrice ? '!bg-slate-50 !text-slate-400 cursor-not-allowed' : ''"
                                                            :id="'vcmp-'+v._idx"
                                                            step="0.01" min="0" placeholder="0.00">
                                                    </div>
                                                </td>
                                                <td class="!py-2 text-center">
                                                    <button type="button"
                                                        @click="variants[v._idx].active = !variants[v._idx].active"
                                                        class="relative inline-flex h-5 w-9 rounded-full transition-colors flex-shrink-0 mx-auto"
                                                        :class="v.active ? 'bg-emerald-500' : 'bg-slate-200'"
                                                        :id="'vtoggle-'+v._idx">
                                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform mt-0.5 ml-0.5" :class="v.active?'translate-x-4':'translate-x-0'"></span>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>

                </div>

                {{-- ────────────────────────────
                     RIGHT COLUMN
                ──────────────────────────── --}}
                <div class="xl:col-span-1 space-y-4">

                    {{-- Save panel --}}
                    <div class="card p-4 space-y-3 top-4">
                        <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Actions</h3>
                        <button type="button" @click="saveProduct()" class="w-full btn btn-primary justify-center gap-2" id="save-product-btn">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            <span x-text="isEdit ? 'Update Product' : 'Save Product'"></span>
                        </button>
                        <button type="button" @click="cancelForm()" class="w-full btn btn-secondary justify-center">Discard Changes</button>

                        {{-- Summary --}}
                        <div class="pt-3 border-t border-slate-100 space-y-2">
                            <div class="flex justify-between text-[12px]">
                                <span class="text-slate-400">Type</span>
                                <span class="font-semibold text-slate-700" x-text="form.hasVariants ? 'Variant product' : 'Simple product'"></span>
                            </div>
                            <template x-if="form.hasVariants">
                                <div class="space-y-2">
                                    <div class="flex justify-between text-[12px]">
                                        <span class="text-slate-400">Attributes</span>
                                        <span class="font-semibold text-slate-700" x-text="attributes.filter(a=>a.name).length"></span>
                                    </div>
                                    <div class="flex justify-between text-[12px]">
                                        <span class="text-slate-400">Variants</span>
                                        <span class="font-semibold text-violet-600" x-text="variants.length"></span>
                                    </div>
                                    <div class="flex justify-between text-[12px]">
                                        <span class="text-slate-400">Active</span>
                                        <span class="font-semibold text-emerald-600" x-text="variants.filter(v=>v.active).length"></span>
                                    </div>
                                    <div class="flex justify-between text-[12px]" x-show="skuErrors.length > 0">
                                        <span class="text-red-500">Duplicate SKUs</span>
                                        <span class="font-semibold text-red-600" x-text="skuErrors.length"></span>
                                    </div>
                                </div>
                            </template>
                            <template x-if="!form.hasVariants && form.price">
                                <div class="flex justify-between text-[12px]">
                                    <span class="text-slate-400">Price</span>
                                    <span class="font-semibold text-slate-700" x-text="'$'+parseFloat(form.price).toFixed(2)"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Attribute preview (clickable) --}}
                    <template x-if="form.hasVariants && attributes.some(a=>a.name && a.values.length > 0)">
                        <div class="card p-4 space-y-3">
                            <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Attribute Preview
                                <span class="lowercase font-normal normal-case text-slate-300 ml-1">click value to highlight</span>
                            </h3>
                            <template x-for="(attr, ai) in attributes.filter(a=>a.name)" :key="attr._key">
                                <div>
                                    <p class="text-[11px] font-semibold text-slate-600 mb-1.5" x-text="attr.name"></p>
                                    <div class="flex flex-wrap gap-1">
                                        <template x-for="(val, vi) in attr.values" :key="val">
                                            <button type="button"
                                                @click="highlightVariantsByAttrValue(ai, vi)"
                                                class="px-2 py-0.5 rounded-full text-[11px] border transition-all cursor-pointer"
                                                :class="highlightAttr===ai && highlightVal===vi
                                                    ? 'bg-violet-500 text-white border-violet-500'
                                                    : 'bg-slate-100 text-slate-600 border-slate-200 hover:border-violet-400 hover:bg-violet-50'"
                                                x-text="val"
                                            ></button>
                                        </template>
                                    </div>
                                </div>
                            </template>
                            <button type="button" x-show="highlightAttr !== null" @click="clearHighlight()"
                                class="text-[11px] text-slate-400 hover:text-slate-600 underline underline-offset-2">
                                Clear filter
                            </button>
                        </div>
                    </template>

                    {{-- Tips --}}
                    <div class="rounded-xl bg-blue-50 border border-blue-200/80 p-4 space-y-2">
                        <p class="text-[11px] font-bold text-blue-600 uppercase tracking-wide">💡 Tips</p>
                        <ul class="space-y-1.5">
                            <li class="text-[11px] text-blue-700 flex gap-1.5"><span class="flex-shrink-0">•</span>Type <strong>comma-separated values</strong> (Red, Blue) to add multiple at once.</li>
                            <li class="text-[11px] text-blue-700 flex gap-1.5"><span class="flex-shrink-0">•</span>Press <strong>Enter</strong> in the value input to confirm.</li>
                            <li class="text-[11px] text-blue-700 flex gap-1.5"><span class="flex-shrink-0">•</span><strong>Click</strong> any attribute chip to highlight matching variants.</li>
                            <li class="text-[11px] text-blue-700 flex gap-1.5"><span class="flex-shrink-0">•</span>SKUs are <strong>auto-generated</strong> but can be edited manually.</li>
                            <li class="text-[11px] text-blue-700 flex gap-1.5"><span class="flex-shrink-0">•</span>Stock is managed per-branch in <strong>Inventory → Stock</strong>.</li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </template>

    {{-- ========================================================
         REGENATION CONFIRMATION MODAL
    ======================================================== --}}
    <div x-show="regenModal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" @click="regenModal.open=false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-sm"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            id="regen-modal">
            <div class="p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Regenerate Variants?</h3>
                <p class="text-[12px] text-slate-500 mt-2 leading-relaxed">Changing attributes will <strong>regenerate all variants</strong>. Existing SKUs, prices, and barcodes for <em>new</em> combinations will be reset.</p>
                <p class="text-[11px] text-slate-400 mt-1">Unchanged variants will keep their data.</p>
                <div class="flex gap-3 mt-5">
                    <button @click="regenModal.open=false; regenModal.callback=null" class="flex-1 btn btn-secondary" id="regen-cancel-btn">Keep Data</button>
                    <button @click="confirmRegen()" class="flex-1 btn btn-primary bg-amber-500 border-amber-500 hover:bg-amber-600 hover:border-amber-600" id="regen-confirm-btn">Regenerate</button>
                </div>
            </div>
        </div>
    </div>

    {{-- DISABLE VARIANTS CONFIRMATION MODAL --}}
    <div x-show="disableVariantsModal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" @click="disableVariantsModal.open=false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-sm"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            id="disable-variants-modal">
            <div class="p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Remove All Variants?</h3>
                <p class="text-[12px] text-slate-500 mt-2 leading-relaxed">Turning off variants will <strong>delete all <span x-text="variants.length"></span> generated variants</strong> and their SKU/price data.</p>
                <p class="text-[11px] text-slate-400 mt-1">This action cannot be undone in the current session.</p>
                <div class="flex gap-3 mt-5">
                    <button @click="disableVariantsModal.open=false" class="flex-1 btn btn-secondary" id="keep-variants-btn">Keep Variants</button>
                    <button @click="confirmDisableVariants()" class="flex-1 btn btn-danger" id="confirm-disable-variants-btn">Yes, Remove All</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function productsManager() {
    return {
        // ─── View ───────────────────────────────
        view: 'list',
        isEdit: false,
        editingId: null,

        // ─── List ───────────────────────────────
        search: '', filterCat: '', filterVariant: '',

        // ─── Form ───────────────────────────────
        form: { name:'', category:'', description:'', emoji:'📦', hasVariants:false, price:'', comparePrice:'', sku:'', barcode:'' },
        attributes: [],
        variants: [],
        skuErrors: [],
        validationErrors: [],

        // ─── Pricing ────────────────────────────
        samePrice: false,
        defaultPrice: '',
        defaultComparePrice: '',

        // ─── Variant table UX ───────────────────
        variantSearch: '',
        variantShowInactive: true,
        highlightAttr: null,
        highlightVal: null,

        // ─── Modals ─────────────────────────────
        regenModal: { open: false, callback: null },
        disableVariantsModal: { open: false },

        // ─── Internal state ─────────────────────
        _prevAttrHash: '',

        // ─── Data ───────────────────────────────
        availableCategories: ['Electronics','Peripherals','Accessories','Cables','Audio','Clothing','Food & Beverage'],

        products: [
            {
                id:1, name:'Classic Cotton T-Shirt', emoji:'👕', category:'Clothing', description:'Premium quality cotton t-shirt',
                hasVariants:true,
                attributes:[{name:'Color',values:['Black','White','Red']},{name:'Size',values:['S','M','L']}],
                variants:[
                    {name:'Black / S',sku:'TSH-BLK-S',barcode:'',price:'10.00',comparePrice:'14.00',active:true},
                    {name:'Black / M',sku:'TSH-BLK-M',barcode:'',price:'10.00',comparePrice:'14.00',active:true},
                    {name:'Black / L',sku:'TSH-BLK-L',barcode:'',price:'12.00',comparePrice:'16.00',active:true},
                    {name:'White / S',sku:'TSH-WHT-S',barcode:'',price:'10.00',comparePrice:'14.00',active:true},
                    {name:'White / M',sku:'TSH-WHT-M',barcode:'',price:'10.00',comparePrice:'14.00',active:true},
                    {name:'White / L',sku:'TSH-WHT-L',barcode:'',price:'12.00',comparePrice:'16.00',active:true},
                    {name:'Red / S',  sku:'TSH-RED-S',barcode:'',price:'11.00',comparePrice:'15.00',active:true},
                    {name:'Red / M',  sku:'TSH-RED-M',barcode:'',price:'11.00',comparePrice:'15.00',active:true},
                    {name:'Red / L',  sku:'TSH-RED-L',barcode:'',price:'13.00',comparePrice:'17.00',active:false},
                ],
                price:'',sku:'',barcode:'',comparePrice:''
            },
            {id:2,name:'Wireless Headphones',emoji:'🎧',category:'Electronics',description:'Premium wireless audio',hasVariants:false,attributes:[],variants:[],price:'89.99',comparePrice:'119.99',sku:'ELEC-001',barcode:''},
            {id:3,name:'USB-C Hub 7-in-1',emoji:'🔌',category:'Accessories',description:'Multi-port USB-C hub',hasVariants:false,attributes:[],variants:[],price:'49.99',comparePrice:'',sku:'ACCS-001',barcode:''},
        ],

        // ─── Computed ───────────────────────────
        get filteredProducts() {
            return this.products.filter(p => {
                const q = this.search.toLowerCase();
                return (!q || p.name.toLowerCase().includes(q) || (p.description||'').toLowerCase().includes(q))
                    && (!this.filterCat    || p.category === this.filterCat)
                    && (!this.filterVariant
                        || (this.filterVariant==='simple'  && !p.hasVariants)
                        || (this.filterVariant==='variant' &&  p.hasVariants));
            });
        },

        get filteredVariants() {
            return this.variants
                .map((v, idx) => ({...v, _idx: idx}))
                .filter(v => {
                    const matchSearch = !this.variantSearch || v.name.toLowerCase().includes(this.variantSearch.toLowerCase()) || (v.sku||'').toLowerCase().includes(this.variantSearch.toLowerCase());
                    const matchActive = this.variantShowInactive || v.active;
                    return matchSearch && matchActive;
                });
        },

        // ─── Init ───────────────────────────────
        init() {},

        // ─── List actions ───────────────────────
        openAdd() {
            this.isEdit = false; this.editingId = null;
            this.form = {name:'',category:'',description:'',emoji:'📦',hasVariants:false,price:'',comparePrice:'',sku:'',barcode:''};
            this.attributes = []; this.variants = [];
            this.skuErrors = []; this.validationErrors = [];
            this.samePrice = false; this.defaultPrice = ''; this.defaultComparePrice = '';
            this.variantSearch = ''; this.variantShowInactive = true;
            this.highlightAttr = null; this.highlightVal = null;
            this._prevAttrHash = '';
            this.view = 'form';
        },

        openEdit(product) {
            this.isEdit = true; this.editingId = product.id;
            this.form = {name:product.name,category:product.category,description:product.description,emoji:product.emoji,hasVariants:product.hasVariants,price:product.price,comparePrice:product.comparePrice,sku:product.sku,barcode:product.barcode};
            this.attributes = (product.attributes||[]).map(a=>({name:a.name,values:[...a.values],_key:Math.random().toString(36).slice(2),_adding:false,_newVal:''}));
            this.variants   = (product.variants||[]).map(v=>({...v}));
            this.skuErrors = []; this.validationErrors = [];
            this.samePrice = false; this.defaultPrice = ''; this.defaultComparePrice = '';
            this.variantSearch = ''; this.variantShowInactive = true;
            this.highlightAttr = null; this.highlightVal = null;
            this._prevAttrHash = this._hashAttributes();
            this.view = 'form';
        },

        duplicateProduct(product) {
            const copy = JSON.parse(JSON.stringify(product));
            copy.id = Date.now(); copy.name = 'Copy of '+copy.name;
            if (copy.hasVariants) copy.variants.forEach(v=>{v.sku='CPY-'+v.sku;});
            else copy.sku = 'CPY-'+copy.sku;
            this.products.push(copy);
            window.dispatchEvent(new CustomEvent('show-toast',{detail:{message:'Product duplicated.',type:'success'}}));
        },

        deleteProduct(product) {
            if (!confirm('Delete "'+product.name+'"?')) return;
            this.products = this.products.filter(p=>p.id!==product.id);
            window.dispatchEvent(new CustomEvent('show-toast',{detail:{message:'Product deleted.',type:'info'}}));
        },

        cancelForm() { this.view = 'list'; },

        // ─── Variant toggle ─────────────────────
        requestToggleVariants() {
            if (this.form.hasVariants && this.variants.length > 0) {
                this.disableVariantsModal.open = true;
            } else {
                this.form.hasVariants = !this.form.hasVariants;
                if (this.form.hasVariants && this.attributes.length === 0) this.addAttribute();
            }
        },

        confirmDisableVariants() {
            this.form.hasVariants = false;
            this.attributes = []; this.variants = [];
            this.skuErrors = []; this.samePrice = false;
            this.disableVariantsModal.open = false;
        },

        // ─── Attribute management ────────────────
        addAttribute() {
            this.attributes.push({name:'',values:[],_key:Math.random().toString(36).slice(2),_adding:false,_newVal:''});
        },

        removeAttribute(index) {
            if (this.variants.length > 0) {
                this.regenModal.callback = () => { this.attributes.splice(index,1); this._doRegen(); };
                this.regenModal.open = true;
            } else { this.attributes.splice(index,1); this._doRegen(); }
        },

        startAddValue(ai) {
            this.attributes[ai]._adding = true;
            this.attributes[ai]._newVal = '';
            this.$nextTick(()=>{ const el=document.getElementById('attr-val-input-'+ai); if(el) el.focus(); });
        },

        addValue(ai) {
            const raw = (this.attributes[ai]._newVal||'').trim();
            if (!raw) { this.attributes[ai]._adding = false; return; }
            // Support comma-separated
            const newVals = raw.split(',').map(v=>v.trim()).filter(v=>v);
            const dupes = [];
            newVals.forEach(val => {
                if (this.attributes[ai].values.some(v=>v.toLowerCase()===val.toLowerCase())) {
                    dupes.push(val);
                } else {
                    this.attributes[ai].values.push(val);
                }
            });
            if (dupes.length) window.dispatchEvent(new CustomEvent('show-toast',{detail:{message:'"'+dupes.join(', ')+'" already exist.',type:'warning',duration:2500}}));
            this.attributes[ai]._newVal = '';
            this.attributes[ai]._adding = false;
            this.requestRegenVariants();
        },

        handleValueBlur(ai) {
            if ((this.attributes[ai]._newVal||'').trim()) {
                this.addValue(ai);
            } else {
                this.attributes[ai]._adding = false;
            }
        },

        removeValue(ai, vi) {
            this.attributes[ai].values.splice(vi,1);
            this.requestRegenVariants();
        },

        // ─── Variant regen logic ─────────────────
        _hashAttributes() {
            return JSON.stringify(this.attributes.map(a=>({name:a.name,values:[...a.values]})));
        },

        requestRegenVariants() {
            const hash = this._hashAttributes();
            if (hash === this._prevAttrHash) return;
            if (this.isEdit && this.variants.length > 0 && this._prevAttrHash !== '') {
                this.regenModal.callback = () => this._doRegen();
                this.regenModal.open = true;
            } else {
                this._doRegen();
            }
        },

        // Used when attribute name changes (less destructive — just regen)
        requestRegenVariants_silent() {
            const hash = this._hashAttributes();
            if (hash !== this._prevAttrHash) this._doRegen();
        },

        confirmRegen() {
            this.regenModal.open = false;
            if (this.regenModal.callback) this.regenModal.callback();
            this.regenModal.callback = null;
        },

        _doRegen() {
            this._prevAttrHash = this._hashAttributes();
            const validAttrs = this.attributes.filter(a=>a.name.trim()&&a.values.length>0);
            if (validAttrs.length === 0) { this.variants = []; return; }

            // Cartesian product
            const combos = this._cartesian(validAttrs.map(a=>a.values));

            // Preserve existing variant data by name
            const existingMap = {};
            this.variants.forEach(v=>{ existingMap[v.name]=v; });

            const globalPrice  = this.samePrice ? (this.defaultPrice||'')  : '';
            const globalCompare= this.samePrice ? (this.defaultComparePrice||'') : '';

            this.variants = combos.map(combo => {
                const name = combo.join(' / ');
                const existing = existingMap[name];
                // Auto-SKU: first 3 chars of each attr name + first 3 of value
                const autoSku = validAttrs.map((a,ai)=>
                    a.name.replace(/\s/g,'').substring(0,3).toUpperCase()+'-'+combo[ai].replace(/\s/g,'').substring(0,3).toUpperCase()
                ).join('-');
                return existing
                    ? {...existing, price: this.samePrice?globalPrice:existing.price, comparePrice: this.samePrice?globalCompare:existing.comparePrice}
                    : {name, sku:autoSku, barcode:'', price:globalPrice, comparePrice:globalCompare, active:true};
            });

            this.validateSkus();
        },

        _cartesian(arrays) {
            return arrays.reduce((acc,arr)=>acc.flatMap(combo=>arr.map(val=>[...combo,val])),[[]]);
        },

        // ─── Highlight ──────────────────────────
        highlightVariantsByAttrValue(ai, vi) {
            if (this.highlightAttr===ai && this.highlightVal===vi) { this.clearHighlight(); return; }
            this.highlightAttr = ai;
            this.highlightVal = vi;
        },

        clearHighlight() { this.highlightAttr=null; this.highlightVal=null; },

        isHighlightedVariant(v) {
            if (this.highlightAttr===null) return false;
            const attr = this.attributes[this.highlightAttr];
            if (!attr) return false;
            const val = attr.values[this.highlightVal];
            return v.name.split(' / ').includes(val);
        },

        // ─── SKU validation ─────────────────────
        validateSkus() {
            const skus = this.variants.map(v=>(v.sku||'').trim().toLowerCase());
            this.skuErrors = [];
            skus.forEach((sku,i)=>{ if(sku&&skus.indexOf(sku)!==i) this.skuErrors.push(i); });
        },

        // ─── Pricing ────────────────────────────
        toggleSamePrice() {
            this.samePrice = !this.samePrice;
            if (this.samePrice) {
                // initialise default price from first variant
                if (this.variants.length > 0 && this.variants[0].price) {
                    this.defaultPrice = this.variants[0].price;
                    this.defaultComparePrice = this.variants[0].comparePrice||'';
                }
                this.syncSamePrice();
            }
        },

        syncSamePrice() {
            this.variants.forEach(v => {
                v.price = this.defaultPrice;
                v.comparePrice = this.defaultComparePrice;
            });
        },

        applyBulkPrice(mode, rawVal) {
            const val = parseFloat(rawVal);
            if (isNaN(val)) return;
            this.variants.forEach(v => {
                const cur = parseFloat(v.price) || 0;
                if (mode === 'set')   v.price = val.toFixed(2);
                if (mode === 'pct')   v.price = (cur * (1 + val/100)).toFixed(2);
                if (mode === 'fixed') v.price = Math.max(0, cur + val).toFixed(2);
            });
            const labels = {set:'Price set to $'+val.toFixed(2), pct:'Prices increased by '+val+'%', fixed:'Prices increased by $'+val.toFixed(2)};
            window.dispatchEvent(new CustomEvent('show-toast',{detail:{message:labels[mode]+' for all variants.',type:'success'}}));
        },

        // ─── Save ────────────────────────────────
        saveProduct() {
            this.validationErrors = [];
            if (!this.form.name.trim()) this.validationErrors.push('Product name is required.');
            if (!this.form.hasVariants && !this.form.price) this.validationErrors.push('Price is required for simple products.');
            if (this.form.hasVariants) {
                const missingPrices = this.variants.filter(v=>v.active && !(parseFloat(v.price)>0));
                if (missingPrices.length) this.validationErrors.push('All active variants must have a price ('+missingPrices.length+' missing).');
                this.validateSkus();
                if (this.skuErrors.length) this.validationErrors.push('Fix '+this.skuErrors.length+' duplicate SKU(s) before saving.');
            }
            if (this.validationErrors.length) {
                window.dispatchEvent(new CustomEvent('show-toast',{detail:{message:'Please fix the errors above.',type:'error'}}));
                return;
            }

            const productData = {
                ...this.form,
                attributes: this.attributes.filter(a=>a.name).map(a=>({name:a.name,values:[...a.values]})),
                variants: this.form.hasVariants ? [...this.variants] : [],
            };

            if (this.isEdit) {
                const idx = this.products.findIndex(p=>p.id===this.editingId);
                if (idx>-1) this.products[idx]={...this.products[idx],...productData};
                window.dispatchEvent(new CustomEvent('show-toast',{detail:{message:'"'+productData.name+'" updated.',type:'success'}}));
            } else {
                this.products.unshift({id:Date.now(),...productData});
                window.dispatchEvent(new CustomEvent('show-toast',{detail:{message:'"'+productData.name+'" added to catalog.',type:'success'}}));
            }
            this.view = 'list';
        },
    };
}
</script>

</x-layouts.admin>
