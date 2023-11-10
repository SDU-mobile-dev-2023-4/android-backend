<?php

namespace App\Http\Controllers\api\Group;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * )
     */
    public function addUserToGroup(Request $request, Group $group)
    {
        $user = auth('sanctum')->user();
        $emailToAdd = $request->input('email'); // Get the email from the request

        // Check if user whos logged in is member of the group.
        if (!$group->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'You are not authorized to add users to this group'], 403);
        }

        // Check if the email is valid
        if (!filter_var($emailToAdd, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['message' => 'The email is not valid'], 400);
        }


        // Check if there is a user with the specified email
        if (!User::where('email', $emailToAdd)->exists()) {
            return response()->json(['message' => 'User with the specified email does not exist'], 400);
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
