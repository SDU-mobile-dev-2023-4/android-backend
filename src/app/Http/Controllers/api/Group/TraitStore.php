<?php

namespace App\Http\Controllers\api\Group;

use App\Helper\Sanitizer;
use App\Http\Requests\api\Group\GroupStoreRequest;
use App\Models\Group;

trait TraitStore
{
    /**
     * Store a newly created resource in storage.
     * 
     * @OA\Post(
     *      path="/api/groups",
     *      tags={"Groups"},
     *      summary="Create a new group",
     *      description="This endpoint is used to create a new group.",
     *      operationId="grouStore",
     *      security={{"bearerAuth":{}}},
     *      
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
     *          description="Group created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/GroupWithUsersAndExpenses")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *      ),
     *      @OA\Response(
     *        response=422,
     *        description="Bad Request - One or more errors with the input data",
     *        @OA\JsonContent(ref="#/components/schemas/BadRequestError")
     *      )
     * )
     */
    public function store(GroupStoreRequest $request)
    {
        // Get input data
        $data = $request->validated();

        // Get user
        $user = auth('sanctum')->user();

        // Create group
        $group = new Group();
        $group->name = Sanitizer::sanitize($data['name']);
        $group->save();

        // Attach default user
        $group->users()->attach($user->id);

        // Prepare response
        $group->load('users', 'expenses');

        // Return response
        return response($group, 200);
    }
}
