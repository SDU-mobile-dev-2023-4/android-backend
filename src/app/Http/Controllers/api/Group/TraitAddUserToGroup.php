<?php

namespace App\Http\Controllers\api\Group;

use App\Helper\Sanitizer;
use App\Http\Requests\api\Group\AddUserToGroupRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

trait TraitAddUserToGroup {
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
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The id of the group",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         required=true,
     *         description="The email of the user to add to the group",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User added to group successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User added to group successfully"
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(ref="#/components/schemas/ForbiddenError")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Content",
     *         @OA\JsonContent(ref="#/components/schemas/UnprocessableContent")
     *     )
     * )
     */
    public function addUserToGroup(AddUserToGroupRequest $request, Group $group)
    {
        $data = $request->validated();

        $user = auth('sanctum')->user();
        
        // Get the email from the request
        $emailToAdd = Sanitizer::sanitize($data['email']); 

        // Check if user whos logged in is member of the group.
        if (!$group->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'You are not authorized to add users to this group'], 403);
        }

        // Check if the new user is already member of the group
        if ($group->users()->where('email', $emailToAdd)->exists()) {
            return response()->json(['message' => 'User is already member of the group'], 400);
        }

        // Add the new user to the group
        $group->users()->attach(User::where('email', $emailToAdd)->first()->id);

        // Return message with success
        return response()->json(['message' => 'User added to group successfully'], 200);

    }
}
