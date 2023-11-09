<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

 /**
 * @OA\Info(
 *     version="1.0",
 *     title="WeShare API",
 * )
 * @OA\PathItem(path="/api")
 * 
 * @OA\Server(
 *      url="http://localhost:8000/api",
 *      description="WeShare API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 * )
 * 
 * 
 *  --- Errors
 * 
 *  @OA\Schema(
 *      schema="ErrorResponse",
 *      @OA\Property(
 *          property="message",
 *          type="string",
 *          example="The given data was invalid."
 *      ),
 *      @OA\Property(
 *          property="errors",
 *          type="object"
 *     )
 *    )
 *  )
 * 
 *  @OA\Schema(
 *      schema="UnauthorizedError",
 *      description="Unauthorized - Access token is missing or invalid",
 *      @OA\Property(
 *          property="message",
 *          type="string",
 *          example="Unauthorized"
 *      )
 *  )
 * 
 *  @OA\Schema(
 *      schema="ForbiddenError",
 *      description="Forbidden - You don't have permission to access this resource",
 *      @OA\Property(
 *          property="message",
 *          type="string",
 *          example="Forbidden"
 *      )
 *  )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
