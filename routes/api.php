<?php

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::apiResource('items', ItemController::class); https://laravel.com/docs/9.x/controllers#api-resource-routes

Route::get('item', function() {
    return Item::all()->toJson();
});

Route::get('item/search', function(Request $request) {
    $query = Item::where('name', 'like', '%'. $request->input('q') .'%')->get();
    return $query->toJson();
})->withoutMiddleware('api')->middleware('throttle:none');
