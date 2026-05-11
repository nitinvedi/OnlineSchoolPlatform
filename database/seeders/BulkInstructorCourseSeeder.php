<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BulkInstructorCourseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Ensure there are categories
        $categories = Category::all();
        if ($categories->isEmpty()) {
            $this->call(CategorySeeder::class);
            $categories = Category::all();
        }

        // Create 5 instructors with known emails/password
        $instructors = [];
        for ($i = 1; $i <= 5; $i++) {
            $instructors[] = User::factory()->create([
                'name' => "Instructor {$i}",
                'email' => "instructor{$i}@example.com",
                'role' => User::ROLE_INSTRUCTOR,
                'password' => bcrypt('password'),
            ]);
        }

        // Titles for 10 sample courses
        $titles = [
            'Automated Testing Fundamentals',
            'Build APIs with Laravel',
            'Mastering Eloquent ORM',
            'Advanced Blade & Frontend',
            'Data Structures for Developers',
            'Intro to Machine Learning',
            'UX Design Essentials',
            'Project Management for Devs',
            'Conversational English for Tech',
            'Personal Productivity Hacks',
        ];

        // Create 10 courses distributed across instructors
        foreach ($titles as $index => $title) {
            $instructor = $instructors[$index % count($instructors)];
            $category = $categories->random();

            $slugBase = Str::slug($title);
            $slug = $slugBase;
            $suffix = 1;
            while (Course::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $suffix++;
            }

            Course::create([
                'title' => $title,
                'slug' => $slug,
                'description' => "This is the full description for {$title}.",
                'overview' => "Overview: {$title}",
                'category_id' => $category->id,
                'instructor_id' => $instructor->id,
                'status' => $index % 3 === 0 ? 'published' : 'draft',
                'published_at' => $index % 3 === 0 ? now() : null,
                'rating' => round(3 + mt_rand() / mt_getrandmax() * 2, 1),
                'student_count' => rand(0, 200),
            ]);
        }
    }
}
