<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home');

// Logout route
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->middleware('auth')->name('logout');
