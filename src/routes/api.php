<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;


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
 */
Route::group(['namespace' => 'App\Http\Controllers\api'], function () {
    Route::apiResource('/groups',   'Group\GroupController');
    Route::apiResource('/users',    'user\UserController');
    Route::apiResource('/expenses', 'expense\ExpenseController');

    Route::post  ('/groups/{group}/add-user',    'api\Group\GroupController@addUserToGroup');
    Route::delete('/groups/{group}/remove-user', 'api\Group\GroupController@removeUserFromGroup');

})->middleware(['auth:sanctum']);


/**
 * Routes not protected by sanctum
 */
Route::group(['namespace' => 'App\Http\Controllers\api'], function () {
    Route::post('/register', 'User\AuthenticationController@register');
    Route::post('/login',    'User\AuthenticationController@login');
});


/**
 * DENNE DEL SKAL FJERNES OVER I EN CONTROLLER FOR SIG SELV, NU LIGGER DEN HER TIL TEST :D
 * 
 * @OA\Get(
 *   tags={"Tag"},
 *   path="Path",
 *   summary="Summary",
 *   @OA\Parameter(ref="#/components/parameters/id"),
 *   @OA\Response(response=200, description="OK"),
 *   @OA\Response(response=401, description="Unauthorized"),
 *   @OA\Response(response=404, description="Not Found")
 * )
 */

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);
 
    $user = User::where('email', $request->email)->first();
 
    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
 
    return $user->createToken($request->device_name)->plainTextToken;
});