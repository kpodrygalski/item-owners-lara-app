<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\APIAuthController;
use App\Http\Controllers\API\V1\APIItemController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('auth')->group(function () {
    Route::post('/login', [APIAuthController::class, 'login'])
        ->name('api-auth.login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->prefix('auth')->group(function () {
    Route::post('/logout', [APIAuthController::class, 'logout'])
        ->name('api-auth.logout');
});

// localhost:8000/api/items

Route::middleware('auth:sanctum')->controller(APIItemController::class)->prefix('items')->group(function () {
    Route::get('/', 'index')->name('items.get-all-items');
    Route::post('/', 'store')->name('items.create-new-item');
    Route::get('/{item}', 'show')->name('items.get-item-by-id');
    Route::put('/{item}', 'update')->name('items.update-item-by-id');
    Route::patch('/{item}/set-name', 'patchItemName')->name('items.patch-item-name');
    Route::delete('/{item}', 'destroy')->name('items.delete-item-by-id');
});
