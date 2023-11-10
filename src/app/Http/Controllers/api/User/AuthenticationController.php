<?php

namespace App\Http\Controllers\api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/register",
     *      tags={"Authentication"},
     *      summary="Register",
     *      description="This endpoint is used to register a new user.",
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          required=true,
     *          description="The name of the user",
     *          @OA\Schema(
     *            type="string"
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          required=true,
     *          description="The email of the user",
     *          @OA\Schema(
     *              type="string",
     *              format="email"
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          required=true,
     *          description="The password of the user",
     *          @OA\Schema(
     *              type="string",
     *              format="password"
     *          ),    
     *      ),
     *      @OA\Parameter(
     *          name="device_name",
     *          in="query",
     *          required=true,
     *          description="The name of the device",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  example="John Doe",
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  example="john.doe@example.com",
     *              ),
     *              @OA\Property(
     *                  property="updated_at",
     *                  type="string",
     *                  format="date-time"
     *              ),
     *              @OA\Property(
     *                  property="created_at",
     *                  type="string",
     *                  format="date-time"
     *              ),
     *              @OA\Property(
     *                  property="id",
     *                  type="integer",
     *                  format="int64",
     *                  example=1,
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=409,
     *          description="Email already exists",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Email already exists",
     *                  description="The message returned when the email already exists"
     *              ),
     *          ),
     *      )
     * )
     */
    public function register(RegisterRequest $request)
    { 
        // Check if there is a user with the email
        if (User::where('email', $request->email)->exists()) {
            return response(["message" => "Email already exists"], 409);
        }



        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        $return = $user->toArray();
        $return['token'] = $user->createToken($request->device_name)->plainTextToken;

        return response($return,200);
        
    }


    /**
     * @OA\Post(
     *      path="/api/login",
     *      tags={"Authentication"},
     *      summary="Login",
     *      description="This endpoint is used to login.",
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *            type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *      ),
     *      @OA\Parameter(
     *          name="device_name",
     *          in="query",
     *          required=true,
     *          description="The name of the device",
     *          @OA\Schema(
     *            type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Group response",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                property="id",
     *                type="integer",
     *                format="int64",
     *                example=1
     *              ),
     *              @OA\Property(
     *                property="name",
     *                type="string",
     *                example="John Doe",
     *              ),
     *              @OA\Property(
     *                property="email",
     *                type="string",
     *                example="john.doe@example.com",
     *              ),
     *              @OA\Property(
     *                property="email_verified_at",
     *                type="string",
     *                format="date-time"
     *              ),
     *              @OA\Property(
     *                property="created_at",
     *                type="string",
     *                format="date-time"
     *              ),
     *              @OA\Property(
     *                property="updated_at",
     *                type="string",
     *                format="date-time"
     *              ),
     *              @OA\Property(
     *                property="token",
     *                type="string",
     *                example="1|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Invalid credentials",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                property="message",
     *                type="string",
     *                example="Invalid credentials",
     *                description="The message returned when the credentials are invalid"
     *              )
     *          )
     *      ),
     * )
     */
    public function login(Request $request) {
        
        $credentials = [
            "email" => $request->email,
            "password" => $request->password
        ];

        if (!auth()->attempt($credentials)) {
            return response(["message" => "Invalid credentials"], 401);
        }

        $user = User::where('email', $request->email)->first();
        $return = $user->toArray();
        $return['token'] = $user->createToken($request->device_name)->plainTextToken;

        return response($return,200);
    }

}
