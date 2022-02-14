<?php

namespace App\Observers;

use App\Models\SocialiteUser;

class SocialiteUserObserver
{
    /**
     * Handle the SocialiteUser "creating" event.
     *
     * @param  \App\Models\SocialiteUser  $socialiteUser
     * @return void
     */
    public function creating(SocialiteUser $socialiteUser)
    {
        $socialiteUser->id = generate_uuid();
    }

    /**
     * Handle the SocialiteUser "created" event.
     *
     * @param  \App\Models\SocialiteUser  $socialiteUser
     * @return void
     */
    public function created(SocialiteUser $socialiteUser)
    {
        //
    }

    /**
     * Handle the SocialiteUser "updated" event.
     *
     * @param  \App\Models\SocialiteUser  $socialiteUser
     * @return void
     */
    public function updated(SocialiteUser $socialiteUser)
    {
        //
    }

    /**
     * Handle the SocialiteUser "deleted" event.
     *
     * @param  \App\Models\SocialiteUser  $socialiteUser
     * @return void
     */
    public function deleted(SocialiteUser $socialiteUser)
    {
        //
    }

    /**
     * Handle the SocialiteUser "restored" event.
     *
     * @param  \App\Models\SocialiteUser  $socialiteUser
     * @return void
     */
    public function restored(SocialiteUser $socialiteUser)
    {
        //
    }

    /**
     * Handle the SocialiteUser "force deleted" event.
     *
     * @param  \App\Models\SocialiteUser  $socialiteUser
     * @return void
     */
    public function forceDeleted(SocialiteUser $socialiteUser)
    {
        //
    }
}
