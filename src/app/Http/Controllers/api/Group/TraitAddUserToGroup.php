<?php

namespace App\Http\Controllers\api\Group;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait TraitAddUserToGroup {
    /**
     * Add a user with a specific email to the group.
     *
     * @group Groups
     *
     * @bodyParam email string required The email of the user to add to the group.
     *
     * @response 200 {
     *     "message": "User added to group successfully"
     * }
     *
     * @response 400 {
     *     "message": "User with the specified email does not exist"
     * }
     *
     * @response 403 {
     *     "message": "You are not authorized to add users to this group"
     * }
     */
    public function addUserToGroup(Request $request, Group $group)
    {
        $user = Auth::user();
        $emailToAdd = $request->input('email'); // Get the email from the request

        // Check if the user who is adding the member is already in the group
        if ($group->users()->where('user_id', $user->id)->exists()) {
            $userToAdd = User::where('email', $emailToAdd)->first();

            // Check if the user with the specified email exists
            if ($userToAdd) {
                $group->users()->attach($userToAdd->id);

                return response()->json(['message' => 'User added to group successfully'], 200);
            } else {
                return response()->json(['message' => 'User with the specified email does not exist'], 400);
            }
        } else {
            return response()->json(['message' => 'You are not authorized to add users to this group'], 403);
        }
    }
}
