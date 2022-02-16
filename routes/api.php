<?php

use App\Http\Controllers\TccController;
use DtmClient\Middleware\DtmLaravelMiddleware;
use Illuminate\Http\Request;
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

Route::group(['prefix' => '/tcc', 'middleware' => [DtmLaravelMiddleware::class]], function () {
    Route::any('/successCase', [TccController::class, 'successCase']);
    Route::any('/gid', [TccController::class, 'getGid']);
    Route::any('/transA/try', [TccController::class, 'transATry']);
    Route::any('/transA/confirm', [TccController::class, 'transAConfirm']);
    Route::any('/transA/confirm', [TccController::class, 'transACancel']);
    Route::any('/transB/try', [TccController::class, 'transBTry']);
    Route::any('/transB/confirm', [TccController::class, 'transBConfirm']);
    Route::any('/transB/confirm', [TccController::class, 'transBCancel']);
});

