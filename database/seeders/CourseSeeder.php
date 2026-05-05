<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $instructor = User::where('role', 'instructor')->first();
        $programmingCategory = Category::where('slug', 'programming')->first();

        if ($instructor && $programmingCategory) {
            $course = Course::create([
                'title' => 'Introduction to Laravel: Build Web Apps',
                'slug' => Str::slug('Introduction to Laravel: Build Web Apps'),
                'description' => 'Learn Laravel from scratch and build modern web applications with confidence. Master routing, databases, authentication, and more.',
                'overview' => 'This comprehensive course covers all the fundamentals of Laravel, one of the most popular PHP frameworks. Perfect for beginners and intermediate developers.',
                'category_id' => $programmingCategory->id,
                'instructor_id' => $instructor->id,
                'status' => 'published',
                'published_at' => now(),
                'rating' => 4.8,
            ]);

            $lessons = [
                ['title' => 'Getting Started with Laravel', 'type' => 'video', 'duration_minutes' => 15, 'is_free' => true],
                ['title' => 'Setting Up Your Environment', 'type' => 'video', 'duration_minutes' => 12, 'is_free' => true],
                ['title' => 'Understanding MVC Architecture', 'type' => 'video', 'duration_minutes' => 20, 'is_free' => false],
                ['title' => 'Routing in Laravel', 'type' => 'text', 'duration_minutes' => 10, 'is_free' => false],
                ['title' => 'Database Setup with Eloquent', 'type' => 'video', 'duration_minutes' => 25, 'is_free' => false],
            ];

            foreach ($lessons as $index => $lesson) {
                Lesson::create([
                    'title' => $lesson['title'],
                    'course_id' => $course->id,
                    'order' => $index + 1,
                    'description' => 'Learn ' . strtolower($lesson['title']) . ' in this lesson.',
                    'type' => $lesson['type'],
                    'duration_minutes' => $lesson['duration_minutes'],
                    'is_free' => $lesson['is_free'],
                    'content' => 'Lesson content for ' . $lesson['title'],
                ]);
            }
        }
    }
}
