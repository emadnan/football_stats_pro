<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LiveStatsController;
use App\Http\Controllers\MatchesController;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\MatchStatsController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\ListBuilderController;
use App\Http\Controllers\SummaryStatsCntroller;
use App\Http\Controllers\PaymentController;

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
    Route::get("/get-user/{id}", [UserController::class, 'getUserById']);
    Route::post('/update-user/{id}', [UserController::class, 'updateUser']); 
    Route::post('/delete-user/{id}', [UserController::class, 'deleteUser']); 
    Route::post('/logout', [UserController::class,'logout']);
    Route::get('/getSummaryStats', [LiveStatsController::class,'getSummaryStats']);
    Route::get("/get-matchesByDate/{date}", [MatchesController::class,'getMatchsByDate']);
    
    Route::post('/create_alert', [AlertController::class, 'createAlert']); 
    Route::get('/get-alerts', [AlertController::class, 'getAlerts']);
    Route::get('/get-alertsById/{id}', [AlertController::class, 'getAlertsById']);
    Route::post('/update-alerts/{id}', [AlertController::class, 'updateAlert']);
    Route::post('/delete-alerts/{id}', [AlertController::class, 'destroyAlert']);
    Route::post('/create_listBuilder', [ListBuilderController::class, 'createListBuilder']); 
    Route::get('/get-listBuilder', [ListBuilderController::class, 'getListBuilder']);
    Route::post('/update-listBuilder/{id}', [ListBuilderController::class, 'updateListBuilder']);
    Route::post('/delete-listBuilder/{id}', [ListBuilderController::class, 'destroyList']);
    Route::get("/search_league_name", [MatchesController::class,'searchLeague']);
    Route::get('/get-list_builderById/{id}', [ListBuilderController::class, 'getListBuilderById']);

});

Route::post("login", [UserController::class,'index']);
Route::post('/register', [UserController::class,'register']);
Route::get("users", [UserController::class,'getUsers']);
Route::post('/resend-verification', [UserController::class, 'resendVerification']);

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
Route::get("/h2hgames", [MatchStatsController::class,'createHead2Head']);
Route::get("/teamlg", [MatchStatsController::class,'createTeamLastMatches']);
Route::post('/download-stats', [SummaryStatsCntroller::class, 'getSummaryStatsByDate']);
Route::post('/summar-stats-advance-filter', [SummaryStatsCntroller::class, 'getSummaryStatsAdvaceFilter']);
Route::post('/download-matches', [MatchesController::class, 'getMatchesListByDate']);

Route::post('/forget-password', [UserController::class, 'forgetpassword']);
Route::post('/reset-password', [UserController::class, 'resetPassword']);
Route::post('/stripe',[UserController::class,'stripePost']);
Route::post('/create-payment-intent', [PaymentController::class, 'createPaymentIntent']);



