<?php

namespace App\Http\Controllers\api\User;

use App\Helper\Sanitizer;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\User\LoginRequest;
use App\Http\Requests\api\User\RegisterRequest;
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
     *          example="John Doe",
     *          @OA\Schema(
     *            type="string",
     *            minLength=1,
     *            maxLength=255,
     *            example="John Doe",
     *            nullable=false
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          required=true,
     *          description="The email of the user. Must be unique.",
     *          @OA\Schema(
     *              type="string",
     *              format="email",
     *              example="john.doe@example.com",
     *              nullable=false,
     *              minLength=2,
     *              maxLength=255
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          required=true,
     *          description="The password of the user",
     *          @OA\Schema(
     *              type="string",
     *              format="password",
     *              minLength=6,
     *              maxLength=255,
     *              example="password",
     *              nullable=false
     *          ),    
     *      ),
     *      @OA\Parameter(
     *          name="device_name",
     *          in="query",
     *          required=true,
     *          description="The name of the device",
     *          @OA\Schema(
     *              type="string",
     *              minLength=2,
     *              maxLength=255,
     *              example="John's iPhone",
     *              nullable=false
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User created successfully",
     *          @OA\JsonContent(
     *              allOf={
     *                  @OA\Schema(
     *                    ref="#/components/schemas/User"
     *                  ),
     *                  @OA\Schema(
     *                    @OA\Property(
     *                      property="token",
     *                      type="string",
     *                      example="1|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
     *                    )
     *                  )
     *              }
     *          ),
     *      ),
     *      @OA\Response(
     *        response=422,
     *        description="Bad Request - One or more errors with the input data",
     *        @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *      )
     * )
     */
    public function register(RegisterRequest $request)
    {
        // Get input data
        $data = $request->validated();

        // Create user
        $user = new User;
        $user->name = Sanitizer::sanitize($data['name']);
        $user->email = Sanitizer::sanitize($data['email']);
        $user->password = Sanitizer::sanitize($data['password']);
        $user->save();

        // Generate auth token for api
        $token = $user->createToken($data['device_name'])->plainTextToken;

        // Generate output
        $return = $user->toArray();
        $return['token'] = $token;

        // Return response
        return response($return, 200);
    }


    /**
     * @OA\Post(
     *      path="/api/login",
     *      tags={"Authentication"},
     *      summary="Login",
     *      description="This endpoint is used to login a user, by returning a authentication token.",
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          required=true,
     *          description="The email of the user.",
     *          @OA\Schema(
     *              type="string",
     *              format="email",
     *              example="john.doe@example.com",
     *              nullable=false,
     *              minLength=2,
     *              maxLength=255
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          required=true,
     *          description="The password of the user",
     *          @OA\Schema(
     *              type="string",
     *              format="password",
     *              minLength=6,
     *              maxLength=255,
     *              example="password",
     *              nullable=false
     *          ),    
     *      ),
     *      @OA\Parameter(
     *          name="device_name",
     *          in="query",
     *          required=true,
     *          description="The name of the device",
     *          @OA\Schema(
     *              type="string",
     *              minLength=2,
     *              maxLength=255,
     *              example="John's iPhone",
     *              nullable=false
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Group response",
     *          @OA\JsonContent(
     *              allOf={
     *                  @OA\Schema(
     *                    ref="#/components/schemas/User"
     *                  ),
     *                  @OA\Schema(
     *                    @OA\Property(
     *                      property="token",
     *                      type="string",
     *                      example="1|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
     *                    )
     *                  )
     *              }
     *          )
     *      ),
     *      @OA\Response(
     *        response=401,
     *        description="Invalid credentials",
     *        @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Invalid credentials"
     *              )
     *        )
     *      ),
     *      @OA\Response(
     *        response=422,
     *        description="Bad Request - One or more errors with the input data",
     *        @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *      )
     * )
     */
    public function login(LoginRequest $request)
    {
        // Get input data
        $data = $request->validated();

        // Generate credentials array
        $credentials = [
            "email" => Sanitizer::sanitize($data['email']),
            "password" => Sanitizer::sanitize($data['password'])
        ];

        // Check credentials
        if (!auth()->attempt($credentials)) {
            return response([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Get user
        $user = User::where('email', $data['email'])->first();

        // Generate auth token for api
        $token = $user->createToken($data['device_name'])->plainTextToken;

        // Generate output
        $return = $user->toArray();
        $return['token'] = $token;

        return response($return, 200);
    }
}
