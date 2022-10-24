<?php

use App\Exports\TransactionsExport;
use App\Exports\UsersExport;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

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
    Route::get('/transaction', [TransactionController::class, 'index']);
    Route::get('/transaction/create', [TransactionController::class, 'create']);
    Route::get('/transaction/in', [TransactionController::class, 'in']);
    Route::get('/transaction/out', [TransactionController::class, 'out']);
    Route::get('/transaction/audit', [TransactionController::class, 'audit']);
    Route::post('/transaction', [TransactionController::class, 'store']);

    Route::get('/exports/transaction', function () {
        return Excel::download(new TransactionsExport, 'transactions.xlsx');
    });

    Route::get('notifications', function () {
        return auth()->user()->notifications->toJson();
    });
});

Route::group(['middleware' => ['auth', 'can:user']], function () {
    Route::resource('item', ItemController::class)->except(['destroy']);
});

Route::group(['middleware' => ['auth', 'can:admin']], function () {
    Route::get('/user', [UserController::class, 'index']);
    Route::get('/register', [UserController::class, 'create']);
    Route::post('/user', [UserController::class, 'store']);
    Route::get('/user/{user}', [UserController::class, 'edit']);
    Route::put('/user/{user}', [UserController::class, 'update']);

    Route::resource('item', ItemController::class);
    Route::get('/integration', [IntegrationController::class, 'index']);
    Route::get('/integration/auth/{platform}', [IntegrationController::class, 'auth']);
    Route::get('/integration/callback/{platform}', [IntegrationController::class, 'callback']);
    Route::get('/integration/{platform}', [IntegrationController::class, 'edit'])->middleware('lazada.authorized');
    Route::put('/integration/{platform}', [IntegrationController::class, 'update'])->middleware('lazada.authorized');
    Route::get('/integration/sync/{platform}', [IntegrationController::class, 'syncDown'])->middleware('lazada.authorized');
});
