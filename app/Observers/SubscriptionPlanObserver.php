<?php

namespace App\Observers;

use App\Models\SubscriptionPlan;

class SubscriptionPlanObserver
{
    /**
     * Handle the SubscriptionPlan "creating" event.
     *
     * @param  \App\Models\SubscriptionPlan  $subscriptionPlan
     * @return void
     */
    public function creating(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->id = generate_uuid();
    }

    /**
     * Handle the SubscriptionPlan "created" event.
     *
     * @param  \App\Models\SubscriptionPlan  $subscriptionPlan
     * @return void
     */
    public function created(SubscriptionPlan $subscriptionPlan)
    {
        //
    }

    /**
     * Handle the SubscriptionPlan "updated" event.
     *
     * @param  \App\Models\SubscriptionPlan  $subscriptionPlan
     * @return void
     */
    public function updated(SubscriptionPlan $subscriptionPlan)
    {
        //
    }

    /**
     * Handle the SubscriptionPlan "deleted" event.
     *
     * @param  \App\Models\SubscriptionPlan  $subscriptionPlan
     * @return void
     */
    public function deleted(SubscriptionPlan $subscriptionPlan)
    {
        //
    }

    /**
     * Handle the SubscriptionPlan "restored" event.
     *
     * @param  \App\Models\SubscriptionPlan  $subscriptionPlan
     * @return void
     */
    public function restored(SubscriptionPlan $subscriptionPlan)
    {
        //
    }

    /**
     * Handle the SubscriptionPlan "force deleted" event.
     *
     * @param  \App\Models\SubscriptionPlan  $subscriptionPlan
     * @return void
     */
    public function forceDeleted(SubscriptionPlan $subscriptionPlan)
    {
        //
    }
}
