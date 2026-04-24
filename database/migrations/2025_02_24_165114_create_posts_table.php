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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('excerpt',1000);
            $table->longText('body');
            $table->unsignedBigInteger('category_id');
            $table->string('thumbnail',1000);
            $table->boolean('is_disabled')->default(false);
            $table->integer('report_count')->default(0);
            $table->enum('status', ['active', 'under_review', 'disabled'])->default('active');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
