<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\InsuranceCompaniesController;
use App\Http\Middleware\MasterMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/forgot-password', [AuthController::class, 'forgot']);
Route::post('/login/reset', [AuthController::class, 'reset']);

Route::post('/contact-us', [ContactUsController::class, 'create']);

Route::get('/banner', [BannerController::class, 'index']);
Route::get('/comment', [CommentController::class, 'index']);
Route::get('/contact', [ContactController::class, 'index']);
Route::get('/insurance-companies', [InsuranceCompaniesController::class, 'index']);





Route::post('/register', [RegisterController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/user/password', [AuthController::class, 'updatePassword']);




    Route::middleware(MasterMiddleware::class)->group(function () {
        // Thinks that only Masters can do!
        Route::post('/banner', [BannerController::class, 'store']);
        Route::get('/banner/{banner}', [BannerController::class, 'show']);
        Route::put('/banner/{banner}', [BannerController::class, 'update']);
        Route::delete('/banner/{banner}', [BannerController::class, 'destroy']);

        Route::post('/comment', [CommentController::class, 'store']);
        Route::get('/comment/{comment}', [CommentController::class, 'show']);
        Route::put('/comment/{comment}', [CommentController::class, 'update']);
        Route::delete('/comment/{comment}', [CommentController::class, 'destroy']);

        Route::post('/contact', [ContactController::class, 'store']);
        Route::get('/contact/{contact}', [ContactController::class, 'show']);
        Route::put('/contact/{contact}', [ContactController::class, 'update']);
        Route::delete('/contact/{contact}', [ContactController::class, 'destroy']);

        Route::get('/contact-us', [ContactUsController::class, 'index']);
        Route::put('/contact-us/{contactUs}', [ContactUsController::class, 'update']);

        Route::post('/insurance-companies', [InsuranceCompaniesController::class, 'store']);
        Route::get('/insurance-companies/{insuranceCompanies}', [InsuranceCompaniesController::class, 'show']);
        Route::put('/insurance-companies/{insuranceCompanies}', [InsuranceCompaniesController::class, 'update']);
        Route::delete('/insurance-companies/{insuranceCompanies}', [InsuranceCompaniesController::class, 'destroy']);
    });
});
