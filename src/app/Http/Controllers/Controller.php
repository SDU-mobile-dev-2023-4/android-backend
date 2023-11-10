<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="UOMI API",
 * )
 * @OA\PathItem(path="/api")
 * 
 * 
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer"
 * )
 * 
 * 
 *  --- Errors
 * 
 *  @OA\Schema(
 *      schema="BadRequestError",
 *      title="Bad Request",
 *      description="Bad Request - The request was invalid or cannot be served. The exact error is sent in the message and further ellaborated for each section in the errors datapoint. E.g. „The JSON is not valid“",
 *      @OA\Property(
 *          property="message",
 *          type="string",
 *          example="The given data was invalid."
 *      ),
 *      @OA\Property(
 *        property="errors",
 *        type="object",
 *        description="The errors returned when the request is invalid, formatted as an object with the key being the field name and the value being an array of errors.",
 *        example={
 *          "field1": {
 *              "The field1 is required.",
 *              "The field1 must be at least 10 characters."
 *          },
 *          "field2": {
 *              "The field2 field is required.",
 *          },
 *        }
 *      )
 *  ),
 *  @OA\Schema(
 *      schema="UnauthorizedError",
 *      title="Unauthorized",
 *      description="Unauthorized - Access token is missing or invalid",
 *      @OA\Property(
 *          property="message",
 *          type="string",
 *          example="Unauthorized - Access token is missing or invalid"
 *      )
 *  )
 * 
 *  @OA\Schema(
 *      schema="ForbiddenError",
 *      title="Forbidden",
 *      description="Forbidden - You don't have permission to access this resource",
 *      @OA\Property(
 *          property="message",
 *          type="string",
 *          example="Forbidden"
 *      )
 *  )
 *  
 *  @OA\Schema(
 *      schema="NotFoundError",
 *      title="Not Found",
 *      description="Not Found - The resource you are looking for was not found",
 *      @OA\Property(
 *          property="message",
 *          type="string",
 *          example="Not Found"
 *      )
 *  )
 *  
 *  @OA\Schema(
 *      schema="UnprocessableContent",
 *      title="Unprocessable Content",
 *      description="The request was well-formed but was unable to be followed due to semantic errors.",
 *      @OA\Property(
 *          property="message",
 *          type="string",
 *          example="Unprocessable Content"
 *      )
 *  )
 *  
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
