<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_submission_id')->constrained()->onDelete('cascade');
            $table->foreignId('graded_by')->constrained('users')->onDelete('cascade');
            $table->decimal('score', 6, 2);
            $table->text('feedback')->nullable();
            $table->timestamps();

            $table->index('assignment_submission_id');
        });

        // Gradebook summary table
        Schema::create('gradebooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('final_grade', 6, 2)->nullable();
            $table->decimal('assignment_average', 6, 2)->nullable();
            $table->decimal('quiz_average', 6, 2)->nullable();
            $table->decimal('participation_score', 6, 2)->nullable();
            $table->integer('assignments_submitted')->default(0);
            $table->integer('assignments_graded')->default(0);
            $table->timestamps();

            $table->unique(['course_id', 'user_id']);
            $table->index(['course_id', 'final_grade']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gradebooks');
        Schema::dropIfExists('grades');
    }
};
