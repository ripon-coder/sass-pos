<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS Screen — {{ config('app.name', 'POS SaaS') }}</title>
    <meta name="description" content="Point of Sale Screen — Cashier Interface">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full overflow-hidden bg-slate-100"
    x-data="posScreen()"
    x-init="init()"
    @keydown.window="handleGlobalKey($event)"
>

{{-- ================================================================ --}}
{{-- TOAST CONTAINER                                                   --}}
{{-- ================================================================ --}}
<div id="pos-toast-container"
    x-data="posToasts()"
    @pos-toast.window="add($event.detail)"
    class="fixed top-4 right-4 z-[200] flex flex-col gap-2 pointer-events-none"
>
    <template x-for="t in toasts" :key="t.id">
        <div
            x-show="t.visible"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-1 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg border min-w-[260px] max-w-xs text-sm font-medium"
            :class="{
                'bg-white border-slate-200 text-slate-700':    t.type === 'info',
                'bg-emerald-50 border-emerald-200 text-emerald-800': t.type === 'success',
                'bg-red-50 border-red-200 text-red-800':       t.type === 'error',
                'bg-amber-50 border-amber-200 text-amber-800': t.type === 'warning',
            }"
        >
            <svg x-show="t.type==='success'" class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            <svg x-show="t.type==='error'"   class="w-4 h-4 text-red-500 flex-shrink-0"     fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            <svg x-show="t.type==='warning'" class="w-4 h-4 text-amber-500 flex-shrink-0"   fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            <svg x-show="t.type==='info'"    class="w-4 h-4 text-blue-500 flex-shrink-0"    fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
            <span x-text="t.message" class="flex-1"></span>
        </div>
    </template>
</div>

{{-- ================================================================ --}}
{{-- OFFLINE INDICATOR                                                  --}}
{{-- ================================================================ --}}
<div id="offline-bar"
    x-data="{ online: navigator.onLine }"
    @online.window="online = true"
    @offline.window="online = false"
    x-show="!online"
    x-cloak
    class="fixed top-0 left-0 right-0 z-[190] flex items-center justify-center gap-2 bg-amber-500 text-white text-xs font-semibold py-2 px-4"
>
    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.584 10.587a2 2 0 002.828 2.83M8.464 8.464A5 5 0 0115.54 15.54M2.05 9.55c1.95-1.95 4.5-3.05 7.14-3.41m8.44 2.09a9.94 9.94 0 011.36 1.32M12 3a9 9 0 019 9c0 1.65-.44 3.2-1.21 4.54M12 3a9 9 0 00-9 9v.5"/></svg>
    You are offline — POS running in offline mode. Transactions will sync when reconnected.
</div>

