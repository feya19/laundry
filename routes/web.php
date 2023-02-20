<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\JenisProdukController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserController;

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

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
]);

Route::middleware(['auth'])->group(function(){
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('settings', [HomeController::class, 'settings'])->name('settings');
    Route::post('change-profile', [HomeController::class, 'changeProfile'])->name('changeProfile');
    Route::post('change-password', [HomeController::class, 'changePassword'])->name('changePassword');
    Route::prefix('master')->name('master.')->group(function(){
        Route::group(['middleware' => 'admin'], function(){
            Route::resource('outlet', OutletController::class);
            Route::resource('jenis_produk', JenisProdukController::class);
            Route::resource('produk', ProdukController::class);
            Route::resource('users', UserController::class);
        });
    });
});
