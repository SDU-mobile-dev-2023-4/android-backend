<?php

use Illuminate\Support\Facades\Route;


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

/**
 * Routes protected by sanctum
 * auth:sanctum has been removed, as the login verification is done in the CheckAuthentication middleware
 */
Route::group(['namespace' => 'App\Http\Controllers\api', 'middleware' => [ 'check-auth']], function () {
    Route::apiResource('/groups',   'Group\GroupController');
    Route::apiResource('/users',   'User\UserController');
    Route::apiResource('/expenses', 'Expense\ExpenseController', ['only' => ['store']]);

    Route::post  ('/groups/{group}/users',    'Group\GroupController@addUserToGroup');
    Route::delete('/groups/{group}/users/{user}',    'Group\GroupController@removeUserFromGroup');
    Route::post  ('/groups/{group}/expenses', 'Group\GroupController@addExpenseToGroup');
    Route::post  ('/groups/{group}/notify',   'Group\GroupController@notify');

    Route::get('/notifications', 'Notification\NotificationController@index');
});


/**
 * Routes not protected by sanctum
 */
Route::group(['namespace' => 'App\Http\Controllers\api'], function () {
    Route::post('/register', 'User\AuthenticationController@register');
    Route::post('/login',    'User\AuthenticationController@login');
});
