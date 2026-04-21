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

Route::get('/settings', function () {
    return view('pos.settings');
})->name('settings');
