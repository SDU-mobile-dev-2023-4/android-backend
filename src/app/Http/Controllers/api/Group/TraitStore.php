<?php

namespace App\Http\Controllers\api\Group;

use Illuminate\Http\Request;
use App\Models\Group;

trait TraitStore {
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth('sanctum')->user();

        $group = new Group();
        $group->name = $request->name;
        $group->save();

        $group->users()->attach($user->id);

        return response($group,200);
    }
}