{{-- ================================================================ --}}
{{-- MAIN LAYOUT                                                        --}}
{{-- ================================================================ --}}
<div class="flex h-full">

    {{-- ===== LEFT: PRODUCT PANEL ===== --}}
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

        {{-- ── POS HEADER ── --}}
        <header class="flex items-center gap-3 px-4 py-2.5 bg-white border-b border-slate-200 flex-shrink-0">

            {{-- Back + Logo --}}
            <a href="/dashboard" class="btn btn-ghost btn-icon btn-sm text-slate-500 flex-shrink-0" title="Back to Dashboard">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            </a>
            <div class="w-px h-5 bg-slate-200 flex-shrink-0"></div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <div class="w-6 h-6 bg-blue-600 rounded flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.943-7.143a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                </div>
                <span class="font-semibold text-slate-800 text-sm hidden sm:block">POS Screen</span>
            </div>

            {{-- Search / Barcode Input --}}
            <div class="flex-1 max-w-lg mx-2">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    <input
                        type="text"
                        x-model="search"
                        @input.debounce.200ms="filterProducts()"
                        @keydown.enter.prevent="handleSearchEnter()"
                        placeholder="Search or scan barcode…"
                        class="form-input pl-9 pr-24 py-2"
                        id="pos-search-input"
                        autocomplete="off"
                        x-ref="searchInput"
                    >
                    <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-1">
                        <kbd class="hidden sm:inline-flex items-center gap-1 px-1.5 py-0.5 text-[10px] font-semibold text-slate-400 bg-slate-100 border border-slate-200 rounded">F2</kbd>
                        <kbd class="hidden sm:inline-flex items-center gap-1 px-1.5 py-0.5 text-[10px] font-semibold text-slate-400 bg-slate-100 border border-slate-200 rounded">↵ Add</kbd>
                    </div>
                </div>
            </div>

            {{-- Keyboard shortcuts hint --}}
            <div class="hidden xl:flex items-center gap-1.5 text-[10px] text-slate-400 flex-shrink-0">
                <kbd class="px-1.5 py-0.5 bg-slate-100 border border-slate-200 rounded font-semibold">F9</kbd><span>Checkout</span>
                <span class="mx-0.5">·</span>
                <kbd class="px-1.5 py-0.5 bg-slate-100 border border-slate-200 rounded font-semibold">Esc</kbd><span>Clear</span>
            </div>

            {{-- Online status dot --}}
            <div class="flex items-center gap-1.5 flex-shrink-0"
                x-data="{ online: navigator.onLine }"
                @online.window="online = true"
                @offline.window="online = false">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                        :class="online ? 'bg-green-400' : 'bg-amber-400'"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2"
                        :class="online ? 'bg-green-500' : 'bg-amber-500'"></span>
                </span>
                <span class="text-[10px] font-medium hidden sm:block"
                    :class="online ? 'text-green-600' : 'text-amber-600'"
                    x-text="online ? 'Online' : 'Offline'">
                </span>
            </div>

        </header>

        {{-- ── CATEGORY SCROLL BAR ── --}}
        <nav class="flex-shrink-0 bg-white border-b border-slate-200"
            aria-label="Product categories">
            <div class="flex items-center gap-0 overflow-x-auto"
                style="scrollbar-width:none; -ms-overflow-style:none;"
                x-ref="categoryBar">

                {{-- All --}}
                <button
                    @click="currentCategory = null; filterProducts()"
                    id="cat-btn-all"
                    class="relative flex-shrink-0 px-4 py-2.5 text-xs font-semibold whitespace-nowrap transition-colors duration-150 focus:outline-none group"
                    :class="currentCategory === null
                        ? 'text-blue-600'
                        : 'text-slate-500 hover:text-slate-800'"
                >
                    All
                    <span class="absolute bottom-0 left-0 right-0 h-0.5 rounded-t transition-all duration-150"
                        :class="currentCategory === null ? 'bg-blue-600' : 'bg-transparent group-hover:bg-slate-200'"
                    ></span>
                </button>

                {{-- Divider --}}
                <div class="w-px h-4 bg-slate-200 flex-shrink-0 mx-1"></div>

                {{-- Category buttons --}}
                <template x-for="cat in categories" :key="cat">
                    <button
                        @click="currentCategory = cat; filterProducts()"
                        :id="'cat-btn-' + cat.toLowerCase().replace(/\s+/g,'-')"
                        class="relative flex-shrink-0 px-4 py-2.5 text-xs font-semibold whitespace-nowrap transition-colors duration-150 focus:outline-none group"
                        :class="currentCategory === cat
                            ? 'text-blue-600'
                            : 'text-slate-500 hover:text-slate-800'"
                    >
                        <span x-text="cat"></span>
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 rounded-t transition-all duration-150"
                            :class="currentCategory === cat ? 'bg-blue-600' : 'bg-transparent group-hover:bg-slate-200'"
                        ></span>
                    </button>
                </template>
            </div>
        </nav>

        {{-- ── PRODUCT GRID ── --}}
        <div class="flex-1 overflow-y-auto p-4">

            {{-- Barcode error banner --}}
            <div x-show="barcodeError"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 -translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="mb-3 flex items-center gap-2.5 px-4 py-2.5 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 font-medium" x-cloak>
                <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                <span>Product not found: "<strong x-text="barcodeError"></strong>"</span>
                <button @click="barcodeError = null; openQuickAdd()" class="ml-auto text-xs font-semibold underline underline-offset-2 hover:text-red-900">Quick Add Product</button>
                <button @click="barcodeError = null" class="text-red-400 hover:text-red-600">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- No results empty state --}}
            <div x-show="filteredProducts.length === 0 && !barcodeError"
                class="flex flex-col items-center justify-center h-56 text-center" x-cloak>
                <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                    <svg class="w-7 h-7 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </div>
                <p class="text-sm font-semibold text-slate-500">No products found</p>
                <p class="text-xs text-slate-400 mt-1">Try a different search or
                    <button @click="openQuickAdd()" class="text-blue-600 underline underline-offset-2 font-medium">add a new product</button>
                </p>
            </div>

            {{-- Product Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3" id="product-grid">
                <template x-for="(product, idx) in filteredProducts" :key="product.id">
                    <button
                        @click="addToCart(product)"
                        :id="'prod-' + product.id"
                        :disabled="product.stock === 0"
                        class="group relative flex flex-col bg-white border border-slate-200 rounded-xl text-left transition-all duration-150 overflow-hidden focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
                        :class="product.stock === 0
                            ? 'opacity-50 cursor-not-allowed'
                            : 'hover:border-blue-400 hover:shadow-md hover:-translate-y-0.5 active:scale-95 active:shadow-none cursor-pointer'"
                    >
                        {{-- Product image area --}}
                        <div class="aspect-square bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center text-3xl relative overflow-hidden">
                            <span x-text="product.emoji" class="transition-transform duration-150 group-hover:scale-110"></span>

                            {{-- Low stock dot --}}
                            <div x-show="product.stock > 0 && product.stock <= 5"
                                class="absolute top-1.5 right-1.5 flex items-center gap-1 bg-amber-100 border border-amber-300 text-amber-700 text-[9px] font-bold px-1.5 py-0.5 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block"></span>
                                <span x-text="product.stock + ' left'"></span>
                            </div>

                            {{-- Out of stock overlay --}}
                            <div x-show="product.stock === 0"
                                class="absolute inset-0 bg-white/70 flex items-center justify-center">
                                <span class="text-[10px] font-bold text-slate-500 bg-white px-2 py-0.5 rounded-full border border-slate-200 shadow-sm">Out of Stock</span>
                            </div>

                            {{-- Add overlay on hover --}}
                            <div x-show="product.stock > 0"
                                class="absolute inset-0 bg-blue-600/0 group-hover:bg-blue-600/10 transition-all duration-150 flex items-center justify-center">
                                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 scale-75 group-hover:scale-100 transition-all duration-150">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Product info --}}
                        <div class="p-2.5 flex-1">
                            <p class="text-xs font-semibold text-slate-700 line-clamp-2 leading-snug" x-text="product.name"></p>
                            <div class="flex items-center justify-between mt-1.5">
                                <p class="text-sm font-bold text-blue-600" x-text="'$' + product.price.toFixed(2)"></p>
                                <span class="text-[10px] text-slate-400 font-medium" x-text="product.stock > 0 ? product.stock + ' stk' : ''"></span>
                            </div>
                        </div>

                        {{-- In-cart indicator --}}
                        <template x-if="cart.find(i => i.id === product.id)">
                            <div class="absolute top-1.5 left-1.5 w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center shadow"
                                x-text="cart.find(i => i.id === product.id)?.qty">
                            </div>
                        </template>
                    </button>
                </template>
            </div>
        </div>
    </div>

    {{-- ===== RIGHT: CART PANEL ===== --}}
    <aside class="w-[22rem] xl:w-96 flex flex-col bg-white border-l border-slate-200 flex-shrink-0">

        {{-- ── CART HEADER ── --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 bg-slate-50/80 flex-shrink-0">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.943-7.143a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                <h2 class="text-sm font-bold text-slate-800">Cart</h2>
                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold"
                    x-text="cart.reduce((s,i)=>s+i.qty,0)"
                    x-show="cart.length > 0"
                ></span>
            </div>
            <div class="flex items-center gap-1">
                {{-- Customer selector --}}
                <div x-data="{ open: false, query: '' }" class="relative">
                    <button @click="open = !open" id="pos-customer-btn"
                        class="flex items-center gap-1.5 px-2 py-1 rounded-lg text-xs font-medium transition-colors"
                        :class="selectedCustomer ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-slate-500 hover:bg-slate-100'">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        <span x-text="selectedCustomer ? selectedCustomer.name : 'Guest'" class="max-w-[80px] truncate"></span>
                        <svg class="w-3 h-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div x-show="open" @click.outside="open = false; query = ''"
                        x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        class="absolute right-0 mt-1 w-56 bg-white border border-slate-200 rounded-xl shadow-xl z-50 py-1 overflow-hidden" x-cloak>
                        <div class="px-3 py-2 border-b border-slate-100">
                            <input type="text" x-model="query" placeholder="Search customer…"
                                class="form-input py-1 text-xs w-full" autofocus>
                        </div>
                        <div class="max-h-48 overflow-y-auto py-1">
                            <button @click="selectedCustomer = null; open = false"
                                class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 flex items-center gap-2"
                                :class="!selectedCustomer ? 'text-blue-600 font-semibold' : 'text-slate-600'">
                                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/></svg>
                                Walk-in Guest
                            </button>
                            <template x-for="c in customers.filter(c => !query || c.name.toLowerCase().includes(query.toLowerCase()))" :key="c.id">
                                <button @click="selectedCustomer = c; open = false; query = ''"
                                    class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 flex items-center gap-2"
                                    :class="selectedCustomer?.id === c.id ? 'text-blue-600 font-semibold' : 'text-slate-600'">
                                    <div class="w-5 h-5 rounded-full bg-slate-200 flex items-center justify-center text-[9px] font-bold text-slate-600 flex-shrink-0"
                                        x-text="c.name.split(' ').map(n=>n[0]).join('')"></div>
                                    <span x-text="c.name" class="truncate"></span>
                                </button>
                            </template>
                        </div>
                        <div class="border-t border-slate-100 px-3 py-2">
                            <button @click="openAddCustomer(); open = false"
                                class="w-full flex items-center gap-2 text-xs font-semibold text-blue-600 hover:text-blue-700 py-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                Add New Customer
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Clear cart --}}
                <button @click="promptClearCart()" title="Clear cart (Esc)"
                    class="btn btn-ghost btn-icon btn-sm text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                </button>
            </div>
        </div>

        {{-- ── CART ITEMS ── --}}
        <div class="flex-1 overflow-y-auto">

            {{-- Empty cart state --}}
            <div x-show="cart.length === 0"
                class="flex flex-col items-center justify-center h-full text-center px-6 py-12" x-cloak>
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.943-7.143a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                </div>
                <p class="text-sm font-semibold text-slate-500">Cart is empty</p>
                <p class="text-xs text-slate-400 mt-1">Click a product or scan a barcode</p>
                <div class="mt-4 flex items-center gap-1.5 text-[10px] text-slate-300">
                    <kbd class="px-1.5 py-0.5 bg-slate-100 border border-slate-200 rounded font-semibold">F2</kbd>
                    <span>to focus search</span>
                </div>
            </div>

            {{-- Cart Items List --}}
            <div class="divide-y divide-slate-50" x-show="cart.length > 0">
                <template x-for="item in cart" :key="item.id">
                    <div class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50/60 transition-colors group/item">

                        {{-- Product emoji thumb --}}
                        <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center text-lg flex-shrink-0 border border-slate-200" x-text="item.emoji"></div>

                        {{-- Item details --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-slate-800 truncate leading-tight" x-text="item.name"></p>
                            <p class="text-[11px] text-slate-400 mt-0.5" x-text="'$' + item.price.toFixed(2) + ' each'"></p>

                            {{-- Stock warning --}}
                            <div x-show="item.qty >= (allProducts.find(p=>p.id===item.id)?.stock || 0)"
                                class="text-[10px] text-amber-600 font-medium flex items-center gap-1 mt-0.5">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                                Max stock reached
                            </div>

                            {{-- Quantity control --}}
                            <div class="flex items-center gap-1.5 mt-2">
                                <button @click.stop="decreaseQty(item)"
                                    class="w-6 h-6 flex items-center justify-center rounded-md border border-slate-200 hover:border-red-300 hover:bg-red-50 hover:text-red-600 text-slate-500 transition-colors text-sm font-bold">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" d="M5 12h14"/></svg>
                                </button>

                                {{-- Editable qty input --}}
                                <input type="number" min="1"
                                    :max="allProducts.find(p=>p.id===item.id)?.stock || 99"
                                    x-model.number="item.qty"
                                    @change="clampQty(item)"
                                    @click.stop
                                    class="w-10 h-6 text-center text-xs font-bold text-slate-800 border border-slate-200 rounded-md focus:ring-1 focus:ring-blue-400 focus:border-blue-400 bg-white"
                                >

                                <button @click.stop="increaseQty(item)"
                                    class="w-6 h-6 flex items-center justify-center rounded-md border border-slate-200 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600 text-slate-500 transition-colors">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Line total + remove --}}
                        <div class="text-right flex-shrink-0">
                            <p class="text-sm font-bold text-slate-800" x-text="'$' + (item.price * item.qty).toFixed(2)"></p>
                            <button @click.stop="removeFromCart(item)"
                                class="mt-1 text-slate-200 hover:text-red-400 transition-colors opacity-0 group-hover/item:opacity-100">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- ── CART FOOTER ── --}}
        <div class="border-t border-slate-200 px-4 pt-3 pb-4 space-y-3 flex-shrink-0 bg-white">

            {{-- Discount section --}}
            <div x-data="{ showDiscount: discountPercent > 0 }" class="space-y-2">
                <div class="flex items-center justify-between">
                    <button @click="showDiscount = !showDiscount"
                        class="flex items-center gap-1.5 text-xs font-semibold transition-colors"
                        :class="discountPercent > 0 ? 'text-emerald-600' : 'text-slate-400 hover:text-blue-600'">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185zM9.75 9h.008v.008H9.75V9zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 4.5h.008v.008h-.008V13.5zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        <span x-text="discountPercent > 0 ? 'Discount: ' + discountPercent + '% applied' : 'Add Discount'"></span>
                    </button>

                    {{-- Tax toggle --}}
                    <button @click="taxEnabled = !taxEnabled"
                        class="flex items-center gap-1.5 text-xs font-medium transition-colors"
                        :class="taxEnabled ? 'text-slate-600' : 'text-slate-400'">
                        <div class="relative w-7 h-4 rounded-full transition-colors duration-150" :class="taxEnabled ? 'bg-blue-600' : 'bg-slate-200'">
                            <div class="absolute top-0.5 left-0.5 w-3 h-3 rounded-full bg-white shadow transition-transform duration-150" :class="taxEnabled ? 'translate-x-3' : 'translate-x-0'"></div>
                        </div>
                        Tax
                    </button>
                </div>

                {{-- Discount controls --}}
                <div x-show="showDiscount"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-2" x-cloak>

                    {{-- Quick discount buttons --}}
                    <div class="flex gap-2">
                        <template x-for="d in [5, 10, 15, 20]" :key="d">
                            <button @click="discountPercent = discountPercent === d ? 0 : d"
                                class="flex-1 py-1 text-xs font-bold rounded-lg border transition-all duration-100"
                                :class="discountPercent === d
                                    ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm'
                                    : 'bg-white text-slate-600 border-slate-200 hover:border-emerald-400 hover:text-emerald-600'"
                                x-text="d + '%'"
                            ></button>
                        </template>
                    </div>

                    {{-- Manual discount input --}}
                    <div class="flex items-center gap-2">
                        <input type="number" x-model.number="discountPercent" min="0" max="100"
                            placeholder="Custom %" id="pos-discount-input"
                            class="form-input flex-1 text-sm text-center py-1.5 font-semibold"
                            @input="discountPercent = Math.min(100, Math.max(0, discountPercent))">
                        <span class="text-sm text-slate-500 font-medium">% off</span>
                        <button x-show="discountPercent > 0" @click="discountPercent = 0"
                            class="text-slate-300 hover:text-red-400 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Order summary --}}
            <div class="space-y-1.5 text-sm bg-slate-50 rounded-xl px-3 py-3 border border-slate-100">
                <div class="flex justify-between text-slate-500">
                    <span>Subtotal</span>
                    <span x-text="'$' + subtotal.toFixed(2)"></span>
                </div>
                <div class="flex justify-between text-emerald-700" x-show="discountAmount > 0">
                    <span x-text="'Discount (' + discountPercent + '%)'"></span>
                    <span x-text="'− $' + discountAmount.toFixed(2)"></span>
                </div>
                <div class="flex justify-between text-slate-500" x-show="taxEnabled">
                    <span>Tax (8.5%)</span>
                    <span x-text="'$' + taxAmount.toFixed(2)"></span>
                </div>
                <div class="flex justify-between font-bold text-slate-900 text-base pt-2 border-t border-slate-200">
                    <span>Total</span>
                    <span x-text="'$' + total.toFixed(2)"></span>
                </div>
            </div>

            {{-- Pay Buttons --}}
            <div class="grid grid-cols-2 gap-2">
                <button @click="openPaymentModal('cash')" :disabled="cart.length === 0" id="pos-pay-cash-btn"
                    class="btn btn-secondary justify-center gap-1.5 text-sm transition-all"
                    :class="cart.length === 0 ? 'opacity-40 cursor-not-allowed' : 'hover:border-emerald-400 hover:text-emerald-700'">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                    Cash
                </button>
                <button @click="openPaymentModal('card')" :disabled="cart.length === 0" id="pos-pay-card-btn"
                    class="btn btn-primary justify-center gap-1.5 text-sm relative"
                    :class="cart.length === 0 ? 'opacity-40 cursor-not-allowed' : ''">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                    Card
                    <kbd class="hidden xl:inline-flex absolute right-2 top-1/2 -translate-y-1/2 text-[9px] px-1 py-0.5 bg-blue-700 rounded font-semibold">F9</kbd>
                </button>
            </div>
        </div>
    </aside>
