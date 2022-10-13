<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RedisController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
Route::get('/redis', [RedisController::class, 'index']);
Route::get('/', function() {
    return redirect()->route('login');
});
Route::get('/login', [UserController::class, 'loginPage'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

Route::name('products.')->group(function () {

    Route::get('/product', [UserController::class, 'index'])->name('index');
});

Route::middleware('auth')->name('users.')->group(function () {

    Route::get('/user', [UserController::class, 'index'])->name('index');
    Route::get('/user-list', [UserController::class, 'show'])->name('show');
    Route::post('/user-add', [UserController::class, 'add']);
    Route::post('/user-delete', [UserController::class, 'delete'])->name('delete');
    Route::post('/user-status', [UserController::class, 'changeStatus'])->name('changeStatus');
    Route::get('/user-edit', [UserController::class, 'edit']);
    Route::post('/user-edit', [UserController::class, 'store']);
});

Route::middleware('auth')->name('customers.')->group(function () {

    Route::get('/customer', [CustomerController::class, 'index'])->name('index');
    Route::get('/customer-list', [CustomerController::class, 'show'])->name('show');
    Route::post('/customer-add', [CustomerController::class, 'add']);
    Route::post('/customer-import', [CustomerController::class, 'import'])->name('import');
    Route::post('/customer-export', [CustomerController::class, 'export'])->name('export');
    Route::post('/customer-edit', [CustomerController::class, 'store']);
});

Route::middleware('auth')->name('products.')->group(function () {

    Route::get('/product', [ProductController::class, 'index'])->name('index');
    Route::get('/product-list', [ProductController::class, 'show'])->name('show');
    Route::get('/product-add', [ProductController::class, 'addForm'])->name('add');
    Route::post('/product-add', [ProductController::class, 'add']);
    Route::get('/product-edit/{id}', [ProductController::class, 'editForm'])->name('edit');
    Route::post('/product-edit/{id}', [ProductController::class, 'store']);
    Route::post('/product-delete', [ProductController::class, 'delete'])->name('delete');  
});

