<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemInStockController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\CategoryController;

// Página inicial
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rotas para o Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Rotas para o Registro
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// Rota para Logout
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth');

// Rotas protegidas
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


Route::controller(\App\Http\Controllers\ItemInStockController::class)->group(function () {
    Route::get('/items-in-stock', 'index')->name('items-in-stock');
})->middleware('auth');

Route::resource('items', ItemInStockController::class);
Route::delete('/items/{id}', [ItemInStockController::class, 'destroy'])->name('items.destroy');

Route::get('/items/{itemInStock}/edit', [ItemInStockController::class, 'edit'])->name('items.edit');
Route::put('/items/{itemInStock}', [ItemInStockController::class, 'update'])->name('items.update');

Route::get('/items/{id}/movements', [StockMovementController::class, 'index'])->name('items.movements');
Route::post('/items/{id}/movements/store', [StockMovementController::class, 'store'])->name('items.movements.store');

Route::get('/movements/{id}', [StockMovementController::class, 'index'])->name('movements.index');


/*Route::resource('items', ItemInStockController::class)->names([
    'store' => 'items.store',
]);*/


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/items', [ItemInStockController::class, 'store']);
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/items', [ItemInStockController::class, 'index'])->name('items.index');
//Route::get('/movements', [StockMovementController::class, 'index'])->name('movements.index');
Route::get('/items/{id}/movements', [StockMovementController::class, 'index'])->name('items.movements');

Route::resource('categories', CategoryController::class)->middleware('auth');

Route::get('/items/export/csv', [ItemInStockController::class, 'exportCsv'])->name('items.export.csv');
Route::get('/items/export/pdf', [ItemInStockController::class, 'exportPdf'])->name('items.export.pdf');

Route::middleware(['auth'])->group(function () {
    Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
    Route::get('/stock-movements/export', [StockMovementController::class, 'export'])->name('stock-movements.export');
    Route::get('/items/{id}/movements', [StockMovementController::class, 'itemHistory'])->name('items.movements');
});

Route::get('/stock-movements/create', [StockMovementController::class, 'create'])->name('stock-movements.create');





