<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\UserController;
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

Route::post('register', [RegisterController::class, 'register']);
Route::post('activate-account', [RegisterController::class, 'activate']);
Route::post('login', [LoginController::class, 'login']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group( function () {
    Route::post('logout', [LoginController::class, 'logout']);
    Route::post('send-invitation-link', [RegisterController::class, 'send_invitation_link']);
    Route::post('profile', [UserController::class, 'profile']);  
    Route::post('update-profile', [UserController::class, 'update_profile']);    
});

