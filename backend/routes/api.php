<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('projects')->group(function () {
    Route::get('/', [ProjectController::class, 'index']);
    Route::get('/{id}', [ProjectController::class, 'show']);
    Route::post('/', [ProjectController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/{id}', [ProjectController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{id}', [ProjectController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index']);
    Route::get('/{id}', [TaskController::class, 'show']);
    Route::post('/', [TaskController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/{id}', [TaskController::class, 'update'])->middleware('auth:sanctum');
    Route::patch('/{id}/status', [TaskController::class, 'updateStatus'])->middleware('auth:sanctum');
    Route::delete('/{id}', [TaskController::class, 'destroy'])->middleware('auth:sanctum');

    Route::post('/{id}/comments', [TaskCommentController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/{id}/comments', [TaskCommentController::class, 'index']);
});
