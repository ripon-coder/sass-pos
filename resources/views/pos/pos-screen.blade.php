<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS Screen — {{ config('app.name', 'POS SaaS') }}</title>
    <meta name="description" content="Point of Sale Screen">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full overflow-hidden" x-data="posScreen()" x-init="init()">

<div class="flex h-full">

    {{-- ===== LEFT: PRODUCT PANEL ===== --}}
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

        {{-- POS Header --}}
        <header class="flex items-center gap-3 px-4 py-3 bg-white border-b border-slate-200">
            <a href="/dashboard" class="btn btn-ghost btn-icon text-slate-500" title="Back to Dashboard">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div class="w-px h-5 bg-slate-200"></div>
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-blue-600 rounded flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.943-7.143a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                </div>
                <span class="font-semibold text-slate-800 text-sm">POS Screen</span>
            </div>

            {{-- Search / Barcode --}}
            <div class="flex-1 max-w-lg mx-4">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input
                        type="text"
                        x-model="search"
                        @input.debounce.200ms="filterProducts()"
                        placeholder="Search products or scan barcode…"
                        class="form-input pl-9 pr-4 py-2.5"
                        id="pos-search-input"
                        autofocus
                    >
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-1">
                        <kbd class="px-1.5 py-0.5 text-[10px] font-semibold text-slate-400 bg-slate-100 border border-slate-200 rounded">
                            <svg class="w-3 h-3 inline-block" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M3 7l3 3-3 3" stroke-linecap="round" stroke-linejoin="round"/><rect x="10" y="4" width="11" height="16" rx="2"/></svg>
                            Scan
                        </kbd>
                    </div>
                </div>
            </div>

            {{-- Category Pills --}}
            <div class="flex items-center gap-1.5 overflow-x-auto">
                <button
                    @click="currentCategory = null; filterProducts()"
                    class="px-3 py-1.5 rounded-full text-xs font-medium whitespace-nowrap transition-colors"
                    :class="currentCategory === null ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                >All</button>
                <template x-for="cat in categories" :key="cat">
                    <button
                        @click="currentCategory = cat; filterProducts()"
                        class="px-3 py-1.5 rounded-full text-xs font-medium whitespace-nowrap transition-colors"
                        :class="currentCategory === cat ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                        x-text="cat"
                    ></button>
                </template>
            </div>
        </header>

        {{-- Product Grid --}}
        <div class="flex-1 overflow-y-auto p-4">
            <div x-show="filteredProducts.length === 0" class="flex flex-col items-center justify-center h-64 text-center" x-cloak>
                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <p class="text-sm font-medium text-slate-500">No products found</p>
                <p class="text-xs text-slate-400 mt-1">Try a different search term or category</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3" id="product-grid">
                <template x-for="product in filteredProducts" :key="product.id">
                    <button
                        @click="addToCart(product)"
                        class="group flex flex-col bg-white border border-slate-200 rounded-lg text-left hover:border-blue-400 hover:shadow-sm transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 overflow-hidden"
                        :class="product.stock === 0 ? 'opacity-50 cursor-not-allowed' : ''"
                        :disabled="product.stock === 0"
                    >
                        <div class="aspect-square bg-slate-50 flex items-center justify-center text-3xl relative overflow-hidden">
                            <span x-text="product.emoji"></span>
                            <div x-show="product.stock > 0 && product.stock <= 5" class="absolute top-1.5 right-1.5 w-1.5 h-1.5 rounded-full bg-amber-400"></div>
                            <div x-show="product.stock === 0" class="absolute inset-0 bg-white/60 flex items-center justify-center">
                                <span class="text-[10px] font-semibold text-slate-500 bg-white px-2 py-0.5 rounded-full border border-slate-200">Out of stock</span>
                            </div>
                        </div>
                        <div class="p-2 flex-1">
                            <p class="text-xs font-medium text-slate-700 line-clamp-2 leading-snug" x-text="product.name"></p>
                            <p class="text-xs font-semibold text-blue-600 mt-1" x-text="'$' + product.price.toFixed(2)"></p>
                        </div>
                    </button>
                </template>
            </div>
        </div>
    </div>

    {{-- ===== RIGHT: CART PANEL ===== --}}
    <aside class="w-80 xl:w-96 flex flex-col bg-white border-l border-slate-200 flex-shrink-0">

        {{-- Cart Header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200">
            <div class="flex items-center gap-2">
                <h2 class="text-sm font-semibold text-slate-800">Cart</h2>
                <span class="badge badge-info" x-text="cart.length + (cart.length === 1 ? ' item' : ' items')"></span>
            </div>
            <div class="flex items-center gap-1">
                {{-- Customer selector --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="btn btn-ghost btn-sm text-slate-500 gap-1.5" id="pos-customer-btn">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                        <span x-text="selectedCustomer ? selectedCustomer.name : 'Guest'"></span>
                    </button>
                    <div x-show="open" @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class="absolute right-0 mt-1 w-52 bg-white border border-slate-200 rounded-lg shadow-lg z-50 py-1" x-cloak>
                        <div class="px-3 py-1.5">
                            <input type="text" placeholder="Search customer…" class="form-input py-1.5 text-xs">
                        </div>
                        <div class="divide-y divide-slate-50 max-h-40 overflow-y-auto">
                            <button @click="selectedCustomer = null; open = false" class="w-full px-3 py-2 text-left text-xs text-slate-600 hover:bg-slate-50">Guest</button>
                            <template x-for="c in customers" :key="c.id">
                                <button @click="selectedCustomer = c; open = false" class="w-full px-3 py-2 text-left text-xs text-slate-600 hover:bg-slate-50" x-text="c.name"></button>
                            </template>
                        </div>
                    </div>
                </div>

                <button @click="clearCart()" class="btn btn-ghost btn-icon btn-sm text-slate-400 hover:text-red-500" title="Clear cart">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                </button>
            </div>
        </div>

        {{-- Cart Items --}}
        <div class="flex-1 overflow-y-auto">
            {{-- Empty State --}}
            <div x-show="cart.length === 0" class="flex flex-col items-center justify-center h-48 text-center px-6" x-cloak>
                <svg class="w-10 h-10 text-slate-200 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.943-7.143a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                <p class="text-sm text-slate-400 font-medium">Cart is empty</p>
                <p class="text-xs text-slate-300 mt-0.5">Click a product to add it</p>
            </div>

            <div class="divide-y divide-slate-50 px-4">
                <template x-for="item in cart" :key="item.id">
                    <div class="flex items-start gap-3 py-3">
                        <div class="w-8 h-8 rounded bg-slate-50 border border-slate-100 flex items-center justify-center text-lg flex-shrink-0" x-text="item.emoji"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-slate-700 truncate" x-text="item.name"></p>
                            <p class="text-xs text-slate-400 mt-0.5" x-text="'$' + item.price.toFixed(2) + ' each'"></p>
                            {{-- Quantity Control --}}
                            <div class="flex items-center gap-2 mt-1.5">
                                <button @click="decreaseQty(item)" class="w-6 h-6 flex items-center justify-center rounded border border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-500 transition-colors">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path d="M5 12h14" stroke-linecap="round"/></svg>
                                </button>
                                <span class="text-sm font-semibold text-slate-700 w-6 text-center" x-text="item.qty"></span>
                                <button @click="increaseQty(item)" class="w-6 h-6 flex items-center justify-center rounded border border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-500 transition-colors">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-slate-800" x-text="'$' + (item.price * item.qty).toFixed(2)"></p>
                            <button @click="removeFromCart(item)" class="text-slate-300 hover:text-red-400 mt-1 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Cart Footer --}}
        <div class="border-t border-slate-200 px-4 py-4 space-y-3">

            {{-- Discount --}}
            <div class="flex items-center gap-2">
                <div x-data="{ showDiscount: false }">
                    <button @click="showDiscount = !showDiscount" class="btn btn-ghost btn-sm text-blue-600 hover:text-blue-700 px-0 gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185zM9.75 9h.008v.008H9.75V9zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 4.5h.008v.008h-.008V13.5zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                        Add Discount
                    </button>
                    <div x-show="showDiscount" class="flex items-center gap-2 mt-2" x-cloak>
                        <input type="number" x-model.number="discountPercent" min="0" max="100" placeholder="0" class="form-input w-20 text-sm text-center" id="pos-discount-input">
                        <span class="text-sm text-slate-500">% off</span>
                    </div>
                </div>
            </div>

            {{-- Totals --}}
            <div class="space-y-1.5 text-sm">
                <div class="flex justify-between text-slate-500">
                    <span>Subtotal</span>
                    <span x-text="'$' + subtotal.toFixed(2)"></span>
                </div>
                <div class="flex justify-between text-slate-500" x-show="discountAmount > 0">
                    <span x-text="'Discount (' + discountPercent + '%)'"></span>
                    <span class="text-red-500" x-text="'- $' + discountAmount.toFixed(2)"></span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Tax (8.5%)</span>
                    <span x-text="'$' + taxAmount.toFixed(2)"></span>
                </div>
                <div class="flex justify-between font-semibold text-slate-900 text-base pt-1.5 border-t border-slate-200">
                    <span>Total</span>
                    <span x-text="'$' + total.toFixed(2)"></span>
                </div>
            </div>

            {{-- Pay Buttons --}}
            <div class="grid grid-cols-2 gap-2">
                <button
                    @click="openPaymentModal('cash')"
                    :disabled="cart.length === 0"
                    class="btn btn-secondary justify-center gap-1.5"
                    id="pos-pay-cash-btn"
                    :class="cart.length === 0 && 'opacity-40 cursor-not-allowed'"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" /></svg>
                    Cash
                </button>
                <button
                    @click="openPaymentModal('card')"
                    :disabled="cart.length === 0"
                    class="btn btn-primary justify-center gap-1.5"
                    id="pos-pay-card-btn"
                    :class="cart.length === 0 && 'opacity-40 cursor-not-allowed'"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                    Card
                </button>
            </div>
        </div>
    </aside>
</div>

{{-- ===== PAYMENT MODAL ===== --}}
<div
    x-show="paymentModal.open"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    x-cloak
>
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/30" @click="paymentModal.open = false"></div>

    {{-- Modal --}}
    <div
        class="relative bg-white rounded-xl shadow-xl w-full max-w-md"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        id="payment-modal"
    >
        {{-- Success State --}}
        <div x-show="paymentModal.success" class="flex flex-col items-center justify-center p-10 text-center" x-cloak>
            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
            </div>
            <h2 class="text-xl font-semibold text-slate-800">Payment Successful!</h2>
            <p class="text-sm text-slate-500 mt-1" x-text="'Order #' + paymentModal.orderNo + ' has been placed.'"></p>
            <div class="mt-2 text-2xl font-bold text-slate-900" x-text="'$' + total.toFixed(2)"></div>
            <div x-show="paymentModal.method === 'cash' && paymentModal.change > 0" class="mt-2 text-sm text-slate-600">
                Change: <span class="font-semibold text-green-600" x-text="'$' + paymentModal.change.toFixed(2)"></span>
            </div>
            <div class="flex gap-3 mt-6">
                <button @click="printReceipt()" class="btn btn-secondary gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a.75.75 0 01-.875.854 49.188 49.188 0 01-14.01 0 .75.75 0 01-.875-.854L2.34 18m15.32 0H6.34" /></svg>
                    Print Receipt
                </button>
                <button @click="resetPOS()" class="btn btn-primary">New Sale</button>
            </div>
        </div>

        {{-- Payment Form State --}}
        <div x-show="!paymentModal.success">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <h2 class="text-base font-semibold text-slate-800" x-text="paymentModal.method === 'cash' ? 'Cash Payment' : 'Card Payment'"></h2>
                <button @click="paymentModal.open = false" class="btn btn-ghost btn-icon btn-sm text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div class="bg-slate-50 rounded-lg p-4 flex justify-between items-center">
                    <span class="text-sm text-slate-500">Amount Due</span>
                    <span class="text-2xl font-bold text-slate-900" x-text="'$' + total.toFixed(2)"></span>
                </div>

                {{-- Cash Payment --}}
                <div x-show="paymentModal.method === 'cash'" class="space-y-3">
                    <div>
                        <label class="form-label">Cash Tendered</label>
                        <input
                            type="number"
                            x-model.number="paymentModal.cashTendered"
                            @input="calculateChange()"
                            placeholder="0.00"
                            class="form-input text-lg font-semibold"
                            id="cash-tendered-input"
                            step="0.01"
                        >
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        <template x-for="amt in quickAmounts" :key="amt">
                            <button
                                @click="paymentModal.cashTendered = amt; calculateChange()"
                                class="btn btn-secondary btn-sm"
                                x-text="'$' + amt"
                            ></button>
                        </template>
                        <button @click="paymentModal.cashTendered = total; calculateChange()" class="btn btn-secondary btn-sm">Exact</button>
                    </div>
                    <div x-show="paymentModal.cashTendered >= total" class="flex justify-between items-center p-3 bg-green-50 rounded-lg border border-green-200" x-cloak>
                        <span class="text-sm text-green-700">Change</span>
                        <span class="font-bold text-green-700" x-text="'$' + paymentModal.change.toFixed(2)"></span>
                    </div>
                </div>

                {{-- Card Payment --}}
                <div x-show="paymentModal.method === 'card'" class="space-y-3">
                    <div>
                        <label class="form-label">Card Reference / Auth Code</label>
                        <input type="text" x-model="paymentModal.cardRef" placeholder="123456" class="form-input" id="card-ref-input">
                    </div>
                    <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                        <p class="text-xs text-blue-700">Tap the card terminal to process the payment, then enter the authorization code above.</p>
                    </div>
                </div>

                <button
                    @click="processPayment()"
                    :disabled="!canPay"
                    class="w-full btn btn-primary btn-lg"
                    id="process-payment-btn"
                    :class="!canPay && 'opacity-40 cursor-not-allowed'"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Confirm Payment
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function posScreen() {
    return {
        search: '',
        currentCategory: null,
        cart: [],
        discountPercent: 0,
        selectedCustomer: null,
        paymentModal: {
            open: false,
            method: 'cash',
            cashTendered: 0,
            change: 0,
            cardRef: '',
            success: false,
            orderNo: null,
        },
        quickAmounts: [5, 10, 20, 50, 100],
        categories: ['Electronics', 'Accessories', 'Cables', 'Peripherals'],
        allProducts: [
            { id: 1, name: 'Wireless Headphones', price: 89.99,  stock: 12, category: 'Electronics',  emoji: '🎧' },
            { id: 2, name: 'Mechanical Keyboard', price: 129.00, stock: 8,  category: 'Peripherals',  emoji: '⌨️' },
            { id: 3, name: 'USB-C Hub 7-in-1',  price: 49.99,  stock: 25, category: 'Accessories',   emoji: '🔌' },
            { id: 4, name: 'Laptop Stand',        price: 39.99,  stock: 5,  category: 'Accessories',   emoji: '💻' },
            { id: 5, name: 'Screen Protector',    price: 14.99,  stock: 0,  category: 'Accessories',   emoji: '🛡️' },
            { id: 6, name: 'Mouse Pad XL',        price: 24.99,  stock: 18, category: 'Accessories',   emoji: '🖱️' },
            { id: 7, name: 'Webcam HD',           price: 79.99,  stock: 3,  category: 'Electronics',   emoji: '📷' },
            { id: 8, name: 'HDMI Cable 2m',       price: 12.99,  stock: 40, category: 'Cables',        emoji: '📺' },
            { id: 9, name: 'USB-A to C Cable',    price: 9.99,   stock: 60, category: 'Cables',        emoji: '🔗' },
            { id: 10, name: 'Wireless Mouse',     price: 45.00,  stock: 15, category: 'Peripherals',   emoji: '🖱️' },
            { id: 11, name: 'Power Bank 20000mAh',price: 54.99,  stock: 9,  category: 'Electronics',   emoji: '🔋' },
            { id: 12, name: 'Phone Stand',        price: 19.99,  stock: 22, category: 'Accessories',   emoji: '📱' },
        ],
        filteredProducts: [],
        customers: [
            { id: 1, name: 'Sarah Evans' },
            { id: 2, name: 'Mike Johnson' },
            { id: 3, name: 'Anna Lee' },
            { id: 4, name: 'David Kim' },
        ],

        init() {
            this.filteredProducts = [...this.allProducts];
        },

        filterProducts() {
            let p = [...this.allProducts];
            if (this.currentCategory) p = p.filter(x => x.category === this.currentCategory);
            if (this.search.trim()) {
                const q = this.search.toLowerCase();
                p = p.filter(x => x.name.toLowerCase().includes(q));
            }
            this.filteredProducts = p;
        },

        addToCart(product) {
            if (product.stock === 0) return;
            const existing = this.cart.find(i => i.id === product.id);
            if (existing) {
                if (existing.qty < product.stock) existing.qty++;
            } else {
                this.cart.push({ ...product, qty: 1 });
            }
        },

        removeFromCart(item) {
            this.cart = this.cart.filter(i => i.id !== item.id);
        },

        increaseQty(item) {
            const product = this.allProducts.find(p => p.id === item.id);
            if (product && item.qty < product.stock) item.qty++;
        },

        decreaseQty(item) {
            if (item.qty <= 1) {
                this.removeFromCart(item);
            } else {
                item.qty--;
            }
        },

        clearCart() {
            if (this.cart.length === 0) return;
            if (confirm('Clear all items from cart?')) {
                this.cart = [];
                this.discountPercent = 0;
                this.selectedCustomer = null;
            }
        },

        get subtotal() {
            return this.cart.reduce((sum, i) => sum + i.price * i.qty, 0);
        },

        get discountAmount() {
            return this.subtotal * (this.discountPercent / 100);
        },

        get taxAmount() {
            return (this.subtotal - this.discountAmount) * 0.085;
        },

        get total() {
            return this.subtotal - this.discountAmount + this.taxAmount;
        },

        openPaymentModal(method) {
            if (this.cart.length === 0) return;
            this.paymentModal.method = method;
            this.paymentModal.cashTendered = 0;
            this.paymentModal.change = 0;
            this.paymentModal.cardRef = '';
            this.paymentModal.success = false;
            this.paymentModal.open = true;
        },

        calculateChange() {
            this.paymentModal.change = Math.max(0, this.paymentModal.cashTendered - this.total);
        },

        get canPay() {
            if (this.paymentModal.method === 'cash') return this.paymentModal.cashTendered >= this.total;
            return this.paymentModal.cardRef.trim().length > 0;
        },

        processPayment() {
            if (!this.canPay) return;
            this.paymentModal.orderNo = Math.floor(1000 + Math.random() * 9000);
            this.paymentModal.success = true;
        },

        printReceipt() {
            alert('Receipt printing... (connect to receipt printer)');
        },

        resetPOS() {
            this.cart = [];
            this.discountPercent = 0;
            this.selectedCustomer = null;
            this.paymentModal.open = false;
            this.paymentModal.success = false;
        },
    };
}
</script>
</body>
</html>
