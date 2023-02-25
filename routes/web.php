<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\JenisProdukController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransaksiController;
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
    Route::get('select-outlet', [HomeController::class, 'selectOutlet'])->name('selectOutlet');
    Route::get('set-outlet/{id}/{previous}', [HomeController::class, 'setOutlet'])->name('setOutlet');
    Route::group(['middleware' => 'role'], function(){
        Route::prefix('master')->name('master.')->group(function(){
            Route::resource('outlet', OutletController::class);
            Route::resource('jenis_produk', JenisProdukController::class);
            Route::resource('produk', ProdukController::class);
            Route::resource('users', UserController::class);
            Route::resource('pelanggan', PelangganController::class);
        });
        Route::group(['middleware' => 'outlet'], function(){
            Route::resource('transaksi', TransaksiController::class);
            Route::get('report', [ReportController::class, 'index'])->name('report.index');
            Route::get('report/generate', [ReportController::class, 'generate'])->name('report.generate');
        });
    });
    Route::get('produk-json', [ProdukController::class, 'produkJson'])->name('produk.json');
});