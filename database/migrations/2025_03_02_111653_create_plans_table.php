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
            ['name'=>'Basic Monthly Plan','stripe_plan_id'=>'prod_Rru7tKR5Q3MS1C','interval'=>'monthly','price'=>10,'articles_per_month'=>100,'stripe_price_id'=>'price_1QyAJeQo3wFOzvgvjXu9kAXF'],
            ['name'=>'Pro Monthly Plan(10% Off)','stripe_plan_id'=>'prod_RruBXDCEmCIicX','interval'=>'monthly','price'=>29,'articles_per_month'=>500,'stripe_price_id'=>'price_1QyAMwQo3wFOzvgv25xn4TcQ'],
            ['name'=>'Basic Yearly Plan','stripe_plan_id'=>'prod_RruAOIK9j8Zzem','interval'=>'yearly','price'=>108,'articles_per_month'=>1200,'stripe_price_id'=>'price_1QyALyQo3wFOzvgvYAs6iAlb'],
            ['name'=>'Pro Yearly Plan(10% Off)','stripe_plan_id'=>'prod_RruCWQy8o4vUJZ','interval'=>'yearly','price'=>313,'articles_per_month'=>6000,'stripe_price_id'=>'price_1QyAO0Qo3wFOzvgvN8W3eY50']
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
