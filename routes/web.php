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


Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/accueil', [App\Http\Controllers\HomeController::class, 'index'])->name('home.alt');


Route::get('/a-propos', function () {
    return view('about');
})->name('about');


Route::middleware(['auth'])->group(function () {
    
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
});


Route::middleware(['auth'])->group(function () {
    
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    
    
    Route::get('/catalogue', [App\Http\Controllers\ProductController::class, 'catalogue'])->name('catalogue.index');
    Route::get('/catalogue/{product}', [App\Http\Controllers\ProductController::class, 'fiche'])->name('catalogue.fiche');
    
   
    Route::prefix('panier')->name('cart.')->group(function () {
        Route::get('/', [App\Http\Controllers\CartController::class, 'index'])->name('index');
        Route::post('/ajouter/{product}', [App\Http\Controllers\CartController::class, 'add'])->name('add');
        Route::post('/modifier/{product}', [App\Http\Controllers\CartController::class, 'update'])->name('update');
        Route::post('/supprimer/{product}', [App\Http\Controllers\CartController::class, 'remove'])->name('remove');
    });
    
   
    Route::prefix('mon-compte')->name('profile.')->group(function () {
        Route::get('/', [App\Http\Controllers\ProfileController::class, 'show'])->name('show');
        Route::post('/', [App\Http\Controllers\ProfileController::class, 'update'])->name('update');
    });
    
    
    Route::prefix('commandes')->name('orders.')->group(function () {
        Route::get('/', [App\Http\Controllers\OrderController::class, 'index'])->name('index');
        Route::get('/nouvelle', [App\Http\Controllers\OrderController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\OrderController::class, 'store'])->name('store');
        Route::get('/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('show');
    });
    
 
    Route::prefix('factures')->name('invoices.')->group(function () {
        Route::get('/generer/{order}', [App\Http\Controllers\InvoiceController::class, 'generate'])->name('generate');
        Route::get('/telecharger/{invoice}', [App\Http\Controllers\InvoiceController::class, 'download'])->name('download');
    });
});


Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
   
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    
    Route::get('produits', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
    Route::get('produits/creer', [App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
    Route::post('produits', [App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
    Route::get('produits/{product}/modifier', [App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
    Route::put('produits/{product}', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
    Route::delete('produits/{product}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
    
   
    Route::get('produits/{product}/stock', [App\Http\Controllers\Admin\StockController::class, 'showAdjustForm'])->name('products.stock.edit');
    Route::put('produits/{product}/stock', [App\Http\Controllers\Admin\StockController::class, 'adjust'])->name('products.stock.update');
    
    
    Route::get('produits/liste', [App\Http\Controllers\ProductController::class, 'index'])->name('products.liste');
    
    
    Route::prefix('produits/{product}/photos')->name('products.photos.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ProductPhotoController::class, 'index'])->name('index');
        Route::get('/ajouter', [App\Http\Controllers\Admin\ProductPhotoController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\ProductPhotoController::class, 'store'])->name('store');
        Route::get('/organiser', [App\Http\Controllers\Admin\ProductPhotoController::class, 'edit'])->name('edit');
        Route::put('/ordre', [App\Http\Controllers\Admin\ProductPhotoController::class, 'updateOrder'])->name('update-order');
        Route::post('/{photo}/definir-principale', [App\Http\Controllers\Admin\ProductPhotoController::class, 'setPrimary'])->name('set-primary');
        Route::delete('/{photo}', [App\Http\Controllers\Admin\ProductPhotoController::class, 'destroy'])->name('destroy');
    });
    
    
    Route::resource('commandes', App\Http\Controllers\Admin\OrderController::class)->names('orders');
    Route::patch('commandes/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])
        ->name('orders.update-status');
    
  
    Route::resource('utilisateurs', App\Http\Controllers\Admin\UserController::class, [
        'names' => 'users',
        'parameters' => ['utilisateurs' => 'user']
    ])->except(['show']);
    
    
    Route::get('utilisateurs/export', [App\Http\Controllers\Admin\UserController::class, 'export'])
        ->name('users.export');
    
    // Routes pour la gestion des contacts
    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ContactController::class, 'index'])->name('index');
        Route::get('/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'show'])->name('show');
        Route::put('/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'update'])->name('update');
        Route::delete('/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('destroy');
        Route::post('/{contact}/marquer-non-lu', [App\Http\Controllers\Admin\ContactController::class, 'markAsUnread'])
            ->name('mark-as-unread');
    });
    
    Route::post('factures/signer/{invoice}', [App\Http\Controllers\InvoiceController::class, 'sign'])
        ->name('invoices.sign');
});
