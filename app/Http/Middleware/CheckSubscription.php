<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Subscription;

class CheckSubscription
{
    
    public function handle($request, Closure $next)
    {
        $subscription = Subscription::where('user_id', auth()->id())
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();

        if (!$subscription) {

            session()->put(
                'car_form_data',
                $request->except(['images','_token'])
            );

            return redirect()->route('subscription.page')
                ->with('error','You must subscribe before posting a car.');
        }

        return $next($request);
    }
}