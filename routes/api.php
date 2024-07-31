<?php

use App\Http\Controllers\Api\KlubController;
use App\Http\Controllers\Api\LigaController;
use App\Http\Controllers\Api\PemainController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    // return $request->user();
// })->middleware('auth:sanctum');

// Route::get('liga', [LigaController::class, 'index']);
// Route::get('liga/{id}', [LigaController::class, 'show']);
// Route::post('liga', [LigaController::class, 'store']);
// Route::put('liga/{id}', [LigaController::class, 'update']);
// Route::delete('liga/{id}', [LigaController::class, 'destroy']);

// The correct command to use when defining a resource route is:

// Route::apiResource('liga', [LigaController::class]);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    // controller lainnya yang kemarin sudah dibuat simpan dibawah
    Route::apiResource('liga', LigaController::class);
    Route::apiResources(['klub' => KlubController::class]);
    Route::apiResources(['pemain' => PemainController::class]);
    // teruskan
});

// auth route
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
