<?php

use App\Http\Controllers\Api\AlertController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BullyingController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\StudentController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/bullying-reports', [BullyingController::class, 'store']);
    Route::post('/chat/messages', [ChatController::class, 'storeMessage']);
});

Route::middleware(['auth:sanctum', 'role:admin,teacher'])->group(function () {
    Route::get('/students', [StudentController::class, 'index']);
    Route::get('/students/{student}', [StudentController::class, 'show']);

    Route::get('/grades', [GradeController::class, 'index']);
    Route::post('/grades', [GradeController::class, 'store']);

    Route::get('/alerts', [AlertController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/alerts/evaluate/{student}', [AlertController::class, 'evaluate']);
    Route::get('/bullying-reports', [BullyingController::class, 'index']);
});

Route::middleware(['auth:sanctum', 'role:admin,teacher,parent'])->get('/chat/rooms', [ChatController::class, 'rooms']);