</div>

{{-- ================================================================ --}}
{{-- PAYMENT / CHECKOUT MODAL                                          --}}
{{-- ================================================================ --}}
<div x-show="paymentModal.open" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
    <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" @click="paymentModal.success || (paymentModal.open = false)"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        id="payment-modal" @keydown.escape.window="paymentModal.success || (paymentModal.open = false)">

        {{-- SUCCESS STATE --}}
        <div x-show="paymentModal.success"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            class="flex flex-col items-center justify-center p-10 text-center" x-cloak>
            <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mb-5">
                <svg class="w-10 h-10 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-slate-900">Payment Complete!</h2>
            <p class="text-sm text-slate-500 mt-1" x-text="'Order #' + paymentModal.orderNo"></p>

            <div class="mt-4 w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-left space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Amount Charged</span>
                    <span class="font-bold text-slate-800" x-text="'$' + total.toFixed(2)"></span>
                </div>
                <div class="flex justify-between text-sm" x-show="selectedCustomer">
                    <span class="text-slate-500">Customer</span>
                    <span class="font-semibold text-slate-700" x-text="selectedCustomer?.name"></span>
                </div>
                <div class="flex justify-between text-sm" x-show="paymentModal.method === 'cash' && paymentModal.cashTendered > 0">
                    <span class="text-slate-500">Cash Tendered</span>
                    <span class="font-semibold text-slate-700" x-text="'$' + parseFloat(paymentModal.cashTendered).toFixed(2)"></span>
                </div>
                <div x-show="paymentModal.method === 'cash' && paymentModal.change > 0"
                    class="flex justify-between text-sm pt-2 border-t border-slate-200">
                    <span class="text-slate-500">Change Due</span>
                    <span class="font-black text-emerald-600 text-base" x-text="'$' + paymentModal.change.toFixed(2)"></span>
                </div>
            </div>

            <div class="flex gap-3 mt-6 w-full">
                <button @click="printReceipt()" class="flex-1 btn btn-secondary gap-2 justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a.75.75 0 01-.875.854 49.188 49.188 0 01-14.01 0 .75.75 0 01-.875-.854L2.34 18m15.32 0H6.34"/></svg>
                    Print
                </button>
                <button @click="resetPOS()" class="flex-1 btn btn-primary justify-center gap-2" id="new-sale-btn">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    New Sale
                </button>
            </div>
        </div>

        {{-- PAYMENT FORM --}}
        <div x-show="!paymentModal.success">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    {{-- Method toggle --}}
                    <div class="flex bg-slate-100 rounded-lg p-1 gap-1">
                        <button @click="paymentModal.method = 'cash'"
                            class="px-3 py-1.5 text-xs font-semibold rounded-md transition-all duration-100"
                            :class="paymentModal.method === 'cash' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'">
                            💵 Cash
                        </button>
                        <button @click="paymentModal.method = 'card'"
                            class="px-3 py-1.5 text-xs font-semibold rounded-md transition-all duration-100"
                            :class="paymentModal.method === 'card' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'">
                            💳 Card
                        </button>
                    </div>
                    <h2 class="text-base font-bold text-slate-800" x-text="paymentModal.method === 'cash' ? 'Cash Payment' : 'Card Payment'"></h2>
                </div>
                <button @click="paymentModal.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 space-y-5">
                {{-- Order summary inside modal --}}
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-2 text-sm">
                    <div class="flex justify-between text-slate-500">
                        <span>Subtotal</span><span x-text="'$' + subtotal.toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between text-emerald-700" x-show="discountAmount > 0">
                        <span x-text="'Discount (' + discountPercent + '%)'"></span>
                        <span x-text="'− $' + discountAmount.toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between text-slate-500" x-show="taxEnabled">
                        <span>Tax (8.5%)</span><span x-text="'$' + taxAmount.toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between font-black text-slate-900 text-lg pt-2 border-t border-slate-200">
                        <span>Amount Due</span>
                        <span x-text="'$' + total.toFixed(2)"></span>
                    </div>
                </div>

                {{-- Customer display --}}
                <div x-show="selectedCustomer" class="flex items-center gap-2 text-xs text-slate-600 bg-blue-50 border border-blue-100 px-3 py-2 rounded-lg">
                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/></svg>
                    Customer: <strong x-text="selectedCustomer?.name"></strong>
                </div>

                {{-- CASH PAYMENT --}}
                <div x-show="paymentModal.method === 'cash'" class="space-y-3">
                    <div>
                        <label class="form-label text-xs font-semibold text-slate-600 mb-1.5 block">Cash Tendered</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-semibold">$</span>
                            <input type="number"
                                x-model.number="paymentModal.cashTendered"
                                @input="calculateChange()"
                                @keydown.enter="canPay && processPayment()"
                                placeholder="0.00"
                                class="form-input pl-7 text-xl font-black tracking-tight text-slate-900"
                                id="cash-tendered-input"
                                step="0.01" min="0"
                                x-ref="cashInput"
                                autofocus
                            >
                        </div>
                    </div>

                    {{-- Quick amounts --}}
                    <div class="grid grid-cols-5 gap-2">
                        <template x-for="amt in quickAmounts" :key="amt">
                            <button @click="paymentModal.cashTendered = amt; calculateChange()"
                                class="py-2 text-sm font-bold rounded-xl border transition-all duration-100 text-center"
                                :class="paymentModal.cashTendered === amt
                                    ? 'bg-slate-800 text-white border-slate-800'
                                    : 'bg-white text-slate-700 border-slate-200 hover:border-slate-400'"
                                x-text="'$' + amt"
                            ></button>
                        </template>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <button @click="paymentModal.cashTendered = total; calculateChange()"
                            class="py-2 text-xs font-bold rounded-xl border border-slate-200 bg-white text-slate-700 hover:border-blue-400 hover:text-blue-600 transition-all">
                            Exact Amount
                        </button>
                        <button @click="paymentModal.cashTendered = Math.ceil(total); calculateChange()"
                            class="py-2 text-xs font-bold rounded-xl border border-slate-200 bg-white text-slate-700 hover:border-blue-400 hover:text-blue-600 transition-all"
                            x-text="'Round up ($' + Math.ceil(total) + ')'">
                        </button>
                    </div>

                    {{-- Change display --}}
                    <div x-show="paymentModal.cashTendered >= total && total > 0"
                        class="flex justify-between items-center p-4 rounded-xl border"
                        :class="paymentModal.change > 0 ? 'bg-emerald-50 border-emerald-200' : 'bg-slate-50 border-slate-200'"
                        x-cloak>
                        <span class="text-sm font-semibold" :class="paymentModal.change > 0 ? 'text-emerald-700' : 'text-slate-600'">
                            Change to Return
                        </span>
                        <span class="text-2xl font-black" :class="paymentModal.change > 0 ? 'text-emerald-700' : 'text-slate-500'"
                            x-text="'$' + paymentModal.change.toFixed(2)">
                        </span>
                    </div>
                    <div x-show="paymentModal.cashTendered > 0 && paymentModal.cashTendered < total"
                        class="flex justify-between items-center p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 font-medium" x-cloak>
                        <span>Insufficient amount</span>
                        <span x-text="'Need $' + (total - paymentModal.cashTendered).toFixed(2) + ' more'"></span>
                    </div>
                </div>

                {{-- CARD PAYMENT --}}
                <div x-show="paymentModal.method === 'card'" class="space-y-3">
                    <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                        <p class="text-xs text-blue-800 font-medium">Tap or insert card on the terminal, then enter the authorization code.</p>
                    </div>
                    <div>
                        <label class="form-label text-xs font-semibold text-slate-600 mb-1.5 block">Authorization Code</label>
                        <input type="text" x-model="paymentModal.cardRef"
                            @keydown.enter="canPay && processPayment()"
                            placeholder="Enter auth code…"
                            class="form-input font-mono font-bold text-lg tracking-widest text-slate-900"
                            id="card-ref-input">
                    </div>
                </div>

                {{-- Confirm button --}}
                <button @click="processPayment()" :disabled="!canPay"
                    class="w-full btn btn-primary text-base py-3 justify-center gap-2 font-bold rounded-xl disabled:opacity-40 disabled:cursor-not-allowed"
                    id="process-payment-btn">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Confirm Payment
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ================================================================ --}}
{{-- QUICK ADD PRODUCT MODAL                                           --}}
{{-- ================================================================ --}}
<div x-show="quickAddModal.open" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
    <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]" @click="quickAddModal.open = false"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        id="quick-add-modal">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Quick Add Product</h3>
                <p class="text-xs text-slate-400 mt-0.5">Add a product not in the system</p>
            </div>
            <button @click="quickAddModal.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form @submit.prevent="saveQuickProduct()" class="p-5 space-y-4">
            <div>
                <label class="form-label">Product Name <span class="text-red-500">*</span></label>
                <input type="text" x-model="quickAddModal.form.name" class="form-input"
                    placeholder="Wireless Mouse" required x-ref="quickAddName">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="form-label">Price ($) <span class="text-red-500">*</span></label>
                    <input type="number" x-model.number="quickAddModal.form.price" class="form-input"
                        placeholder="0.00" step="0.01" min="0.01" required>
                </div>
                <div>
                    <label class="form-label">Stock</label>
                    <input type="number" x-model.number="quickAddModal.form.stock" class="form-input"
                        placeholder="1" min="1">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="form-label">Category</label>
                    <select x-model="quickAddModal.form.category" class="form-input form-select">
                        <template x-for="cat in categories" :key="cat">
                            <option :value="cat" x-text="cat"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="form-label">Emoji</label>
                    <input type="text" x-model="quickAddModal.form.emoji" class="form-input text-xl text-center"
                        placeholder="📦" maxlength="2">
                </div>
            </div>
            <div class="flex gap-3 pt-1">
                <button type="button" @click="quickAddModal.open = false" class="flex-1 btn btn-secondary">Cancel</button>
                <button type="submit" class="flex-1 btn btn-primary">Add & Sell</button>
            </div>
        </form>
    </div>
