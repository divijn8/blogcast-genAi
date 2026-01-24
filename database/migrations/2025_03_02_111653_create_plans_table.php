<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\Models\Plan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('stripe_plan_id');
            $table->string('interval');
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('articles_per_month');
            $table->string('stripe_price_id');
            $table->timestamps();
        });
        $plans=collect([
            ['name'=>'Basic Monthly Plan','stripe_plan_id'=>'prod_Tqo9pnnm3FNMLS','interval'=>'monthly','price'=>10,'articles_per_month'=>100,'stripe_price_id'=>'price_1St6XbAcTvZ5kp0YvSiUn3oL'],
            ['name'=>'Pro Monthly Plan(10% Off)','stripe_plan_id'=>'prod_TqoCO9S03Tspqv','interval'=>'monthly','price'=>29,'articles_per_month'=>500,'stripe_price_id'=>'price_1St6aSAcTvZ5kp0YGG4rYRlg'],
            ['name'=>'Basic Yearly Plan','stripe_plan_id'=>'prod_TqoDvxARFk9pf9','interval'=>'yearly','price'=>108,'articles_per_month'=>1200,'stripe_price_id'=>'price_1St6bIAcTvZ5kp0YrnsFPvtk'],
            ['name'=>'Pro Yearly Plan(10% Off)','stripe_plan_id'=>'prod_TqoFozKKjUyRGf','interval'=>'yearly','price'=>313,'articles_per_month'=>6000,'stripe_price_id'=>'price_1St6cjAcTvZ5kp0YdYQ0dTwd']
        ]);

        $plans->each(function($plan) {
            Plan::create($plan);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
