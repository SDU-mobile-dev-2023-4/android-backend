<?php

namespace App\Http\Controllers\api\Group;

use App\Helper\Sanitizer;
use App\Http\Requests\api\Group\GroupStoreRequest;
use App\Models\Group;

trait TraitUpdate
{
    /**
     * Update the specified resource in storage.
     * 
     * @OA\Put(
     *      path="/api/groups/{id}",
     *      tags={"Groups"},
     *      summary="Update a group",
     *      description="This endpoint is used to update a group.",
     *      operationId="groupUpdate",
     *      security={{"bearerAuth":{}}},
     *      
     *      @OA\Parameter(
     *          name="id",
     *          description="Group id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              example="1",
     *              nullable=false,
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          required=true,
     *          description="The name of the group",
     *          example="Europe trip",
     *          @OA\Schema(
     *            type="string",
     *            minLength=1,
     *            maxLength=255,
     *            example="Europe trip",
     *            nullable=false
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Group updated successfully",
     *          @OA\JsonContent(ref="#/components/schemas/GroupWithUsersAndExpenses")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="You are not authorized to edit this group",
     *          @OA\JsonContent(ref="#/components/schemas/ForbiddenError")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Group not found",
     *          @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *      )
     *  )
     *              
     */
    public function update(Group $group, GroupStoreRequest $request)
    {
        // Get input data
        $data = $request->validated();

        // Get user
        $user = auth('sanctum')->user();

        // Check if user is authorized to view this group
        if (!$group->user($user->id)->exists()) {
            return response()->json(['message' => 'You are not authorized to view this group'], 403);
        }

        // Update group
        $group->name = Sanitizer::sanitize($data['name']);
        $group->save();

        // Prepare response
        $group->load('users', 'expenses');

        // Return response
        return response($group, 200);
    }
}
