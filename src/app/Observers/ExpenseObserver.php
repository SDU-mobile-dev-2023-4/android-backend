<?php

namespace App\Observers;

class ExpenseObserver
{
    public function created(object $expense): void
    {
        $expense->group->users->each(function ($user) use ($expense) {
            $user->notify(new \App\Notifications\ExpenseAdded($expense));
        });
    }
}
