<?php

namespace App\Http\Controllers\api\Group;

use App\Models\Group;
use Illuminate\Support\Facades\Auth;

trait TraitDestroy {
    /**
     * Remove the specified resource from storage.
     * 
     * @OA\Delete(
     *      path="/api/groups/{id}",
     *      tags={"Groups"},
     *      summary="Delete a group",
     *      description="This endpoint is used to delete a group.",
     *      operationId="destroy",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="The id of the group",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Group deleted successfully",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Group deleted successfully"
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedError")
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          @OA\JsonContent(ref="#/components/schemas/ForbiddenError")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *          @OA\JsonContent(ref="#/components/schemas/NotFoundError")
     *      ),
     *  )
     */
    public function destroy(Group $group)
    {
        $user = auth('sanctum')->user();

        if (!$group->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'You are not authorized to update this group'], 403);
        }

        $group->delete();

        return response()->json(['message' => 'Group deleted successfully'], 200);
    }
}
