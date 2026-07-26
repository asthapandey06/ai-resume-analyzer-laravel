<?php

use App\Http\Controllers\HelloController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/resumes', [ResumeController::class, 'store']);

Route::get('/resumes', [ResumeController::class, 'index']);

Route::get('/resumes/{resume}', [ResumeController::class, 'show']);

Route::delete('/resumes/{resume}', [ResumeController::class, 'destroy']);
