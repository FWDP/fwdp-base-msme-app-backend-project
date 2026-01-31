<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
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
    public function expire(): void
    {
        $this->update([
            'status' => 'expired',
        ]);
    }

    /**
     * Activate subscription (admin action)
     */
    public function activate(int $durationDays): void
    {
        $this->update([
            'status'     => 'active',
            'start_date' => now(),
            'end_date'   => now()->addDays($durationDays),
        ]);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->whereIn('status', ['trial', 'active'])
            ->whereDate('end_date', '>=', now())
            ->latest();
    }

}
