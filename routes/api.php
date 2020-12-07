<?php

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

Route::group(['middleware' => ['auth:api']], fn() => [
        Route::post('logout', 'Auth\LoginController@logout'),
        Route::put('settings/password', 'User\SettingsController@updatePassword'),
        Route::put('settings/profile', 'User\SettingsController@updateProfile'),
        Route::post('designs', 'Designs\DesignController@upload'),
        Route::put('designs/{id}', 'Designs\DesignController@update'),
        Route::delete('designs/{id}', 'Designs\DesignController@delete'),
        Route::get('designs', 'Designs\DesignController@index'),
        Route::get('designs/{id}', 'Designs\DesignController@findDesign'),
        Route::get('users', 'User\UserController@index')
    ]
);

Route::group(['middleware' => ['guest:api']], fn() => [
        Route::post('register', 'Auth\RegisterController@register'),
        Route::post('verification/verify/{user}', 'Auth\VerificationController@verify')
        ->name('verification.verify'),
        Route::post('verification/resend', 'Auth\VerificationController@resend'),
        Route::post('login', 'Auth\LoginController@login'),
        Route::post('forgetPassword', 'Auth\ForgotPasswordController@sendResetLinkEmail'),
        Route::post('resetPassword', 'Auth\ResetPasswordController@reset')
    ]
);

Route::get('currentUser', 'User\UserController@getCurrentUser');

