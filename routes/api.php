<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

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

Route::post('login', [AuthController::class, 'login']); 
Route::get('getdropdowns', [AuthController::class, 'getDropDowns']);  
Route::post('forgotpassword', [AuthController::class, 'forgotPassword']);
Route::post('verifyresetcode', [AuthController::class, 'verifyResetCode']);
Route::post('resetpassword', [AuthController::class, 'resetPassword']);

//Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('batches', [AuthController::class, 'getBatchesForDriver']); 
    Route::post('dashboard', [AuthController::class, 'dashboardForDriver']);
    Route::post('updatelocation', [AuthController::class, 'updateLocation']);
    Route::post('storesurvey', [AuthController::class, 'storeSurvey']);
    Route::post('updatebatchdetail', [AuthController::class, 'updateBatchDetail']); 
    Route::post('updatebatch', [AuthController::class, 'updateBatch']); 
    Route::post('getbatchdetails', [AuthController::class, 'getBatchDetails']);   
    Route::post('driverprofile', [AuthController::class, 'DriversProfile']);  
    Route::post('driversleaderboard', [AuthController::class, 'driversLeaderBoard']);
    Route::post('changepassword', [AuthController::class, 'changePassword']); 
});