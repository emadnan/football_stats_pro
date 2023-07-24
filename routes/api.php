<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LiveStatsController;
use App\Http\Controllers\MatchesController;
use App\Http\Controllers\SMSController;

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

Route::post("login", [UserController::class,'index']);
Route::post('/register', [UserController::class,'register']);
Route::get("users", [UserController::class,'getUsers']);

// New routes for OTP handling
Route::post('/verify-otp', [UserController::class, 'verifyOTP']); // Route to verify OTP during registration
// Route::post('/send-sms', [SMSController::class, 'sendSMS']); // How this Route will work and use in code to send OTP during registration?

Route::get('/live-stats', [LiveStatsController::class, 'getLiveStats']);

Route::get("/day-basic", [MatchesController::class,'getDayBasic']);
Route::get("/day-full", [MatchesController::class,'getDayFull']);
Route::get("/view-basic", [MatchesController::class,'getViewBasic']);
Route::get("/view-full", [MatchesController::class,'getViewFull']);
Route::get("/by-basic", [MatchesController::class,'getByBasic']);
Route::get("/by-full", [MatchesController::class,'getByFull']);
Route::get("/odds", [MatchesController::class,'getOdds']);
Route::get("/view-progressive", [MatchesController::class,'getViewProgressive']);
