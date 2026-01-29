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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            // Polymorphic parent (Post / Podcast / future)
            $table->unsignedBigInteger('commentable_id');
            $table->string('commentable_type');

            // Comment body
            $table->text('comment');

            // Auth user
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            // Guest support
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();

            // Replies
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('comments')
                ->onDelete('cascade');

            // Moderation
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');

            $table->timestamps();

            // Optional but good
            $table->index(['commentable_id', 'commentable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
