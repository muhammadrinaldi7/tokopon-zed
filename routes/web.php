<?php

use App\Livewire\Pages\Buymobile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home');
Route::get('/buy-mobile',Buymobile::class)->name('buy-mobile');

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

