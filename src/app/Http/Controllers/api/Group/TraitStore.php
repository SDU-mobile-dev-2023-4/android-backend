<?php

namespace App\Http\Controllers\api\Group;

use Illuminate\Http\Request;
use App\Models\Group;

trait TraitStore {
    /**
     * Store a newly created resource in storage.
     * 
     * @OA\Post(
     *      path="/api/groups",
     *      tags={"Groups"},
     *      summary="Create a new group",
     *      description="This endpoint is used to create a new group.",
     *      operationId="store",
     *      security={{"bearerAuth":{}}},
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
     *          description="Group created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  example="Group 1"
     *              ),
     *              @OA\Property(
     *                  property="updated_at",
     *                  type="string",
     *                  format="date-time",
     *              ),
     *              @OA\Property(
     *                  property="created_at",
     *                  type="string",
     *                  format="date-time",
     *              ),
     *              @OA\Property(
     *                  property="id",
     *                  type="integer",
     *                  example="1"
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="You are not authenticated",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *      ),
     *                  
     * )
     */
    public function store(Request $request)
    {
        $user = auth('sanctum')->user();

        $group = new Group();
        $group->name = $request->name;
        $group->save();

        $group->users()->attach($user->id);

        return response($group,200);
    }
}
