<?php

namespace App\Http\Controllers\api\Group;

use App\Helper\Sanitizer;
use App\Http\Requests\api\Group\AddUserToGroupRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

trait TraitAddUserToGroup
{
    /**
     * Add a user with a specific email to the group.
     *
     * @OA\Post(
     *     path="/api/groups/{id}/add-user",
     *     tags={"Groups"},
     *     summary="Add a user to a group",
     *     description="This endpoint is used to add a user to a group.",
     *     operationId="addUserToGroup",
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
     *     @OA\Parameter(
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
     *      @OA\Response(
     *         response=200,
     *         description="User added to group successfully",
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
    public function addUserToGroup(AddUserToGroupRequest $request, Group $group)
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

        // Check if the new user is already member of the group
        if ($group->email($emailToAdd)->exists()) {
            return response()->json([
                'message' => 'User is already member of the group',
                'errors' => [
                    'email' => [
                        'User is already member of the group'
                    ]
                ]
            ], 400);
        }

        // Add the new user to the group
        $group->users()->attach($userToAdd->id);

        // Prepare response
        $group->load('users', 'expenses');

        // Return response
        return response($group, 200);
    }
}
