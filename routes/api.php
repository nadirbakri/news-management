<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NewsController;
use App\Http\Middleware\AdminMiddleware;

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

Route::prefix('v1')->group(function () {
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::middleware('admin')->group(function () {
            Route::resource('news', NewsController::class);
            Route::post('/news/comment/{id}', [NewsController::class, 'comment']);
            Route::post('/user/statusUpdate/{id}', [UserController::class, 'statusUpdate']);
            Route::resource('user', UserController::class);
        });

        Route::resource('user', UserController::class)->only(['show']);
        Route::resource('news', NewsController::class)->only(['index', 'show']);
        Route::post('/news/comment/{id}', [NewsController::class, 'comment']);
    });
});
