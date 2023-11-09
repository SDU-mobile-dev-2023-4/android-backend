<?php

namespace App\Http\Controllers\api\Group;

use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

trait TraitRemoveUserFromGroup {
    /**
     * Remove a user (self or another member) from the group.
     *
     * @group Groups
     *
     * @bodyParam user_id integer required The user ID to remove from the group. Set to your own user ID for self-removal.
     *
     * @response 200 {
     *     "message": "You have been removed from the group" (for self-removal)
     * }
     *
     * @response 200 {
     *     "message": "User removed from the group successfully" (for removing another member)
     * }
     *
     * @response 400 {
     *     "message": "User to remove is not a member of the group"
     * }
     *
     * @response 403 {
     *     "message": "You are not authorized to remove users from this group"
     * }
     */
    public function removeUserFromGroup(Request $request, Group $group)
    {
        $user = Auth::user();
        $userIdToRemove = $request->input('user_id'); // Get the user_id to remove from the request

        // Check if the user who is removing the member is a member of the group
        if ($group->users()->where('user_id', $user->id)->exists()) {
            // Check if the user to remove is not the person making the request (self-remove)
            if ($userIdToRemove == $user->id) {
                $group->users()->detach($userIdToRemove);
                return response()->json(['message' => 'You have been removed from the group'], 200);
            } else {
                // Check if the user to remove is a member of the group
                if ($group->users()->where('user_id', $userIdToRemove)->exists()) {
                    $group->users()->detach($userIdToRemove);
                    return response()->json(['message' => 'User removed from the group successfully'], 200);
                } else {
                    return response()->json(['message' => 'User to remove is not a member of the group'], 400);
                }
            }
        } else {
            return response()->json(['message' => 'You are not authorized to remove users from this group'], 403);
        }
    }
}
