<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LiveStatsController;

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

Route::group(['middleware' => 'auth:sanctum'], function(){
    //All secure URL's
    Route::post('/logout', [UserController::class,'logout']);
});

Route::get('/live-stats', [LiveStatsController::class, 'getLiveStats']);
Route::post("login",[UserController::class,'index']);
Route::post('/register', [UserController::class,'register']);
Route::get("users",[UserController::class,'getUsers']);
