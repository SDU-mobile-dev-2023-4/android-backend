<?php

namespace App\Http\Controllers\api\Group;

use App\Models\Group;

trait TraitShow
{
    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *      path="/api/groups/{id}",
     *      tags={"Groups"},
     *      summary="Get a group",
     *      description="Returns a single group with all its information",
     *      operationId="groupShow",
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
     *      @OA\Response(
     *          response=200,
     *          description="Group data",
     *          @OA\JsonContent(ref="#/components/schemas/GroupWithUsersAndExpenses")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="You are not authenticated",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="You are not authorized to view this group",
     *          @OA\JsonContent(ref="#/components/schemas/ForbiddenError")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Group not found",
     *          @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *      )
     * )
     */
    public function show(Group $group)
    {
        // Get user
        $user = auth('sanctum')->user();

        // Check if user is authorized to view this group
        if (!$group->user($user->id)->exists()) {
            return response()->json(['message' => 'You are not authorized to view this group'], 403);
        }

        // Load group data
        $group->load('users', 'expenses');

        // Return group
        return $group;
    }
}
