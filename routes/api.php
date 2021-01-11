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

Route::group(['middleware' => ['auth:api', 'cacheable:120']], function ()
{
        Route::post('logout', 'Auth\LoginController@logout');
        Route::get('users/teams', 'Teams\TeamsController@getUserTeams');

        //settings routes
        Route::prefix('settings')->group(function ()
        {
            Route::put('password', 'User\SettingsController@updatePassword');
            Route::put('profile', 'User\SettingsController@updateProfile');
        });

        Route::prefix('designs')->group(function ()
        {
            //design routes
            Route::post('/', 'Designs\DesignController@upload');
            Route::put('{id}', 'Designs\DesignController@update');
            Route::delete('{id}', 'Designs\DesignController@delete');
            Route::post('{id}/comments', 'Designs\CommentController@store');

            //like routes
            Route::post('{id}/like', 'Designs\DesignController@like');
            Route::get('{id}/liked', 'Designs\DesignController@userHasLiked');
        });

        //comments routes
        Route::prefix('comments')->group(function ()
        {
            Route::put('{id}', 'Designs\CommentController@update');
            Route::delete('{id}', 'Designs\CommentController@delete');
        });

        Route::prefix('teams')->group(function ()
        {
            //Teams routes
            Route::post('/', 'Teams\TeamsController@store');
            Route::get('{id}', 'Teams\TeamsController@findById');
            Route::get('/', 'Teams\TeamsController@index');
            Route::put('{id}', 'Teams\TeamsController@update');
            Route::delete('{id}', 'Teams\TeamsController@delete');
            Route::delete('{team_id}/users/{user_id}', 'Teams\TeamsController@removeMember');
        });

        Route::prefix('invitations')->group(function ()
        {
            //invitations route
            Route::post('{team_id}', 'Teams\InvitationsController@invite');
            Route::post('{id}/resend', 'Teams\InvitationsController@resend');
            Route::post('{id}/respond', 'Teams\InvitationsController@respond');
            Route::delete('{id}', 'Teams\InvitationsController@delete');
            Route::get('/', 'Teams\InvitationsController@index');
        });


        Route::prefix('chats')->group(function ()
        {
            // chat routes
            Route::post('/', 'Chats\ChatController@sendMessage');
            Route::get('/', 'Chats\ChatController@getUserChats');
            Route::get('{id}/messages', 'Chats\ChatController@getChatMessges');
            Route::put('{id}/markAsRead', 'Chats\ChatController@markAsRead');
            Route::delete('messages/{id}', 'Chats\ChatController@deleteMessage');
        });

});

Route::group(['middleware' => ['guest:api']], function () {

    Route::post('register', 'Auth\RegisterController@register');

    Route::prefix('verification')->group(function () {
        //verification routes
        Route::post('verify/{user}', 'Auth\VerificationController@verify')
            ->name('verification.verify');
        Route::post('resend', 'Auth\VerificationController@resend');
    });

    Route::post('login', 'Auth\LoginController@login');
    Route::post('forgetPassword', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('resetPassword', 'Auth\ResetPasswordController@reset');
});

Route::get('currentUser', 'User\UserController@getCurrentUser')->middleware('cacheable:120');

Route::prefix('teams')->middleware('cacheable:120')->group(function ()
{
    //teams routes
    Route::get('{team_id}/designs', 'Designs\DesignController@getTeamDesigns');
    Route::get('slug/{slug}', 'Teams\TeamsController@findBySlug');
});


Route::prefix('designs')->middleware('cacheable:120')->group(function ()
{
    //design routes
    Route::get('/', 'Designs\DesignController@index');
    Route::get('{id}', 'Designs\DesignController@findDesign');
    Route::get('slug/{slug}', 'Designs\DesignController@findBySlug');
});




Route::prefix('users')->middleware('cacheable:120')->group(function ()
{
    //users route
    Route::get('/', 'User\UserController@index');
    Route::get('{user_id}/designs', 'Designs\DesignController@getUserDesigns');
    Route::get('{username}', 'User\UserController@getByUsername');
});


Route::prefix('search')->middleware('cacheable:120')->group(function ()
{
    //search routes
    Route::get('designs', 'Designs\DesignControllers@search');
    Route::get('designers', 'Users\UserControllers@search');
});
