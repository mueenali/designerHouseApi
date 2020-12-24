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

        //settings routes
        Route::put('settings/password', 'User\SettingsController@updatePassword'),
        Route::put('settings/profile', 'User\SettingsController@updateProfile'),

        //design routes
        Route::post('designs', 'Designs\DesignController@upload'),
        Route::put('designs/{id}', 'Designs\DesignController@update'),
        Route::delete('designs/{id}', 'Designs\DesignController@delete'),

        //comments routes
        Route::post('designs/{id}/comments', 'Designs\CommentController@store'),
        Route::put('comments/{id}', 'Designs\CommentController@update'),
        Route::delete('comments/{id}', 'Designs\CommentController@delete'),

        //like routes
        Route::post('designs/{id}/like', 'Designs\DesignController@like'),
        Route::get('designs/{id}/liked', 'Designs\DesignController@userHasLiked'),

        //Teams routes
        Route::post('teams', 'Teams\TeamsController@store'),
        Route::get('teams/{id}', 'Teams\TeamsController@findById'),
        Route::get('teams', 'Teams\TeamsController@index'),
        Route::get('users/teams', 'Teams\TeamsController@getUserTeams'),
        Route::put('teams/{id}', 'Teams\TeamsController@update'),
        Route::delete('teams/{id}', 'Teams\TeamsController@delete'),
        Route::delete('teams/{team_id}/users/{user_id}', 'Teams\TeamsController@removeMember'),

        //invitations route
        Route::post('invitations/{team_id}', 'Teams\InvitationsController@invite'),
        Route::post('invitations/{id}/resend', 'Teams\InvitationsController@resend'),
        Route::post('invitations/{id}/respond', 'Teams\InvitationsController@respond'),
        Route::delete('invitations/{id}', 'Teams\InvitationsController@delete'),
        Route::get('invitations', 'Teams\InvitationsController@index'),

        // chat routes
        Route::post('chats', 'Chats\ChatController@sendMessage'),
        Route::get('chats', 'Chats\ChatController@getUserChats'),
        Route::get('chats/{id}/messages', 'Chats\ChatController@getChatMessges'),
        Route::put('chats/{id}/markAsRead', 'Chats\ChatController@markAsRead'),
        Route::delete('messages/{id}', 'Chats\ChatController@deleteMessage')

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

Route::get('teams/slug/{slug}', 'TeamsController@findBySlug');

//design routes
Route::get('designs', 'Designs\DesignController@index');
Route::get('designs/{id}', 'Designs\DesignController@findDesign');

//users route
Route::get('users', 'User\UserController@index');

//search routes

Route::get('search/designs', 'Designs\DesignControllers@search');
Route::get('search/designers', 'Users\UserControllers@search');