</div>

{{-- ================================================================ --}}
{{-- ADD CUSTOMER MODAL                                                --}}
{{-- ================================================================ --}}
<div x-show="addCustomerModal.open" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
    <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]" @click="addCustomerModal.open = false"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        id="add-customer-modal">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-800">Add New Customer</h3>
            <button @click="addCustomerModal.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form @submit.prevent="saveCustomer()" class="p-5 space-y-4">
            <div>
                <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                <input type="text" x-model="addCustomerModal.form.name" class="form-input" required placeholder="Jane Smith">
            </div>
            <div>
                <label class="form-label">Phone</label>
                <input type="tel" x-model="addCustomerModal.form.phone" class="form-input" placeholder="+1 (555) 000-0000">
            </div>
            <div class="flex gap-3 pt-1">
                <button type="button" @click="addCustomerModal.open = false" class="flex-1 btn btn-secondary">Cancel</button>
                <button type="submit" class="flex-1 btn btn-primary">Save & Select</button>
            </div>
        </form>
    </div>
</div>

{{-- ================================================================ --}}
{{-- CLEAR CART CONFIRM                                                --}}
{{-- ================================================================ --}}
<div x-show="clearConfirm" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
    <div class="absolute inset-0 bg-black/30 backdrop-blur-[1px]" @click="clearConfirm = false"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xs p-6 text-center"
        x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
        </div>
        <h3 class="text-sm font-bold text-slate-800">Clear Cart?</h3>
        <p class="text-xs text-slate-500 mt-1.5" x-text="'Remove all ' + cart.reduce((s,i)=>s+i.qty,0) + ' item(s) from the cart.'"></p>
        <div class="flex gap-3 mt-5">
            <button @click="clearConfirm = false" class="flex-1 btn btn-secondary">Keep Cart</button>
            <button @click="clearCart()" class="flex-1 btn btn-danger">Yes, Clear</button>
        </div>
    </div>
