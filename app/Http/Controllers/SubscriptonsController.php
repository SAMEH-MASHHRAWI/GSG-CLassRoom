<?php

namespace App\Http\Controllers;

use App\Actions\Createsubscription;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Requests\CreateSubscriptionRequest;
use Throwable;

class SubscriptonsController extends Controller
{
    public function store(CreateSubscriptionRequest $request, CreateSubscription $create)
    {
        $plan = Plan::findOrFail($request->post('plan_id'));
        $months = $request->post('period');
        dd($request->user()->id);
        try {
            $subscription = $create->create([
                'plan_id' => $plan->id,
                'user_id' => $request->user()->id,
                'price' => ($plan->price) * $months,
                'expires_at' => now()->addMonth($months),
                'status' => 'pending'
            ]);

            return redirect()->route('checkout', $subscription->id);
        } catch (Throwable $e) {
            return back()->with([
                'error' => $e->getMessage(),
            ]);
        }
    }
}
