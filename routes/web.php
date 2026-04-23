<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    return view('pos.dashboard');
})->name('dashboard');

Route::get('/pos', function () {
    return view('pos.pos-screen');
})->name('pos');

Route::get('/products', function () {
    return view('pos.products');
})->name('products');

Route::get('/orders', function () {
    return view('pos.orders');
})->name('orders');

Route::get('/customers', function () {
    return view('pos.customers');
})->name('customers');

Route::get('/staff', function () {
    return view('pos.staff');
})->name('staff');

Route::get('/login', function () {
    return view('pos.login');
})->name('login');

Route::get('/settings', function () {
    return view('pos.settings');
})->name('settings');

// ── Catalog (Global) ──────────────────────────────────────────
Route::get('/categories', function () {
    return view('pos.categories');
})->name('categories');

Route::get('/attributes', function () {
    return view('pos.attributes');
})->name('attributes');

// ── Inventory (Branch-specific) ───────────────────────────────
Route::get('/inventory/stock', function () {
    return view('pos.inventory-stock');
})->name('inventory.stock');

Route::get('/inventory/adjustments', function () {
    return view('pos.inventory-adjustments');
})->name('inventory.adjustments');

Route::get('/inventory/transfers', function () {
    return view('pos.inventory-transfers');
})->name('inventory.transfers');
