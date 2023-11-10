<?php

namespace App\Http\Controllers\api\Group;

trait TraitIndex {
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
     *     operationId="index",
     *     security={{"bearerAuth":{}}},
     *     
     *     @OA\Response(
	 *         response=200,
	 *         description="OK",
	 *         @OA\JsonContent(
	 *             allOf={
	 *                 @OA\Schema(
	 *                     @OA\Property(ref="#/components/schemas/Group")
	 *                 )
	 *             }
	 *         )
	 *     ),
     * )
     */
    public function index()
    {
        $user = auth('sanctum')->user();

        $groups = $user->groups()->with('users')->get();

        return $groups;
    }
}

