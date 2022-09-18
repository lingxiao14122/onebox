<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\TransactionController;
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

Route::get('/', function () {
    return view('landing');
});

Route::group(['middleware' => ['guest']], function () {
    //create
    Route::get('/register', [UserController::class, 'create']);
    Route::post('/users', [UserController::class, 'store']);
    
    //login
    Route::get('/login', [UserController::class, 'login'])->name('login');
    Route::post('/users/login', [UserController::class, 'authenticate']);
});


Route::group(['middleware' => ['auth']], function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::resource('item', ItemController::class);
    Route::get('/transaction', [TransactionController::class, 'index']);
    Route::get('/transaction/create', [TransactionController::class, 'create']);
    Route::post('/transaction', [TransactionController::class, 'store']);
    Route::get('/transaction/{transaction}', [TransactionController::class, 'show']);
});
