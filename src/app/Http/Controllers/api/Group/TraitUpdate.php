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
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="The name of the group",
     *                      example="Europe trip",
     *                      nullable=false,
     *                      maxLength=255
     *                  ),
     *              )
     *          )
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
     *      ),
     *      @OA\Response(
     *        response=422,
     *        description="Bad Request - One or more errors with the input data",
     *        @OA\JsonContent(ref="#/components/schemas/BadRequestError")
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
