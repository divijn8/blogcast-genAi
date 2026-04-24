<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            // user who reported
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // polymorphic relation (post or podcast)
            $table->unsignedBigInteger('reportable_id');
            $table->string('reportable_type');

            // reason + optional message
            $table->string('reason');
            $table->text('description')->nullable();

            $table->timestamps();

            $table->index(['reportable_id', 'reportable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