</div>

{{-- ================================================================ --}}
{{-- ALPINE.JS COMPONENT                                              --}}
{{-- ================================================================ --}}
<script>
/* ─── Toast manager (separate component) ───────────────────────────── */
function posToasts() {
    return {
        toasts: [],
        add({ message, type = 'info', duration = 3000 }) {
            const id = Date.now() + Math.random();
            this.toasts.push({ id, message, type, visible: true });
            setTimeout(() => {
                const t = this.toasts.find(t => t.id === id);
                if (t) t.visible = false;
                setTimeout(() => { this.toasts = this.toasts.filter(t => t.id !== id); }, 200);
            }, duration);
        },
    };
}

/* ─── Dispatch toast helper ─────────────────────────────────────────── */
function toast(message, type = 'info', duration = 3000) {
    window.dispatchEvent(new CustomEvent('pos-toast', { detail: { message, type, duration } }));
}

/* ─── Main POS component ────────────────────────────────────────────── */
function posScreen() {
    return {
        /* ── State ── */
        search:          '',
        currentCategory: null,
        cart:            [],
        discountPercent: 0,
        taxEnabled:      true,
        selectedCustomer:null,
        barcodeError:    null,
        clearConfirm:    false,

        /* ── Modals ── */
        paymentModal: {
            open: false, method: 'cash', cashTendered: 0,
            change: 0, cardRef: '', success: false, orderNo: null,
        },
        quickAddModal: {
            open: false,
            form: { name: '', price: 0, stock: 10, category: 'Accessories', emoji: '📦' },
        },
        addCustomerModal: {
            open: false,
            form: { name: '', phone: '' },
        },

        /* ── Data ── */
        quickAmounts: [5, 10, 20, 50, 100],
        categories: ['Electronics', 'Accessories', 'Cables', 'Peripherals'],
        allProducts: [
            { id: 1,  name: 'Wireless Headphones',  price: 89.99,  stock: 12, category: 'Electronics',  barcode: 'WH001', emoji: '🎧' },
            { id: 2,  name: 'Mechanical Keyboard',   price: 129.00, stock: 8,  category: 'Peripherals',  barcode: 'MK002', emoji: '⌨️' },
            { id: 3,  name: 'USB-C Hub 7-in-1',      price: 49.99,  stock: 25, category: 'Accessories',  barcode: 'UH003', emoji: '🔌' },
            { id: 4,  name: 'Laptop Stand',           price: 39.99,  stock: 5,  category: 'Accessories',  barcode: 'LS004', emoji: '💻' },
            { id: 5,  name: 'Screen Protector',       price: 14.99,  stock: 0,  category: 'Accessories',  barcode: 'SP005', emoji: '🛡️' },
            { id: 6,  name: 'Mouse Pad XL',           price: 24.99,  stock: 18, category: 'Accessories',  barcode: 'MP006', emoji: '🖱️' },
            { id: 7,  name: 'Webcam HD',              price: 79.99,  stock: 3,  category: 'Electronics',  barcode: 'WC007', emoji: '📷' },
            { id: 8,  name: 'HDMI Cable 2m',          price: 12.99,  stock: 40, category: 'Cables',       barcode: 'HC008', emoji: '📺' },
            { id: 9,  name: 'USB-A to C Cable',       price: 9.99,   stock: 60, category: 'Cables',       barcode: 'UC009', emoji: '🔗' },
            { id: 10, name: 'Wireless Mouse',          price: 45.00,  stock: 15, category: 'Peripherals', barcode: 'WM010', emoji: '🖱️' },
            { id: 11, name: 'Power Bank 20000mAh',    price: 54.99,  stock: 9,  category: 'Electronics',  barcode: 'PB011', emoji: '🔋' },
            { id: 12, name: 'Phone Stand',             price: 19.99,  stock: 22, category: 'Accessories',  barcode: 'PS012', emoji: '📱' },
        ],
        filteredProducts: [],
        customers: [
            { id: 1, name: 'Sarah Evans',  phone: '+1 555 0001' },
            { id: 2, name: 'Mike Johnson', phone: '+1 555 0002' },
            { id: 3, name: 'Anna Lee',     phone: '+1 555 0003' },
            { id: 4, name: 'David Kim',    phone: '+1 555 0004' },
        ],

        /* ════════════════════════════════════════════════════════
           INIT
           ════════════════════════════════════════════════════════ */
        init() {
            this.filteredProducts = [...this.allProducts];
            // Focus search on load
            this.$nextTick(() => { this.$refs.searchInput?.focus(); });
        },

        /* ════════════════════════════════════════════════════════
           KEYBOARD SHORTCUTS
           ════════════════════════════════════════════════════════ */
        handleGlobalKey(e) {
            // F2 → focus search
            if (e.key === 'F2') {
                e.preventDefault();
                this.$refs.searchInput?.focus();
                this.$refs.searchInput?.select();
                return;
            }
            // F9 → checkout
            if (e.key === 'F9') {
                e.preventDefault();
                if (this.cart.length > 0 && !this.paymentModal.open) {
                    this.openPaymentModal('card');
                }
                return;
            }
            // Escape → clear cart prompt OR close modals
            if (e.key === 'Escape') {
                if (this.paymentModal.open && !this.paymentModal.success) { this.paymentModal.open = false; return; }
                if (this.quickAddModal.open)    { this.quickAddModal.open = false; return; }
                if (this.addCustomerModal.open) { this.addCustomerModal.open = false; return; }
                if (this.clearConfirm)          { this.clearConfirm = false; return; }
                if (this.cart.length > 0)       { this.promptClearCart(); }
                return;
            }
            // + → increase last cart item qty
            if (e.key === '+' && !this.paymentModal.open) {
                const last = this.cart[this.cart.length - 1];
                if (last) { this.increaseQty(last); e.preventDefault(); }
                return;
            }
            // - → decrease last cart item qty
            if (e.key === '-' && !this.paymentModal.open && document.activeElement.tagName !== 'INPUT') {
                const last = this.cart[this.cart.length - 1];
                if (last) { this.decreaseQty(last); e.preventDefault(); }
                return;
            }
        },

        /* ════════════════════════════════════════════════════════
           SEARCH & BARCODE
           ════════════════════════════════════════════════════════ */
        filterProducts() {
            this.barcodeError = null;
            let p = [...this.allProducts];
            if (this.currentCategory) p = p.filter(x => x.category === this.currentCategory);
            if (this.search.trim()) {
                const q = this.search.toLowerCase().trim();
                p = p.filter(x => x.name.toLowerCase().includes(q) || x.barcode?.toLowerCase() === q);
            }
            this.filteredProducts = p;
        },

        /* Enter on search: barcode scan flow */
        handleSearchEnter() {
            const q = this.search.trim().toLowerCase();
            if (!q) return;

            // Exact barcode match → auto-add
            const byBarcode = this.allProducts.find(p => p.barcode?.toLowerCase() === q);
            if (byBarcode) {
                this.addToCart(byBarcode);
                this.search = '';
                this.filterProducts();
                this.$refs.searchInput?.focus();
                return;
            }

            // Single search result → add it
            const matches = this.filteredProducts.filter(p => p.stock > 0);
            if (matches.length === 1) {
                this.addToCart(matches[0]);
                this.search = '';
                this.filterProducts();
                this.$refs.searchInput?.focus();
                return;
            }

            // No match → barcode error
            if (this.filteredProducts.length === 0) {
                this.barcodeError = this.search;
                this.search = '';
                this.filterProducts();
            }
        },

        /* ════════════════════════════════════════════════════════
           CART OPERATIONS
           ════════════════════════════════════════════════════════ */
        addToCart(product) {
            if (product.stock === 0) {
                toast('Out of stock: ' + product.name, 'error');
                return;
            }
            const existing = this.cart.find(i => i.id === product.id);
            if (existing) {
                const maxStock = this.allProducts.find(p => p.id === product.id)?.stock || 0;
                if (existing.qty >= maxStock) {
                    toast('Max stock reached for ' + product.name, 'warning');
                    return;
                }
                existing.qty++;
            } else {
                this.cart.push({ ...product, qty: 1 });
            }
            toast(product.name + ' added to cart', 'success', 1500);
        },

        removeFromCart(item) {
            this.cart = this.cart.filter(i => i.id !== item.id);
            toast(item.name + ' removed', 'info', 1800);
        },

        increaseQty(item) {
            const maxStock = this.allProducts.find(p => p.id === item.id)?.stock || 0;
            if (item.qty >= maxStock) {
                toast('Max stock reached', 'warning', 1500);
                return;
            }
            item.qty++;
        },

        decreaseQty(item) {
            if (item.qty <= 1) {
                this.removeFromCart(item);
            } else {
                item.qty--;
            }
        },

        clampQty(item) {
            const maxStock = this.allProducts.find(p => p.id === item.id)?.stock || 1;
            item.qty = Math.max(1, Math.min(item.qty || 1, maxStock));
        },

        promptClearCart() {
            if (this.cart.length === 0) return;
            this.clearConfirm = true;
        },

        clearCart() {
            this.cart = [];
            this.discountPercent = 0;
            this.selectedCustomer = null;
            this.clearConfirm = false;
            toast('Cart cleared', 'info');
            this.$nextTick(() => this.$refs.searchInput?.focus());
        },

        /* ════════════════════════════════════════════════════════
           COMPUTED TOTALS
           ════════════════════════════════════════════════════════ */
        get subtotal() {
            return this.cart.reduce((sum, i) => sum + i.price * i.qty, 0);
        },
        get discountAmount() {
            return this.subtotal * (this.discountPercent / 100);
        },
        get taxAmount() {
            return this.taxEnabled ? (this.subtotal - this.discountAmount) * 0.085 : 0;
        },
        get total() {
            return this.subtotal - this.discountAmount + this.taxAmount;
        },

        /* ════════════════════════════════════════════════════════
           PAYMENT
           ════════════════════════════════════════════════════════ */
        openPaymentModal(method) {
            if (this.cart.length === 0) {
                toast('Cart is empty', 'error');
                return;
            }
            this.paymentModal.method       = method;
            this.paymentModal.cashTendered = 0;
            this.paymentModal.change       = 0;
            this.paymentModal.cardRef      = '';
            this.paymentModal.success      = false;
            this.paymentModal.open         = true;
            // Auto-focus cash input
            if (method === 'cash') {
                this.$nextTick(() => this.$refs.cashInput?.focus());
            }
        },

        calculateChange() {
            this.paymentModal.change = Math.max(0, this.paymentModal.cashTendered - this.total);
        },

        get canPay() {
            if (this.paymentModal.method === 'cash') return this.paymentModal.cashTendered >= this.total && this.total > 0;
            return this.paymentModal.cardRef.trim().length > 0;
        },

        processPayment() {
            if (!this.canPay) return;
            this.paymentModal.orderNo = '#' + (1000 + Math.floor(Math.random() * 9000));
            this.paymentModal.success = true;
            toast('Payment complete — Order ' + this.paymentModal.orderNo, 'success', 4000);
        },

        printReceipt() {
            toast('Sending to printer…', 'info', 2000);
        },

        resetPOS() {
            this.cart              = [];
            this.discountPercent   = 0;
            this.selectedCustomer  = null;
            this.paymentModal.open = false;
            this.paymentModal.success = false;
            this.search            = '';
            this.filterProducts();
            this.$nextTick(() => this.$refs.searchInput?.focus());
        },

        /* ════════════════════════════════════════════════════════
           QUICK ADD PRODUCT
           ════════════════════════════════════════════════════════ */
        openQuickAdd() {
            this.quickAddModal.form = {
                name: this.barcodeError || '', price: 0,
                stock: 10, category: 'Accessories', emoji: '📦',
            };
            this.quickAddModal.open = true;
            this.$nextTick(() => this.$refs.quickAddName?.focus());
        },

        saveQuickProduct() {
            const f = this.quickAddModal.form;
            if (!f.name || !f.price) return;
            const newProduct = {
                id:       Date.now(),
                name:     f.name,
                price:    parseFloat(f.price),
                stock:    parseInt(f.stock) || 1,
                category: f.category,
                emoji:    f.emoji || '📦',
                barcode:  null,
            };
            this.allProducts.push(newProduct);
            this.quickAddModal.open = false;
            this.barcodeError = null;
            this.filterProducts();
            this.addToCart(newProduct);
        },

        /* ════════════════════════════════════════════════════════
           CUSTOMER
           ════════════════════════════════════════════════════════ */
        openAddCustomer() {
            this.addCustomerModal.form = { name: '', phone: '' };
            this.addCustomerModal.open = true;
        },

        saveCustomer() {
            const f = this.addCustomerModal.form;
            if (!f.name) return;
            const c = { id: Date.now(), name: f.name, phone: f.phone };
            this.customers.push(c);
            this.selectedCustomer    = c;
            this.addCustomerModal.open = false;
            toast(c.name + ' added and selected', 'success');
        },
    };
}
</script>
</body>
</html>
