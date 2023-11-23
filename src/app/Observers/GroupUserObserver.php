<?php

namespace App\Observers;

class GroupUserObserver
{
    public function created(object $groupUser): void
    {
        // Notify the new group member that they've been added to the group.
        // Get the new group member.
        $user = $groupUser->user;

        // Get the group.
        $group = $groupUser->group;

        // Get the current authenticated user.
        $addedBy = auth('sanctum')->user();

        // If the current authenticated user is the new group member, don't notify them.
        if ($addedBy->id === $user->id) {
            return;
        }

        // Notify the new group member that they've been added to the group.
        $user->notify(new \App\Notifications\AddedToGroup($group, $addedBy));
    }

    public function deleted(object $groupUser): void
    {
        // Notify the group member that they've been removed from the group.
        // Get the group member.
        $user = $groupUser->user;

        // Get the group.
        $group = $groupUser->group;

        // Get the current authenticated user.
        $removedBy = auth('sanctum')->user();

        if ($removedBy->id === $user->id) {
            return;
        }

        // Notify the group member that they've been removed from the group.
        $user->notify(new \App\Notifications\RemovedFromGroup($group, $removedBy));
    }
}
