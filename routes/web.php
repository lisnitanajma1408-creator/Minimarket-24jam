<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductCatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\SuppliersController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ContactController;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/katalog', [ProductCatalogController::class, 'index'])->name('katalog');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/{product}/tambah', [CartController::class, 'add'])->name('cart.add');
Route::patch('/keranjang/{product}/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{product}/hapus', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/qris/{orderNumber}', [CheckoutController::class, 'qris'])->name('checkout.qris');
Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

Route::get('/ulasan', [ReviewController::class, 'index'])->name('ulasan');
Route::post('/ulasan', [ReviewController::class, 'store'])->name('ulasan.store');

Route::get('/lokasi', [LocationController::class, 'index'])->name('lokasi');

Route::get('/kontak', [ContactController::class, 'index'])->name('kontak');
Route::post('/kontak', [ContactController::class, 'store'])->name('kontak.store');

Route::view('/keunggulan', 'keunggulan')->name('keunggulan');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin,kasir'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::post('/inventory/{product}/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');

        Route::get('/suppliers', [SuppliersController::class, 'index'])->name('suppliers.index');
        Route::get('/suppliers/create', [SuppliersController::class, 'create'])->name('suppliers.create');
        Route::post('/suppliers', [SuppliersController::class, 'store'])->name('suppliers.store');
        Route::get('/suppliers/{supplier}/edit', [SuppliersController::class, 'edit'])->name('suppliers.edit');
        Route::put('/suppliers/{supplier}', [SuppliersController::class, 'update'])->name('suppliers.update');
        Route::delete('/suppliers/{supplier}', [SuppliersController::class, 'destroy'])->name('suppliers.destroy');
    });

require __DIR__.'/auth.php';