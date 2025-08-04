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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Auth routes
Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Route d'accueil principale
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Alias pour la compatibilité
Route::get('/accueil', [App\Http\Controllers\HomeController::class, 'index'])->name('home.alt');

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
    // Routes utilisateur
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    
    // Catalogue et panier
    Route::get('/catalogue', [App\Http\Controllers\ProductController::class, 'catalogue'])->name('catalogue.index');
    Route::get('/catalogue/{product}', [App\Http\Controllers\ProductController::class, 'fiche'])->name('catalogue.fiche');
    
    // Gestion du panier
    Route::prefix('panier')->name('cart.')->group(function () {
        Route::get('/', [App\Http\Controllers\CartController::class, 'index'])->name('index');
        Route::post('/ajouter/{product}', [App\Http\Controllers\CartController::class, 'add'])->name('add');
        Route::post('/modifier/{product}', [App\Http\Controllers\CartController::class, 'update'])->name('update');
        Route::post('/supprimer/{product}', [App\Http\Controllers\CartController::class, 'remove'])->name('remove');
    });
    
    // Profil utilisateur
    Route::prefix('mon-compte')->name('profile.')->group(function () {
        Route::get('/', [App\Http\Controllers\ProfileController::class, 'show'])->name('show');
        Route::post('/', [App\Http\Controllers\ProfileController::class, 'update'])->name('update');
    });
    
    // Commandes
    Route::prefix('commandes')->name('orders.')->group(function () {
        Route::get('/', [App\Http\Controllers\OrderController::class, 'index'])->name('index');
        Route::get('/nouvelle', [App\Http\Controllers\OrderController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\OrderController::class, 'store'])->name('store');
        Route::get('/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('show');
    });
    
    // Factures
    Route::prefix('factures')->name('invoices.')->group(function () {
        Route::get('/generer/{order}', [App\Http\Controllers\InvoiceController::class, 'generate'])->name('generate');
        Route::get('/telecharger/{invoice}', [App\Http\Controllers\InvoiceController::class, 'download'])->name('download');
    });
});

// Routes d'administration
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Tableau de bord administrateur
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Gestion des produits - Définition manuelle des routes
    Route::get('produits', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
    Route::get('produits/creer', [App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
    Route::post('produits', [App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
    Route::get('produits/{product}/modifier', [App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
    Route::put('produits/{product}', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
    Route::delete('produits/{product}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
    
    // Gestion du stock
    Route::get('produits/{product}/stock', [App\Http\Controllers\Admin\StockController::class, 'showAdjustForm'])->name('products.stock.edit');
    Route::put('produits/{product}/stock', [App\Http\Controllers\Admin\StockController::class, 'adjust'])->name('products.stock.update');
    
    // Alias pour la compatibilité avec les URLs générées
    Route::get('produits/liste', [App\Http\Controllers\ProductController::class, 'index'])->name('products.liste');
    
    // Gestion des photos de produits
    Route::prefix('produits/{product}/photos')->name('products.photos.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ProductPhotoController::class, 'index'])->name('index');
        Route::get('/ajouter', [App\Http\Controllers\Admin\ProductPhotoController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\ProductPhotoController::class, 'store'])->name('store');
        Route::get('/organiser', [App\Http\Controllers\Admin\ProductPhotoController::class, 'edit'])->name('edit');
        Route::put('/ordre', [App\Http\Controllers\Admin\ProductPhotoController::class, 'updateOrder'])->name('update-order');
        Route::post('/{photo}/definir-principale', [App\Http\Controllers\Admin\ProductPhotoController::class, 'setPrimary'])->name('set-primary');
        Route::delete('/{photo}', [App\Http\Controllers\Admin\ProductPhotoController::class, 'destroy'])->name('destroy');
    });
    
    // Gestion des commandes
    Route::resource('commandes', App\Http\Controllers\Admin\OrderController::class)->names('orders');
    Route::patch('commandes/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])
        ->name('orders.update-status');
    
    // Gestion des utilisateurs
    Route::resource('utilisateurs', App\Http\Controllers\Admin\UserController::class, [
        'names' => 'users',
        'parameters' => ['utilisateurs' => 'user']
    ])->except(['show']);
    
    // Export des utilisateurs
    Route::get('utilisateurs/export', [App\Http\Controllers\Admin\UserController::class, 'export'])
        ->name('users.export');
    
        // Signature des factures
    Route::post('factures/signer/{invoice}', [App\Http\Controllers\InvoiceController::class, 'sign'])
        ->name('invoices.sign');
});
