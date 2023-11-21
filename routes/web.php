<?php

use App\Http\Controllers\customerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::redirect('/','/customer');
Route::get('/welcome', function () {
    return view('welcome');
});

Route::resource('/customer', customerController::class);
