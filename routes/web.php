<?php

use App\Http\Controllers\customerController;
use App\Http\Controllers\searchController;
use Illuminate\Support\Facades\Route;

use function Laravel\Prompts\search;

// Route::redirect('/','/customer');
Route::get('/welcome', function () {
    return view('welcome');
});

Route::resource('/customer', customerController::class);
Route::get('/search', [searchController::class, 'customer'])->name('customer.search');
