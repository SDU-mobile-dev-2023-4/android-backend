<?php

namespace App\Http\Controllers\api\Group;

trait TraitIndex
{
    /**
     * Display a listing of the resource.
     *
     * return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/groups",
     *     tags={"Groups"},
     *     summary="Get all groups",
     *     description="Get all groups",
     *     operationId="groupIndex",
     *     security={{"bearerAuth":{}}},
     *     
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(
     *                     @OA\Property(
     *                       property="data",
     *                       type="array",
     *                       @OA\Items(
     *                          ref="#/components/schemas/Group"
     *                       )
     *                     )
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *     )
     * )
     */
    public function index()
    {
        // Get user
        $user = auth('sanctum')->user();

        // Get groups
        $groups = $user->groups()->with('users', 'expenses')->get();

        // Return the groups with users and expenses
        return $groups;
    }
}

