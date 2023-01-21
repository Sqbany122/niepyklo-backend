<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "Api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => '/auth'], function () {
    Route::post('register', [\App\Http\Controllers\api\v1\Auth\AuthController::class, 'register'])
        ->name('auth.register');
    Route::post('login', [\App\Http\Controllers\api\v1\Auth\AuthController::class, 'login'])
        ->name('auth.login');
    Route::post('send-reset-password-code', \App\Http\Controllers\Api\v1\Auth\ForgotPasswordController::class)
        ->name('auth.send.reset.password.code');
    Route::post('reset-password', \App\Http\Controllers\Api\v1\Auth\ResetPasswordController::class)
        ->name('auth.reset.password');
});

Route::get('/posts', [\App\Http\Controllers\Api\v1\Post\PostController::class, 'index'])
    ->name('posts');

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/auth/user', function(\Illuminate\Http\Request $request) {
        return $request->user();
    })->name('auth.user');

    Route::post('/post/create', [\App\Http\Controllers\Api\v1\Post\PostController::class, 'store'])
        ->name('post.create');
});
