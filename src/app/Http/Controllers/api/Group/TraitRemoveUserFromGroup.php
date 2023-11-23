<?php

namespace App\Http\Controllers\api\Group;

use App\Helper\Sanitizer;
use App\Http\Requests\api\Group\AddUserToGroupRequest;
use App\Models\Group;
use App\Models\User;

trait TraitRemoveUserFromGroup
{
    /**
     * Remove a user with a specific email from the group.
     *
     * @OA\Delete(
     *     path="/api/groups/{id}/users",
     *     tags={"Groups"},
     *     summary="Remove a user from a group",
     *     description="This endpoint is used to remove a user from a group.",
     *     operationId="RemoveUserFromGroup",
     *     security={{"bearerAuth":{}}},
     *     
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="The id of the group",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              example="1",
     *              nullable=false,
     *          ),
     *     ),
     *     
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="The email of the user.",
     *                      example="john.doe@example.com",
     *                      nullable=false,
     *                      minLength=2,
     *                      maxLength=255
     *                  ),
     *              )
     *          )
     *      ),
     *      
     *      @OA\Response(
     *         response=200,
     *         description="User remove from group successfully",
     *         @OA\JsonContent(ref="#/components/schemas/GroupWithUsersAndExpenses")
     *      ),
     *      @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *      ),
     *      @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(ref="#/components/schemas/ForbiddenError")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *          @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *      ),
     *      @OA\Response(
     *        response=422,
     *        description="Bad Request - One or more errors with the input data",
     *        @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *      ),
     * )
     */
    public function removeUserfromGroup(AddUserToGroupRequest $request, Group $group)
    {
        // Get input data
        $data = $request->validated();

        // Get user
        $user = auth('sanctum')->user();

        // Check if user whos logged in is member of the group.
        if (!$group->user($user->id)->exists()) {
            return response()->json(['message' => 'You are not authorized to add users to this group'], 403);
        }

        // Get the email from the request
        $emailToAdd = Sanitizer::sanitize($data['email']);

        // Check if the user exists
        $userToAdd = User::where('email', $emailToAdd)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'errors' => [
                    'email' => [
                        'User not found'
                    ]
                ]
            ], 400);
        }

        // Check if the user is already member of the group
        if ($group->email($emailToAdd)->exists()) {
            // Remove the user from the group
            $group->users()->detach($userToAdd->id);
        }

        // Prepare response
        $group->load('users', 'expenses');

        // Return response
        return response($group, 200);
    }
}
