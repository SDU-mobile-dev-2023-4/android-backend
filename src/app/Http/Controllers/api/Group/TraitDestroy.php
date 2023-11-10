<?php

namespace App\Http\Controllers\api\Group;

use App\Models\Group;
use Illuminate\Support\Facades\Auth;

trait TraitDestroy {
    /**
     * Remove the specified resource from storage.
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
