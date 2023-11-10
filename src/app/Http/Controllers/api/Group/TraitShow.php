<?php

namespace App\Http\Controllers\api\Group;

use App\Models\Group;
use Illuminate\Support\Facades\Auth;

trait TraitShow {
    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *      path="/api/groups/{id}",
     *      tags={"Groups"},
     *      summary="Get a group",
     *      description="Returns a single group with all its information",
     *      operationId="show",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="group",
     *          description="Group id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Group response",
     *          @OA\JsonContent(ref="#/components/schemas/Group")
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
        $user = auth('sanctum')->user();

        if (!$group->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'You are not authorized to view this group'], 403);
        }

        return $group;
    }
}
