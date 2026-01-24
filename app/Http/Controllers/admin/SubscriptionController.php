<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tag;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class SubscriptionController extends Controller
{
    public function showPlans() {
        $plans= Plan::all();
        return view('admin.subscription.plans',compact([
            'plans',
        ]));
    }

    public function createCheckoutSession($planId) {
        $plan = Plan::findOrFail($planId);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session= Session::create([
            'payment_method_types'=>['card'],
            'line_items'=>[[
                'price'=>$plan->stripe_price_id,
                'quantity'=>1,
            ]],
            'mode'=>'subscription',
            'success_url'=>route('subscription.success',['planId'=>$planId]).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'=>route('subscription.cancel'),
            ]);
            return redirect($session->url);
    }

    public function success(Request $request,$planId){
        Subscription::create([
            'user_id'=>auth()->id(),
            'plan_id'=>$planId,
            'stripe_session_id'=>$request->session_id,
            'status'=>'active',
            'articles_remaining'=>Plan::find($planId)->articles_per_month,
        ]);
        return redirect()->route('admin.dashboard')->with('success','Subscription activated!');
    }
    public function cancel(){
        return view('subscriptions.cancel');
    }
}
