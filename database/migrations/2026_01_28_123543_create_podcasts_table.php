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
        Schema::create('podcasts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->json('script_json'); // Stores: [{"speaker": "Host", "text": "..."}, {"speaker": "Guest", "text": "..."}]
            $table->string('audio_path'); // Final MP3 file
            $table->string('thumbnail');;
            $table->integer('duration')->default(0); // in seconds
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('author_id');
            $table->timestamp('published_at')->nullable();
            $table->integer('view_count')->default(0);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('author_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('podcasts');
    }
};
