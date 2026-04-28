<?php

use App\Livewire\Pages\Buymobile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ──────────────────────────────────────────────
Route::livewire('/', 'pages::home');
Route::get('/buy-mobile', Buymobile::class)->name('buy-mobile');
Route::get('/products', \App\Livewire\Pages\ProductList::class)->name('products.index');
Route::get('/products/{product:slug}', \App\Livewire\Pages\ProductDetail::class)->name('products.show');
Route::get('/cart', \App\Livewire\Pages\CartPage::class)->name('cart');

// ─── Google OAuth Routes ────────────────────────────────────────
Route::get('/auth/google', [\App\Http\Controllers\GoogleCallbackController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\GoogleCallbackController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// ─── Customer Routes (requires auth + customer role) ────────────
Route::middleware(['auth', 'customer'])->group(function () {
    Route::get('/checkout', \App\Livewire\Pages\Checkout::class)->name('checkout');
    Route::get('/orders', \App\Livewire\Pages\OrderHistory::class)->name('orders.index');
    Route::get('/orders/{order}', \App\Livewire\Pages\OrderDetail::class)->name('orders.show');
    Route::get('/orders/{order}/confirmation', \App\Livewire\Pages\OrderConfirmation::class)->name('orders.confirmation');

    // Trade In Client
    Route::get('/trade-in', \App\Livewire\Pages\TradeInHistory::class)->name('trade-ins.index');
    Route::get('/trade-in/{product}/submit', \App\Livewire\Pages\SubmitTradeIn::class)->name('trade-in.submit');
    Route::get('/trade-in/{tradeIn}/detail', \App\Livewire\Pages\TradeInDetail::class)->name('trade-ins.show');
});

// ─── Admin Routes (requires auth + admin role) ──────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::livewire('/dashboard', 'pages::admin.dashboard')->name('dashboard');
    Route::livewire('/users', 'pages::admin.user-management')->name('users');
    Route::livewire('/roles', 'pages::admin.role-permission')->name('roles');

    Route::get('/products', \App\Livewire\Admin\Products\ProductManagement::class)->name('products');
    Route::get('/orders', \App\Livewire\Admin\Orders\OrderManagement::class)->name('orders.management');
    Route::get('/categories', \App\Livewire\Admin\Products\CategoryManagement::class)->name('categories');
    Route::get('/brands', \App\Livewire\Admin\Products\BrandManagement::class)->name('brands');
    Route::get('/products/{product}/variants', \App\Livewire\Admin\Products\VariantManagement::class)->name('products.variants');

    Route::get('/settings/payment', \App\Livewire\Admin\Settings\PaymentSettings::class)->name('settings.payment');
    Route::get('/settings/shipping', \App\Livewire\Admin\Settings\ShippingSettings::class)->name('settings.shipping');
    Route::get('/settings/catalog', \App\Livewire\Admin\Settings\CatalogSettings::class)->name('settings.catalog');

    Route::get('/trade-ins', App\Livewire\Admin\TradeIn\Index::class)->name('trade-ins.index');
    Route::get('/trade-ins/{tradeIn}', App\Livewire\Admin\TradeIn\Show::class)->name('trade-ins.show');
});

// ─── CS Chat Route (requires auth + admin middleware + cs role) ──
Route::livewire('/admin/cs-chat', 'pages::cs-dashboard')
    ->middleware(['auth', 'admin'])
    ->name('admin.cs-chat');

// ─── Logout ─────────────────────────────────────────────────────
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->middleware('auth')->name('logout');

// ─── Erzap Webhook Routes (Dynamic Source Support) ────────────────
Route::post('/web_service/import_produk_json/new.json', [\App\Http\Controllers\Api\ErzapProductController::class, 'store']);
Route::post('/web_service/import_produk_json/new', [\App\Http\Controllers\Api\ErzapProductController::class, 'store']);
Route::post('/web_service/sinkronisasi_stok/new', [\App\Http\Controllers\Api\ErzapProductController::class, 'syncStock']);
