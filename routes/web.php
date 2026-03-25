<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home');

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

// Logout route
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->middleware('auth')->name('logout');
