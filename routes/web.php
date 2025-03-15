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

// Rotas para o Login com Rate Limiting aplicado em RouteServiceProvider.php
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:login');

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

// Rotas para Itens em Estoque
Route::middleware('auth')->group(function () {
    Route::resource('items', ItemInStockController::class);
    Route::get('/items/{itemInStock}/edit', [ItemInStockController::class, 'edit'])->name('items.edit');
    Route::put('/items/{itemInStock}', [ItemInStockController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [ItemInStockController::class, 'destroy'])->name('items.destroy');
    Route::get('/items/{id}/movements', [StockMovementController::class, 'index'])->name('items.movements');
});

// Rotas para Movimentação de Estoque
Route::middleware('auth')->group(function () {
    Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
    Route::get('/stock-movements/export', [StockMovementController::class, 'export'])->name('stock-movements.export');
    Route::get('/stock-movements/create', [StockMovementController::class, 'create'])->name('stock-movements.create');
});

// Rotas para Exportação
Route::middleware('auth')->group(function () {
    Route::get('/items/export/csv', [ItemInStockController::class, 'exportCsv'])->name('items.export.csv');
    Route::get('/items/export/pdf', [ItemInStockController::class, 'exportPdf'])->name('items.export.pdf');
});

// Rotas para Categorias
Route::resource('categories', CategoryController::class)->middleware('auth');





