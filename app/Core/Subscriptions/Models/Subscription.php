<?php

namespace App\Core\Subscriptions\Models;

use App\Core\Payments\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription_plan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers / Business Rules
    |--------------------------------------------------------------------------
    */

    /**
     * Check if subscription is currently valid
     */
    public function isActive(): bool
    {
        return in_array($this->status, ['trial', 'active'])
            && $this->end_date >= Carbon::today();
    }

    /**
     * Mark subscription as expired
     */
    public function isExpired(): bool
    {
        return $this->end_date !== null && now()->gt($this->end_date);
    }

    public function expire() : void
    {
        $this->update(['status'  => 'expired']);
    }
}
