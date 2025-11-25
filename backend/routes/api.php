<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('project')->group(function () {
    Route::get('/', [ProjectController::class, 'index']);
    Route::get('/{id}', [ProjectController::class, 'show']);
    Route::post('/', [ProjectController::class, 'store']);
    Route::put('/{id}', [ProjectController::class, 'update']);
    Route::delete('/{id}', [ProjectController::class, 'destroy']);
})->middleware('auth:sanctum');

Route::prefix('task')->group(function () {
    Route::get('/', [TaskController::class, 'index']);
    Route::get('/{id}', [TaskController::class, 'show']);
    Route::post('/', [TaskController::class, 'store']);
    Route::put('/{id}', [TaskController::class, 'update']);
    Route::patch('/{id}/status', [TaskController::class, 'updateStatus']);
    Route::patch('/{id}/priority', [TaskController::class, 'updatePriority']);
    Route::delete('/{id}', [TaskController::class, 'destroy']);

    Route::post('/{id}/comments', [TaskCommentController::class, 'store']);
    Route::get('/{id}/comments', [TaskCommentController::class, 'index']);
    Route::put('/comments/{commentId}', [TaskCommentController::class, 'update']);
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/stats', [StatsController::class, 'index'])->middleware('auth:sanctum');
