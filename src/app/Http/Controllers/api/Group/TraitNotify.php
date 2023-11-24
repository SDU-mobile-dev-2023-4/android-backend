<?php

namespace App\Http\Controllers\api\Group;

use App\Helper\Sanitizer;
use App\Http\Requests\api\Group\GroupStoreRequest;
use App\Models\Group;

trait TraitNotify
{
    /**
     * Update the specified resource in storage.
     * 
     * @OA\Put(
     *      path="/api/groups/{id}/notify",
     *      tags={"Groups"},
     *      summary="Notify group members of unpaid expenses",
     *      description="This endpoint is used to notify group members of unpaid expenses, which are manually added by the user.",
     *      operationId="groupNotify",
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
     *          description="Group notified successfully",
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
    public function notify(Group $group)
    {
        // Get user
        $notifyingUser = auth('sanctum')->user();

        // Check if user is authorized to view this group
        if (!$group->user($notifyingUser->id)->exists()) {
            return response()->json(['message' => 'You are not authorized to view this group'], 403);
        }

        // Notify group members
        $group->users->each(function ($user) use ($group, $notifyingUser) {
            if ($user->id === $notifyingUser->id) {
                return;
            }

            $user->notify(new \App\Notifications\MissingPayment($group, $notifyingUser));
        });

        // Return response
        return response()->json(200);
    }
}
