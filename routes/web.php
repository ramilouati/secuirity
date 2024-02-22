<?php

use App\Http\Controllers\ClaimController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/claims', [ClaimController::class, 'index'])->name('claims');
Route::get('/claims/create', [ClaimController::class, 'create'])->name('claims.create');
Route::post('/claims/response/{id}', [ClaimController::class, 'response'])->name('claims.response');

Route::get('/claims/{id}', [ClaimController::class, 'show'])->name('claims.show');
Route::post('/claims', [ClaimController::class, 'store'])->name('claims.store');
Route::put('/claims/{id}', [ClaimController::class, 'update'])->name('claims.update');
Route::delete('/claims/{id}', [ClaimController::class, 'destroy'])->name('claims.destroy');
