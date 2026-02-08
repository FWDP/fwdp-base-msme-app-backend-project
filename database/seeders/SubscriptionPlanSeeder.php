<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name'          => 'Trial',
                'billing_cycle' => 'trial',
                'duration_days' => 14,
                'is_active'     => true,
            ],
            [
                'name'          => 'Monthly',
                'billing_cycle' => 'monthly',
                'duration_days' => 30,
                'is_active'     => true,
            ],
            [
                'name'          => 'Yearly',
                'billing_cycle' => 'yearly',
                'duration_days' => 365,
                'is_active'     => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['billing_cycle' => $plan['billing_cycle']],
                $plan
            );
        }
    }
}
