<?php

namespace App\Http\Controllers\api\Group;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait TraitUpdate {
    /**
     * Update the specified resource in storage.
     * 
     * @OA\Put(
     *      path="/api/groups/{id}",
     *      tags={"Groups"},
     *      summary="Update a group",
     *      description="This endpoint is used to update a group.",
     *      operationId="update",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="The id of the group",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          required=true,
     *          description="The name of the group",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Group updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Group updated successfully"
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *        response=401,
     *        description="Unauthorized",
     *        @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          @OA\JsonContent(ref="#/components/schemas/ForbiddenError")
     *      ),
     *  )
     *              
     */
    public function update(Request $request, Group $group)
    {
        $user = auth('sanctum')->user();

        if (!$group->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'You are not authorized to update this group'], 403);
        }

        $group->name = $request->name;

        $group->save();

        return response()->json(['message' => 'Group updated successfully'], 200);

    }
}
