<?php

use App\Http\Controllers\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


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

Route::post('/signup', [AuthController::class, 'signUp']);
Route::post('/login', [AuthController::class, 'logIn']);
Route::get('/logout', [AuthController::class, 'logOut'])->middleware('auth:api');
Route::post('/chat/sendMessage',  [MessageController::class, 'sendMessage'])->middleware('auth:api');
Route::post('/chat/getConversation',  [MessageController::class, 'getConversation'])->middleware('auth:api');
