<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DataController;
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


// Auth
Route::post('login', [AuthController::class, 'login']);





Route::middleware('auth:api')->group(function () {
  Route::get('status-absen/{id}', [DataController::class, 'statusAbsen']);
  Route::post('absen-masuk', [DataController::class, 'absenMasuk']);
  Route::post('absen-pulang', [DataController::class, 'absenPulang']);

  Route::get('data-absen/{id}', [DataController::class, 'dataAbsen']);

  Route::get('status-cuti/{id}', [DataController::class, 'statusCuti']);
  Route::post('cuti', [DataController::class, 'kirimCuti']);

  Route::post('profil', [DataController::class, 'updateProfile']);

  Route::get('user', function (Request $req) {
    return response()->json([
      'user' => $req->user()
    ]);
  });
  Route::get('logout', [AuthController::class, 'logout']);
});
