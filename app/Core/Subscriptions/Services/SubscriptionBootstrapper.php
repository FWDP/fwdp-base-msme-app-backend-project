<?php

namespace App\Core\Subscriptions\Services;

use App\Core\Subscriptions\Models\SubscriptionPlan;
use App\Models\User;

class SubscriptionBootstrapper
{
    protected SubscriptionPlan $subscriptionPlan;
    public function createTrialFor(
        User $user,
    ): \Illuminate\Database\Eloquent\Model
    {
        return $user->subscriptions()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'subscription_plan_id' => $this->subscriptionPlan->newQuery()
                        ->where('name', '=','Free')
                        ->first()
                        ->toArray()['id'],
                'status'                => 'trial',
                'start_date'            => now(),
                'end_date'              => now()->addDays($this->subscriptionPlan->newQuery()
                                            ->where('name', '=','Free')
                                            ->first()
                                            ->toArray()['duration_days']),
            ]
        );
    }
}
