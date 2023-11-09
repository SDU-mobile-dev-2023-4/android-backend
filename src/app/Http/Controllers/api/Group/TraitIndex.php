<?php

namespace App\Http\Controllers\api\Group;

use App\Models\Group;
use Illuminate\Support\Facades\Auth;

trait TraitIndex {
    /**
     * Display a listing of the resource.
     *
     * return \Illuminate\Http\Response
     *
     * @OA\Get(
     *   path="/api/groups",
     *   tags={"Groups"},
     *   summary="Get all groups",
     *   description="Get all groups",
     *
     *   @OA\Response(
	 *     response=200,
	 *     description="OK",
	 *     @OA\JsonContent(
	 *        allOf={
	 *          @OA\Schema(
	 *            @OA\Property(
	 *              type="array",
	 *              @OA\Items(
	 *                ref="#/components/schemas/Group"
	 *              )
	 *            )
	 *          )
	 *        }
	 *      )
	 *   ),
     * )
     */
    public function index()
    {
        $user = Auth::user();

        $groups = Group::where('user_id', $user->id)->get();

        return $groups;

    }
}

