<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/pesanan', [OrderController::class, 'store'])->middleware('throttle:10,1')->name('orders.store');
Route::get('/pesanan/terkirim', [OrderController::class, 'sent'])->name('orders.sent');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');

    Route::get('pesanan', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('pesanan/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('pesanan/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');

    Route::patch('menu/{menu}/stok', [Admin\MenuController::class, 'toggle'])->name('menus.toggle');
    Route::resource('menu', Admin\MenuController::class)->except('show')->names('menus');

    Route::resource('kategori', Admin\CategoryController::class)->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['kategori' => 'category'])->names('categories');
});

require __DIR__.'/auth.php';
