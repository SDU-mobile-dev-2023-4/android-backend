<?php

namespace App\Http\Controllers\api\Group;
use Illuminate\Http\Request;
use App\Models\Group;

use Illuminate\Support\Facades\Auth;

trait TraitStore {
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $group = new Group();
        $group->name = $request->name;
        $group->save();

        $group->users()->attach($user->id);

    }
}
