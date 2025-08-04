<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StockController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group and the "admin" middleware.
|
*/

// Tableau de bord administrateur
Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

// Gestion des stocks
Route::prefix('stocks')->name('stocks.')->group(function () {
    Route::get('/', [StockController::class, 'index'])->name('index');
    Route::get('/dashboard', [StockController::class, 'dashboard'])->name('dashboard');
    Route::get('/create', [StockController::class, 'create'])->name('create');
    Route::post('/', [StockController::class, 'store'])->name('store');
    Route::get('/{stock}', [StockController::class, 'show'])->name('show');
    Route::get('/{stock}/edit', [StockController::class, 'edit'])->name('edit');
    Route::put('/{stock}', [StockController::class, 'update'])->name('update');
    Route::delete('/{stock}', [StockController::class, 'destroy'])->name('destroy');
    
    // Routes pour la gestion des ajustements de stock
    Route::get('/{stock}/adjust', [StockController::class, 'showAdjustForm'])->name('adjust');
    Route::post('/{stock}/adjust', [StockController::class, 'adjust'])->name('adjust.submit');
});

// Autres routes administratives peuvent être ajoutées ici...
