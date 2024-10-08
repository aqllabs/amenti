<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\User;


class ActivityObserver
{
    /**
     * Handle the Activity "created" event.
     */
    public function created(Activity $activity): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $activity->attendees()->attach($user->id, []);
        }
    }

    /**
     * Handle the Activity "updated" event.
     */
    public function updated(Activity $activity): void
    {
        //
    }

    /**
     * Handle the Activity "deleted" event.
     */
    public function deleted(Activity $activity): void
    {
        //
    }

    /**
     * Handle the Activity "restored" event.
     */
    public function restored(Activity $activity): void
    {
        //
    }

    /**
     * Handle the Activity "force deleted" event.
     */
    public function forceDeleted(Activity $activity): void
    {
        //
    }
}
