<?php

use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\WorkWithUsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/contact', [ContactUsController::class, 'create']);

Route::post('/work_with_us', [WorkWithUsController::class, 'create']);

Route::post('/register', [RegisterController::class, 'store']);


