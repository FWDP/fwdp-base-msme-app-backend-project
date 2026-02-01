<?php

namespace App\Modules\Membership\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $subscription = $request->user()->activeSubscription;

        if (!$subscription || !$subscription->isActive()) {
            return response()->json([
                'message' => 'Subscription expired'
            ], 402);
        }

        return $next($request);
    }
}
