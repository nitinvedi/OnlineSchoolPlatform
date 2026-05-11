<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discussions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->enum('status', ['open', 'closed', 'archived'])->default('open');
            $table->boolean('is_pinned')->default(false);
            $table->integer('reply_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->timestamp('last_reply_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['course_id', 'status']);
            $table->index(['course_id', 'is_pinned', 'created_at']);
            $table->index('last_reply_at');
        });

        Schema::create('discussion_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discussion_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_reply_id')->nullable()->constrained('discussion_replies')->onDelete('cascade');
            $table->text('content');
            $table->integer('likes_count')->default(0);
            $table->enum('status', ['pending', 'approved', 'hidden'])->default('approved');
            $table->timestamps();
            $table->softDeletes();

            $table->index('discussion_id');
            $table->index(['user_id', 'created_at']);
            $table->index('parent_reply_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discussion_replies');
        Schema::dropIfExists('discussions');
    }
};
