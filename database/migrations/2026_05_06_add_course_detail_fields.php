<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->json('learning_outcomes')->nullable()->after('overview');
            $table->json('prerequisites')->nullable()->after('learning_outcomes');
            $table->enum('difficulty_level', ['Beginner', 'Intermediate', 'Advanced'])->default('Beginner')->after('prerequisites');
            $table->boolean('is_bestseller')->default(false)->after('difficulty_level');
            $table->timestamp('sale_ends_at')->nullable()->after('is_bestseller');
            $table->integer('articles_count')->default(0)->after('sale_ends_at');
            $table->integer('resources_count')->default(0)->after('articles_count');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'learning_outcomes',
                'prerequisites',
                'difficulty_level',
                'is_bestseller',
                'sale_ends_at',
                'articles_count',
                'resources_count',
            ]);
        });
    }
};
