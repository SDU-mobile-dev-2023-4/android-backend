<?php

namespace App\Http\Controllers\api\Group;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait TraitUpdate {
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        $user = auth('sanctum')->user();

        if (!$group->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'You are not authorized to update this group'], 403);
        }

        $group->name = $request->name;

        $group->save();

        return response()->json(['message' => 'Group updated successfully'], 200);

    }
}
