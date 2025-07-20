<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

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

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Auth routes
Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

Route::get('/accueil', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Public routes
Route::get('/a-propos', function () {
    return view('about');
})->name('about');

// Notification routes
Route::middleware(['auth'])->group(function () {
    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::get('/catalogue', [App\Http\Controllers\ProductController::class, 'catalogue'])->name('catalogue.index');
    Route::get('/catalogue/{product}', [App\Http\Controllers\ProductController::class, 'fiche'])->name('catalogue.fiche');
    Route::get('/panier', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/panier/ajouter/{product}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::post('/panier/modifier/{product}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::post('/panier/supprimer/{product}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::get('/admin/produits/create', [App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
    Route::post('/admin/produits', [App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
    Route::get('/admin/produits/{product}/edit', [App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/admin/produits/{product}', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
    Route::delete('/admin/produits/{product}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/infos', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::post('/infos', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/commande', [App\Http\Controllers\OrderController::class, 'create'])->name('order.create');
    Route::post('/commande', [App\Http\Controllers\OrderController::class, 'store'])->name('order.store');
    Route::get('/commandes', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/commandes/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    
    // Admin routes
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
        // Gestion des commandes
        Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
        Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])
            ->name('orders.update-status');
        
        // Gestion des utilisateurs
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['show']);
        
        // Routes supplémentaires pour les utilisateurs
        Route::get('users/export', [\App\Http\Controllers\Admin\UserController::class, 'export'])
            ->name('users.export');
    });

    // Routes pour les factures
    Route::prefix('factures')->name('invoices.')->group(function () {
        Route::get('/generer/{order}', [App\Http\Controllers\InvoiceController::class, 'generate'])->name('generate');
        Route::get('/telecharger/{invoice}', [App\Http\Controllers\InvoiceController::class, 'download'])->name('download');
        
        // Routes admin uniquement
        Route::middleware(['admin'])->group(function () {
            Route::post('/signer/{invoice}', [App\Http\Controllers\InvoiceController::class, 'sign'])->name('sign');
        });
    });
});
