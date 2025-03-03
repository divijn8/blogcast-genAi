<?php

namespace App\Console\Commands;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetMonthlyArticleCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:reset-ai-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets AI generated count for free users and monthly subscription users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info("Starting Monthly Post Reset CountTask.....");
        $monthlyPlanIds = [1,2];

        $users= Subscription::whereIn('plan_id',[1,2])->where('status','active')->distinct('user_id')->get('user_id');
        User::whereIn('id',$users)->update(['articles_generated'=> 0]);
        
        $plan = Plan::find(1);
        Subscription::where('plan_id',1)->update(['articles_remaining'=>$plan->articles_per_month]);

        $plan = Plan::find(2);
        Subscription::where('plan_id',2)->update(['articles_remaining'=>$plan->articles_per_month]);

        Log::info("Monthly Active Subscriptions Reset Done....");

        $users= User::whereNotIn('id',Subscription::distinct('user_id')->where('status','active')->get('user_id'))->get('id');

        User::whereIn('id',$users)->update(['articles_generated'=> 0]);

        Log::info("Monthly Count Reset Done....");
    }
}
