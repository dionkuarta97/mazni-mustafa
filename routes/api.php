<?php

use App\Http\Controllers\PendanaanController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [UsersController::class, 'login']);
Route::get('/pendanaan', [PendanaanController::class, 'getPendanaan']);
Route::get('/metadata', [PendanaanController::class, 'getMetaData']);

Route::post('/pendanaan', [PendanaanController::class, 'add'])->middleware('JwtAuth');
Route::delete('/pendanaan/{id}', [PendanaanController::class, 'delete'])->middleware('JwtAuth');
Route::put('/pendanaan/{id}', [PendanaanController::class, 'update'])->middleware('JwtAuth');
