<?php

use App\Livewire\Pages\Buymobile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home');
Route::get('/buy-mobile', Buymobile::class)->name('buy-mobile');
Route::get('/products', \App\Livewire\Pages\ProductList::class)->name('products.index');
Route::get('/products/{product:slug}', \App\Livewire\Pages\ProductDetail::class)->name('products.show');
Route::get('/cart', \App\Livewire\Pages\CartPage::class)->name('cart');

// Order routes (requires authentication)
Route::get('/checkout', \App\Livewire\Pages\Checkout::class)->middleware('auth')->name('checkout');
Route::get('/orders', \App\Livewire\Pages\OrderHistory::class)->middleware('auth')->name('orders.index');
Route::get('/orders/{order}', \App\Livewire\Pages\OrderDetail::class)->middleware('auth')->name('orders.show');
Route::get('/orders/{order}/confirmation', \App\Livewire\Pages\OrderConfirmation::class)->middleware('auth')->name('orders.confirmation');

Route::livewire('/admin/cs-chat', 'pages::cs-dashboard')
    ->middleware(['auth', 'role:cs'])
    ->name('admin.cs-chat');

Route::livewire('/admin/dashboard', 'pages::admin.dashboard')
    ->middleware(['auth'])
    ->name('admin.dashboard');

Route::livewire('/admin/users', 'pages::admin.user-management')
    ->middleware(['auth', 'role:admin|superadmin'])
    ->name('admin.users');

Route::livewire('/admin/roles', 'pages::admin.role-permission')
    ->middleware(['auth', 'role:admin|superadmin'])
    ->name('admin.roles');

Route::get('/admin/products', \App\Livewire\Admin\Products\ProductManagement::class)
    ->middleware(['auth', 'role:admin|superadmin'])
    ->name('admin.products');

Route::get('/admin/orders', \App\Livewire\Admin\Orders\OrderManagement::class)
    ->middleware(['auth', 'role:admin|superadmin'])
    ->name('admin.orders.management');

Route::get('/admin/categories', \App\Livewire\Admin\Products\CategoryManagement::class)
    ->middleware(['auth', 'role:admin|superadmin'])
    ->name('admin.categories');

Route::get('/admin/brands', \App\Livewire\Admin\Products\BrandManagement::class)
    ->middleware(['auth', 'role:admin|superadmin'])
    ->name('admin.brands');

Route::get('/admin/products/{product}/variants', \App\Livewire\Admin\Products\VariantManagement::class)
    ->middleware(['auth', 'role:admin|superadmin'])
    ->name('admin.products.variants');

// Logout route
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->middleware('auth')->name('logout');

// Erzap Webhook Routes
Route::post('/web_service/import_produk_json/new.json', [\App\Http\Controllers\Api\ErzapProductController::class, 'store']);
Route::post('/web_service/import_produk_json/new', [\App\Http\Controllers\Api\ErzapProductController::class, 'store']);
Route::post('/web_service/sinkronisasi_stok/new', [\App\Http\Controllers\Api\ErzapProductController::class, 'syncStock']);
