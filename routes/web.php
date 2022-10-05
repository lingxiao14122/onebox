<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

Route::get('/lazada', function(Request $request) {
    Log::info(var_export($request, true));
    return response('', 200);
});

Route::get('/', function () {
    if (Auth::check()) {
        return view('landing');
    } else {
        return redirect('login');
    }
});

Route::get('/logout', [UserController::class, 'logout']);

Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', [UserController::class, 'login'])->name('login');
    Route::post('/users/login', [UserController::class, 'authenticate']);
});


Route::group(['middleware' => ['auth']], function () {
    Route::resource('item', ItemController::class);
    Route::get('/transaction', [TransactionController::class, 'index']);
    Route::get('/transaction/create', [TransactionController::class, 'create']);
    Route::post('/transaction', [TransactionController::class, 'store']);
    Route::get('/transaction/{transaction}', [TransactionController::class, 'show']);
    
    Route::group(['middleware' => ['can:admin']], function () {
        Route::get('/user', [UserController::class, 'index']);
        Route::get('/register', [UserController::class, 'create']);
        Route::post('/user', [UserController::class, 'store']);
        Route::get('/user/{user}', [UserController::class, 'edit']);
        Route::put('/user/{user}', [UserController::class, 'update']);
    });

    Route::get('notifications', function() {
        return auth()->user()->notifications->toJson();
    });
});