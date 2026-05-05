<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Quizzes — one per lesson
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('pass_percentage')->default(70); // e.g. 70 = 70%
            $table->unsignedTinyInteger('max_attempts')->default(3);     // 0 = unlimited
            $table->timestamps();
        });

        // Questions belonging to a quiz
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->unsignedTinyInteger('order')->default(0);
            $table->unsignedTinyInteger('points')->default(1);
            $table->timestamps();
        });

        // Answer options for each question
        Schema::create('quiz_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_question_id')->constrained()->onDelete('cascade');
            $table->text('option_text');
            $table->boolean('is_correct')->default(false);
            $table->unsignedTinyInteger('order')->default(0);
            $table->timestamps();
        });

        // Student quiz attempts
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedSmallInteger('score')->default(0);       // points earned
            $table->unsignedSmallInteger('max_score')->default(0);   // total possible
            $table->unsignedTinyInteger('percentage')->default(0);   // 0–100
            $table->boolean('passed')->default(false);
            $table->json('answers');                                  // {question_id: option_id, ...}
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['quiz_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_options');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
    }
};
