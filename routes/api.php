<?php

use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VapeController;
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

// harus memberikan token ketika login nya
Route::middleware('auth:sanctum')->group(function ()  {
// Khusus jika sudah login
// Ambil data user
Route::get('user', [UserController::class, 'fetch']);
// krim kan data user yang di inputkan
Route::post('user', [UserController::class, 'updateProfile']);
Route::post('user/photo', [UserController::class, 'updatePhoto']);
Route::post('logout', [UserController::class, 'logout']);

Route::get('transaction', [TransactionController::class, 'all']);
Route::post('transaction/{id}', [TransactionController::class, 'update']);
});

// Jika belum
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

Route::get('vaps', [VapeController::class, 'all']);
