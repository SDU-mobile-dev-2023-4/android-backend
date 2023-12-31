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
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="User name",
     *                      example="John Doe",
     *                      nullable=false,
     *                      maxLength=255
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="User email",
     *                      example="demo@example.com",
     *                      nullable=false,
     *                      maxLength=255
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      description="User password",
     *                      example="password",
     *                      nullable=false,
     *                      maxLength=255
     *                  ),
     *                  @OA\Property(
     *                      property="device_name",
     *                      type="string",
     *                      description="The name of the device",
     *                      example="John's iPhone",
     *                      nullable=false,
     *                      maxLength=255
     *                  )
     *              )
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
     *      description="This endpoint is used to login a user. <br><br>You have to provide the following informations: <br>Email <br>Password<br>Device Name: This can be a name of the phone, or a browser name<br><br>The endpoint will return an user object, with a token, the token is a bearer token, which must be attached in the header of all request, this token is used to identify the user, in the system.",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="User email",
     *                      example="demo@example.com",
     *                      nullable=false
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      description="User password",
     *                      example="password",
     *                      nullable=false
     *                  ),
     *                  @OA\Property(
     *                      property="device_name",
     *                      type="string",
     *                      description="The name of the device",
     *                      example="John's iPhone",
     *                      nullable=false
     *                  )
     *              )
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
